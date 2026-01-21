<?php

namespace Bcs\Hooks;

use Bcs\Model\TestResult;

use Bcs\Backend\SendFormEmail;

use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FormFieldModel;
use Contao\FormModel;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use DateTime;

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class FormHooks
{

    public function onSubmitTest($answers, $formData, $files, $labels, $test)
    {
    }

    public function onPrepareFormData(&$answers, $labels, $fields, $test, &$files)
    {
        
        if($test->formType == 'test') {
            
            
            $correct_answers = [];
            
            $our_answers = [];

            // Grade the Test
            $total_questions = 0;
            $total_correct_answers = 0;
            
            foreach($answers as $question_id => $answer) {
                $total_questions++;
                
                $questions = FormFieldModel::findBy(['type = ?', 'pid = ?'], ['multiple_choice_question', $test->id]);
                
                foreach($questions as $question) {
                    if($question->name == $question_id) {
                        $options =  unserialize($question->options);
                        foreach($options as $option) {
                            if($option['value'] == $answer) {
                                if($option['correct'] == 1) {
                                    $total_correct_answers++;
                                    
                                    $correct_answers[] = $option['value'];
                                    $answers[$question->name] = 'correct';
                                    $our_answers[$question->name]['correct'] = 'yes';
                                    $our_answers[$question->name]['answer'] = $option['label'];
                                    
                                } else {
                                    $answers[$question->name] = 'incorrect';
                                    $our_answers[$question->name]['correct'] = 'no';
                                    $our_answers[$question->name]['answer'] = $option['label'];
                                }
                            }
                            
                        }
                    }
                }
                
            }

            // Get Member
            $member = FrontendUser::getInstance();

            // Create Test Result record
            $test_result = new TestResult();
            $test_result->tstamp = time();
            $test_result->test = $test->id;
            $test_result->member = $member->id;
            $test_result->submission_date = time();
            $test_result->answers = json_encode($our_answers);
            $test_result->result_total_correct = $total_correct_answers;
            $test_result->result_percentage = round(($total_correct_answers / $total_questions) * 100, 2);
            
            // Scoring
            if($test->scoringType == 'percentage_correct') {
                if($test_result->result_percentage >= $test->percentage)
                    $test_result->result_passed = 'yes';
                
            } else if ($test->scoringType == 'total_correct') {
                if($test_result->result_total_correct >= $test->total_correct)
                    $test_result->result_passed = 'yes';
            }
            
            
            $test_result->save();
            
            $this->sendCertificateEmail($member->email, $test_result->result_passed, $test_result->result_percentage, $test_result->id);
            

            // Pass our result ID to the result page
            $_SESSION['test_results_id'] = $test_result->id;
            
            $answers['member_name'] = $member->firstname . " " . $member->lastname;
            $answers['test_result_id'] = $test_result->id;
            $answers['test'] = $test->title;
            $answers['submission_date'] = date('m/d/Y g:i a', $test_result->submission_date);
            $answers['result_total_correct'] = $test_result->result_total_correct;
            $answers['result_percentage'] = $test_result->result_percentage;
            $answers['result_passed'] = $test_result->result_passed;
            $answers['correct_answers'] = serialize($correct_answers);

            
            
        }
        
    }
    
    
    
    public function sendCertificateEmail($emai, $passed, $percentage, $test_result_id) {

        $options = new Options();
        $options->set("defaultFont", "Times-Roman");
        $options->set("isRemoteEnabled", "true");
        $options->setChroot('/');
    	$dompdf = new Dompdf($options);
    	$context = stream_context_create([ 
        	'ssl' => [ 
        		'verify_peer' => FALSE, 
        		'verify_peer_name' => FALSE,
        		'allow_self_signed'=> TRUE 
        	] 
        ]);
        $dompdf->setHttpContext($context);

        
        /*****************************/
    	/* DATABASE STUFFS */
    	/*****************************/
        
        $test_result = [];
        $test = [];
        $member = [];
        
        $tr = TestResult::findOneBy('id', $test_result_id);
        if($tr) {
            $test_result['test'] = $tr->test;
            $test_result['member'] = $tr->member;
            $test_result['result_percentage'] = $tr->result_percentage;
            $test_result['submission_date'] = $tr->submission_date;
        }
        
        $form = FormModel::findById($test_result['test']);
        if($form) {
            $test['title'] = $form->title;
        }
        
        $mm = MemberModel::findOneBy('id', $test_result['member']);
        if($mm) {
            $member['name'] = $mm->firstname . " " . $mm->lastname;
        }
    
    	/*******************/
    	/* TEMPLATE STUFFS */
    	/*******************/
      	
        // Load our HTML template
        $html = file_get_contents('bundles/bcsosi/templates/certificate.html', true);
        
        preg_match_all('/\{{2}(.*?)\}{2}/is', $html, $tags);
        foreach($tags[0] as $tag) {
            
            // Remove brackets from our tag
            $cleanTag = str_replace("{{","",$tag);
            $cleanTag = str_replace("}}","",$cleanTag);
            
    	    $explodedTag = explode("::", $cleanTag);
    	    
    	    // Do different things based on the first part of our tag
    	    switch($explodedTag[0]) {
    		    
    		    // If the first part of our exploded tag is "product" we are looking for an attribute
    		    case 'member':
    		        switch($explodedTag[1]) {
    		            case 'name':
    		                $html = str_replace($tag, $member['name'], $html);
    		                break;
    		        }
    		    break;
    		    
    		    case 'test':
    		        switch($explodedTag[1]) {
    		            case 'title':
    		                $html = str_replace($tag, $test['title'], $html);
    		                break;
    		        }
    		    break;
    		    
    		    case 'result':
    		        switch($explodedTag[1]) {
    		            case 'id':
    		                $html = str_replace($tag, $test_result_id, $html);
    		                break;
    		            case 'submission_date':
    		                $html = str_replace($tag, date('F j, Y', $test_result['submission_date']), $html);
    		                break;
    		        }
    		    break;
    		    
    		    case 'server':
    		        switch($explodedTag[1]) {
    		            case 'root':
    		                $html = str_replace($tag, $_SERVER["DOCUMENT_ROOT"], $html);
    		                break;
    		        }
    		    break;
    
    	    }
            
        }
        
        /***********************/
    	/* GENERATE PDF STUFFS */
    	/***********************/
    	
        // Load our HTML into dompdf
    	$dompdf->loadHtml($html);
    	
    	// Set our paper size and orientation
    	$dompdf->setPaper('A4', 'landscape');
    	
    	// Render our PDF using the loaded HTML
    	$dompdf->render();
    	
    	$pdf_content = $dompdf->output();
    	
    	$to = $emai;
        $from = '"Occupational Services, Inc." <training@occserv.com>';
        $subject = "Results from your OSI online training";
        $filename = "document.pdf";
        
        // Create a unique boundary
        $boundary = md5(time());
        
        // Headers
        $headers = "From: $from\r\n";
        $headers .= "Reply-To: training@occserv.com\r\n"; // Optional: ensures replies go to the right place
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
        
        // Message Body
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        
        // Wrap content in HTML tags for better rendering
        $body .= "<html><body>";
        $body .= "<p>Hello " . htmlspecialchars($member['name']) . ",</p>";
        $body .= "<p>Thank you for taking the following training: " . htmlspecialchars($test['title']) . ".</p>";
        
        if($passed == 'yes') {
            $body .= "<p>You scored <strong>".$percentage."%</strong> and have passed.</p>";
            $body .= "<p>Thank you for taking this test and don't hesitate to contact us with any questions.</p>";
        } else {
            // The link will now work because the Content-Type is text/html
            $body .= "<p>Unfortunately you did not pass this training. Please return to the <a href='https://occserv.com/client-portal/my-trainings'>My Trainings page</a> and take this training again.</p>";
        }
        
        $body .= "<p>Sincerely,<br>The Occupational Services Team</p>";
        $body .= "</body></html>\r\n\r\n";
        
        if($passed == 'yes') {
            // Attachment
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
            $body .= chunk_split(base64_encode($pdf_content)) . "\r\n";
            $body .= "--$boundary--";
        }
        
        // Send the email
        if (mail($to, $subject, $body, $headers)) {
            //echo "Email sent successfully!";
        } else {
            //echo "Email delivery failed.";
        }
    	
    	

    }
    
    
    

}

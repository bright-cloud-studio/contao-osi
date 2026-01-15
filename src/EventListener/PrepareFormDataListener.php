<?php

declare(strict_types=1);


namespace Bcs\OSIBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Form;
use Symfony\Component\HttpFoundation\RequestStack;

use Bcs\Model\TestResult;
use Bcs\Backend\SendFormEmail;
use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FormFieldModel;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use DateTime;
use Terminal42\NotificationCenterBundle\NotificationCenter;

#[AsHook('prepareFormData')]
readonly class PrepareFormDataListener
{
    public function __construct(
        private FileUploadNormalizer $fileUploadNormalizer,
        private RequestStack $requestStack,
    ) {
    }

    public function __invoke(array &$answers, array &$labels, array $fields, Form $test, array &$files): void
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
}

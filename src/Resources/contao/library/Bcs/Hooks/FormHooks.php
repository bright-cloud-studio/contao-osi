<?php

namespace Bcs\Hooks;

use Bcs\Model\TestResult;

use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FormFieldModel;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\PageModel;
use Contao\StringUtil;
use DateTime;

class FormHooks
{

    public function onSubmitTest($answers, $formData, $files, $labels, $test)
    {
        if($test->formType == 'test') {
            
            // Grade the Test
            $total_questions = 0;
            $total_correct_answers = 0;
            
            foreach($answers as $question_id => $answer) {
                $total_questions++;
                $question = FormFieldModel::findOneBy('name', $question_id);
                $options =  unserialize($question->options);
                foreach($options as $option) {
                    if($option['value'] == $answer) {
                        if($option['correct'] == 1) {
                            $total_correct_answers++;
                        } else {
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
            $test_result->answers = json_encode($answers);
            $test_result->result_total_correct = $total_correct_answers;
            $test_result->result_percentage = ($total_correct_answers / $total_questions) * 100;
            
            $test_result->save();
            
            // Pass our result ID to the result page
            $_SESSION['test_results_id'] = $test_result->id;
            $_SESSION['test_123'] = '456';
            
            
        }
    }

}

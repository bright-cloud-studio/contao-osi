<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-leads-optin-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2025, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @author     Christopher Bölter
 * @author     Carsten Götzinger
 * @license    LGPL-3.0-or-later
 */

namespace Bcs\OSI\Hook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Form;
use Contao\Widget;

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
/**
 * Provides several function to access leads hooks and send notifications.
 */
#[AsHook('prepareFormData')]
class PrepareFormDataHook
{

    public function __construct(
        private readonly NotificationCenter $notificationCenter,
        private readonly Connection $db,
        private readonly StringParser $stringParser,
    ) {
    }
    /**
     * @param array<mixed>  $submittedData
     * @param array<mixed>  $labels
     * @param array<Widget> $fields
     */
    public function __invoke(array &$answers, array $labels, array $fields, Form $test): void
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
            $test_result->result_percentage = ($total_correct_answers / $total_questions) * 100;
            
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

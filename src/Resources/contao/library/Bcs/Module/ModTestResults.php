<?php

namespace Bcs\Module;

use Bcs\Model\TestResult;

use Contao\BackendTemplate;
use Contao\FormModel;
use Contao\FormFieldModel;
use Contao\Input;
use Contao\System;
use Contao\FrontendUser;

class ModTestResults extends \Contao\Module
{

    /* Default Template */
    protected $strTemplate = 'mod_test_results';

    /* Construct function */
    public function __construct($objModule, $strColumn='main')
    {
        parent::__construct($objModule, $strColumn);
    }

    /* Generate function */
    public function generate()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $objTemplate = new BackendTemplate('be_wildcard');
 
            $objTemplate->wildcard = '### ' . mb_strtoupper($GLOBALS['TL_LANG']['FMD']['assignments'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
 
            return $objTemplate->parse();
        }
 
        return parent::generate();
    }


    protected function compile()
    {
        $member = FrontendUser::getInstance();
        
        if(isset($_SESSION['test_results_id'])) {
            $results = TestResult::findOneBy('id', $_SESSION['test_results_id']);
            $test = FormModel::findOneBy('id', $results->test);
            
            $results_data = [];
            
            $results_data['id'] = $_SESSION['test_results_id'];
            $results_data['submission_date'] = date('m/d/Y g:i a', $results->submission_date);
            $results_data['total_correct_answers'] = $results->result_total_correct;
            $results_data['percentage'] = $results->result_percentage;
            $results_data['result_passed'] = $results->result_passed;
            $results_data['test_name'] = $test->title;
            
            
            // Questions
            $questions = FormFieldModel::findBy('pid', $results->test);
            // Answers
            $answers = json_decode($results->answers, true);

            
            $question_counter = 0;
            foreach($questions as $question) {
                
                if($question->type == 'multiple_choice_question') {
                    // Add question text to template data
                    $results_data['questions'][$question_counter]['question'] = $question->label;
                    
                    $options =  unserialize($question->options);
                    foreach($options as $option) {

                        if($answers[$question->name]['correct'] == 'yes') {
                            $results_data['questions'][$question_counter]['correct'] = 'true';
                            $results_data['questions'][$question_counter]['answer'] = $answers[$question->name]['answer'];
                        } else {
                            $results_data['questions'][$question_counter]['correct'] = 'false';
                            $results_data['questions'][$question_counter]['answer'] = $answers[$question->name]['answer'];
                        }

                    }
                    
                    $question_counter++;
                }
            }

            $this->Template->results = $results_data;
            
        }
        
    }
  

}

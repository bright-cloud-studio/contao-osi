<?php

namespace Bcs\Module;

use Bcs\Model\TestResult;

use Contao\BackendTemplate;
use Contao\FormFieldModel;
use Contao\Input;
use Contao\System;
use Contao\FrontendUser;

class ModTestHistory extends \Contao\Module
{

    /* Default Template */
    protected $strTemplate = 'mod_test_history';

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

    /* Compile */
    protected function compile()
    {
        $member = FrontendUser::getInstance();
        $results = TestResult::findBy('member', $member->id);
        
        $results_data = [];
        $result_counter = 0;
        foreach($results as $result) {
            $results_data[$result_counter]['id'] = $result->id;
            $results_data[$result_counter]['test'] = $result->test;
            $results_data[$result_counter]['submission_date'] = $result->submission_date;
            $results_data[$result_counter]['result_total_correct'] = $result->result_total_correct;
            $results_data[$result_counter]['result_percentage'] = $result->result_percentag;
        }
        
        $this->Template->results_history = $results_data;
    }
  
}

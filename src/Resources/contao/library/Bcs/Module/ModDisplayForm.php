<?php

namespace Bcs\Module;

use Bcs\Model\TestResult;

use Contao\ArrayUtil;
use Contao\Controller;
use Contao\BackendTemplate;
use Contao\FormModel;
use Contao\Input;
use Contao\System;
use Contao\FrontendUser;

class ModDisplayForm extends \Contao\Module
{

    /* Default Template */
    protected $strTemplate = 'mod_display_form';

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
        // Get the 'test_id' in the URL
        $test_id = Input::get('test');
        if($test_id != '') {
            
            // Get the Test
            $test = FormModel::findBy('id', $test_id);
            
            if($test->member_groups) {
                
                $member_groups = unserialize($test->member_groups);

                $this->Template->test_id = $test_id;
            }
        }
    }

}

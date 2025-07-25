<?php

namespace Bcs\Module;

use Bcs\Model\TestResult;

use Contao\ArrayUtil;
use Contao\Controller;
use Contao\BackendTemplate;
use Contao\FormModel;
use Contao\Input;
use Contao\MemberGroupModel;
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
        // Get the signed in Member and their assigned groups
        $member = FrontendUser::getInstance();
        $members_groups = $member->groups;

        $test_id = Input::get('test');
        
        $in_group = false;
        
        foreach($members_groups as $group) {
         
            $member_group = MemberGroupModel::findBy('id', $group);
            $assignments = unserialize($member_group->test_assignment);
            foreach($assignments as $assignment) {
                if($assignment == $test_id)
                    $in_group = true;
            }
            
        }
        
        if($in_group) {
            $test = FormModel::findBy('id', $test_id);
            $this->Template->test_id = $test_id;
            $this->Template->has_permission = 'true';
            $this->Template->test_title = $test->title;
            $this->Template->embed_code = $test->embed_code;
            $this->Template->additional_info = $test->additional_info;
        } else
            $this->Template->has_permission = 'false';
        
    }

}

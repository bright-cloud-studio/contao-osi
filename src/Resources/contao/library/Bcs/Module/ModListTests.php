<?php

namespace Bcs\Module;

use Bcs\Model\TestResult;

use Contao\ArrayUtil;
use Contao\Controller;
use Contao\BackendTemplate;
use Contao\FilesModel;
use Contao\FormModel;
use Contao\FrontendUser;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;

class ModListTests extends \Contao\Module
{

    /* Default Template */
    protected $strTemplate = 'mod_list_tests';

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
        $members_groups = $member->groups;
        
        $test_data = [];
        $tests = FormModel::findBy('formType', 'test');
        $test_counter = 0;
        foreach($tests as $test) {
            
            if($test->member_groups) {            
                
                $in_group = false;
                
                $member_groups = unserialize($test->member_groups);
                
                foreach($member_groups as $group) {
                    if (in_array($group, $members_groups))
                        $in_group = true;
                }
                
                if($in_group) {
                    $test_data[$test_counter]['id'] = $test->id;
                    $test_data[$test_counter]['title'] = $test->title;
                    
                    if($test->cert_image) {
                        $uuid = StringUtil::binToUuid($test->cert_image);
                        $objFile = FilesModel::findByUuid($uuid);
                        if ($objFile) {
            				$test_data[$test_counter]['cert_image'] = $objFile->path;
                        }
                    }
                    
                    $test_counter++;
                }
                
            }
            
            
        }
        
        $this->Template->tests = $test_data;
    }

}

<?php

namespace Bcs\Module;

use Bcs\Model\TestResult;

use Contao\BackendTemplate;
use Contao\FilesModel;
use Contao\FormModel;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Contao\FrontendUser;

class ModMyCertificates extends \Contao\Module
{

    /* Default Template */
    protected $strTemplate = 'mod_my_certificates';

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
        $results = TestResult::findBy(['member = ?', 'result_passed = ?'], [$member->id, 'yes']);
        
        $certificates = [];
        if($results) {
            foreach($results as $result) {
    
                
                $test = FormModel::findBy(['id = ?'], [$result->test]);
    
                
                $certificates[$result->test]['title'] = $test->title;
                
                $uuid = StringUtil::binToUuid($test->cert_image);
                $objFile = FilesModel::findByUuid($uuid);
                if ($objFile) {
    				$certificates[$result->test]['cert_image'] = $objFile->path;
    				$certificates[$result->test]['id'] = $result->id;
                }
                
    
            }
        }
        
        $this->Template->my_certificates = $certificates;
        
    }
  

}

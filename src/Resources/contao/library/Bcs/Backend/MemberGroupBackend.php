<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;

use Bcs\Model\TestResult;

class MemberGroupBackend extends Backend
{

    public function saveCallback($varValue, DataContainer $dc) {

        // Get the Test IDs
        $group_ids = unserialize($varValue);

        // Foreach Test
        foreach($group_ids as $id) {
            
            // Get any Test Results that are assigned to this Test
            $test_results = TestResult::findBy(['test = ?'], [$id]);
            if($test_results) {
                foreach($test_results as $test_result) {
                    
                    // Get any current Member Group values as an array
                    $mem_groups = unserialize($test_result->member_groups);
                    // Add our current Member Group to the list of selected Member Groups
                    $mem_groups[] = $dc->activeRecord->id;
                    // Save our changes
                    $test_result->member_groups = serialize($mem_groups);
                    $test_result->save();
                }
            }
            
        }


        return $varValue;
    }
    
    // Get Members as options for a Select DCA field
    public function getTests(DataContainer $dc) {
        $tests = array();
        $this->import('Database');
        $result = $this->Database->prepare("SELECT * FROM tl_form WHERE formType='test' ORDER BY title ASC")->execute();
        while($result->next())
        {
            $tests = $tests + array($result->id => $result->title . '[' . $result->id . ']');   
        }
        return $tests;
    }

}

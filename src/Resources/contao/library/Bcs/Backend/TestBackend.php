<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\FormModel;
use Contao\Image;
use Contao\Input;
use Contao\MemberModel;
use Contao\StringUtil;
use Bcs\Model\TestResult;

class TestBackend extends Backend
{
    // Get Members as options for a Select DCA field
    public function getMembers(DataContainer $dc) { 

        $members = array();

    		$this->import('Database');
    		$result = $this->Database->prepare("SELECT * FROM tl_member WHERE disable=0 ORDER BY firstname ASC")->execute();
    		while($result->next())
    		{
                // Add ti array with ID as the value and firstname lastname as the label
                $members = $members + array($result->id => ($result->firstname . " " . $result->lastname));   
    		}
      
        return $members;
	}

}

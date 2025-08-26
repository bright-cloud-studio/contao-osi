<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\FormModel;
use Contao\Image;
use Contao\Input;
use Contao\MemberModel;
use Contao\MemberGroupModel;
use Contao\StringUtil;
use Bcs\Model\TestResult;

class TestResultBackend extends Backend
{

    public function saveCallback($varValue, DataContainer $dc) {

        // Get the Test IDs
        $group_ids = unserialize($varValue);
        
        // Loop through each ID, add to new array where the key is the title
        $sorted = [];
        foreach($group_ids as $id) {
            $member_group = MemberGroupModel::findBy(['id = ?'], [$id]);
            $sorted[$member_group->name] = $member_group->id;
        }

        // send back a serialized array of our sorted version of selected values.
        return serialize($sorted);
        //return $varValue;
    }
    
    // Get Members as options for a Select DCA field
    public function getMemberGroups(DataContainer $dc) { 

        $member_groups = array();

		$this->import('Database');
		$result = $this->Database->prepare("SELECT * FROM tl_member_group WHERE disable=0 ORDER BY name ASC")->execute();
		while($result->next())
		{
            // Add ti array with ID as the value and firstname lastname as the label
            $member_groups = $member_groups + array($result->id => $result->name);   
		}

		return $member_groups;
	}
    
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

    // Get Tests as options for a Select DCA field
    public function getTests(DataContainer $dc) { 

        $tests = array();

		$this->import('Database');
		$result = $this->Database->prepare("SELECT * FROM tl_form WHERE formType='test' ORDER BY title ASC")->execute();
		while($result->next())
		{
            // Add ti array with ID as the value and firstname lastname as the label
            $tests = $tests + array($result->id => ($result->title));   
		}

		return $tests;
	}

    public function generateLabel($row, $label, $dc, $args)
    {
        // Clear out our current label
        $label = '';
        
        $label .= date('m/d/Y', $row['submission_date']) . " - ";
        
        if($row['result_passed'] == 'no')
            $label .= "<span style='color: red; font-weight: 600;'>Failed</span> - ";
        else if($row['result_passed'] == 'yes')
            $label .= "<span style='color: green; font-weight: 600;'>Failed</span> - ";
            
        $label .= "(" . $row['result_percentage'] . "%) - ";
        
        
        
        $test = FormModel::findBy('id', $row['test']);
        $label .= $test->title . " - ";
        
        $member = MemberModel::findBy('id', $row['member']);
        $label .= $member->firstname . " " . $member->lastname;
        
        return $label;
    }
    
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}	
	

	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_listing']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_listing']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_test_result SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);
		$this->log('A new version of record "tl_test_result.id='.$intId.'" has been created'.$this->getParentEntries('tl_listing', $intId), __METHOD__, TL_GENERAL);
	}
	
}

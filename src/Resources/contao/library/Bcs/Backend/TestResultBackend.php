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

    // Third party code, Mark St. Jean did not write this. I am not responsible for the quality of this function.
    public function exportTestResults()
	{
		$whereConditionFilter = '';
		if (!empty($_SESSION['_contao_be_attributes']['filter']['tl_test_result'])) {
			$filters = $_SESSION['_contao_be_attributes']['filter']['tl_test_result'];
			if (!empty($filters)) {
				$filterParts = [];

				foreach ($filters as $field => $value) {
					if ($field!="limit" && ($value !== '' && $value !== null)) {
						$filterParts[] = $field . " = '" . addslashes($value) . "'";
					}
				}

				if (!empty($filterParts)) {
					$whereConditionFilter = implode(' AND ', $filterParts);
				}
			}
		}

		$whereConditionSearch = '';
		if (!empty($_SESSION['_contao_be_attributes']['search']['tl_test_result'])) {
			$search  = $_SESSION['_contao_be_attributes']['search']['tl_test_result'] ?? [];
			if (!empty($search)) {
				$searchParts = [];

				// foreach ($search as $field => $value) {
				// 	if ($value !== '' && $value !== null) {
						// Build LIKE condition for each search field
						if(isset($search['value']) && $search['value'] !== '' && $search['value'] !== null){
							$searchParts[] = "tr.".$search['field'] . " LIKE '%" . addslashes($search['value']) . "%'";
						}
				// 	}
				// }

				if (!empty($searchParts)) {
					// join with OR
					$whereConditionSearch = '(' . implode(' OR ', $searchParts) . ')';
				}
			}
		}
		// Get all records
		$this->import('Database');
		$sql="SELECT tr.id,DATE(FROM_UNIXTIME(tr.submission_date)) as TestDate,if(tr.result_passed='yes','Passed','Failed') as result,tr.result_percentage,f.title,CONCAT(m.firstname,' ',m.lastname) as name FROM `tl_test_result` tr
			left JOIN tl_form f on tr.test=f.id
			left JOIN tl_member m on tr.member=m.id
			Where 1 ".($whereConditionFilter ? " AND ".$whereConditionFilter : "").($whereConditionSearch!='' ? " AND ".$whereConditionSearch : "")."
			ORDER BY `tr`.`id` DESC;";
		$testResults = $this->Database->prepare($sql)->execute()->fetchAllAssoc();

		if (empty($testResults)) {
			$csvContent='No test results found to export.';
			// Send headers to trigger CSV download
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename="test_results_' . date('Ymd_His') . '.csv"');
			header('Pragma: no-cache');
			header('Expires: 0');

			echo $csvContent;
			exit;
		}

		// Build CSV headers dynamically from field names
		$headers = array_keys($testResults[0]);
		$csvContent = implode(',', $headers) . "\n";

		// Build CSV rows
		foreach ($testResults as $row) {
			// Convert array values to CSV-safe format
			$escaped = array_map(function ($value) {
				$value = str_replace('"', '""', $value); // escape quotes
				return '"' . $value . '"';
			}, $row);

			$csvContent .= implode(',', $escaped) . "\n";
		}

		// Send headers to trigger CSV download
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="test_results_' . date('Ymd_His') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo $csvContent;
		exit;
	}
	
}

<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\FormModel;
use Contao\Image;
use Contao\Input;
use Contao\MemberModel;
use Contao\StringUtil;

class TestBackend extends Backend
{

    private static $fieldPositions = [];

    /**
     * @param array $row
     * @return string
     */
    public function generateRow($row)
    {
        $pid = $row['pid'];

        if (!isset(self::$fieldPositions[$pid])) {
            self::$fieldPositions[$pid] = [];
            
            // Fetch all field IDs for this PID, sorted by sorting
            $this->import('Database');
            $objFields = $this->Database
                ->prepare("SELECT id FROM tl_form_field WHERE pid=? ORDER BY sorting,id")
                ->execute($pid);

            $index = 1;
            while ($objFields->next()) {
                self::$fieldPositions[$pid][$objFields->id] = $index++;
            }
        }

        $number = isset(self::$fieldPositions[$pid][$row['id']]) ? self::$fieldPositions[$pid][$row['id']] : '?';
        
        $originalHtml = '';
        if (class_exists('tl_form_field')) {
            $tlFormField = new \tl_form_field();
            $originalHtml = $tlFormField->listFormFields($row);
        } else {
             // Fallback if class not found
             $originalHtml = $row['label'] . ' (' . $row['name'] . ')';
        }

        return sprintf(
            '<div style="display:flex; align-items:center;"><div style="color:#999; margin:10px; font-weight:bold; min-width:20px;">#%s</div><div style="flex-grow:1;">%s</div></div>',
            $number,
            $originalHtml
        );
    }

    public function getTrainingImages(DataContainer $dc) { 

        // Hold the psys
        $training_images = array();
        $training_images = $training_images + array('biohazard_waste' => 'Biohazard Waste');
        $training_images = $training_images + array('climbing_ladder' => 'Climbing Ladder');
        $training_images = $training_images + array('danger_labels' => 'Danger Labels');
        $training_images = $training_images + array('fire_alarm' => 'Fire Alarm');
        $training_images = $training_images + array('fire_extinguisher' => 'Fire Extinguisher');
        $training_images = $training_images + array('forklift_and_cone' => 'Forklift and Cone');
        $training_images = $training_images + array('hazmat_suit' => 'Hazmat Suit');
        $training_images = $training_images + array('hydration_break' => 'Hydration Break');
        $training_images = $training_images + array('keycard_entry' => 'Keycard Entry');
        $training_images = $training_images + array('lab_mouse' => 'Lab Mouse');
        $training_images = $training_images + array('laser_optics' => 'Laser Optics');
        $training_images = $training_images + array('monkey' => 'Monkey');
        $training_images = $training_images + array('office_argument' => 'Office Arguement');
        $training_images = $training_images + array('pallet_jack' => 'Pallet Jack');
        $training_images = $training_images + array('seatbelt' => 'Seatbelt');
        $training_images = $training_images + array('spine_pain' => 'Spine Pain');
        $training_images = $training_images + array('sterile_lab' => 'Sterile Lab');
        $training_images = $training_images + array('toxic_chemical_label' => 'Toxic Chemical Label');
        $training_images = $training_images + array('virus_safety' => 'Virus Safety');
        $training_images = $training_images + array('weighing_powder' => 'Weighing Powder');
        $training_images = $training_images + array('work_safety' => 'Work Safety');
        $training_images = $training_images + array('worker_safety_symbols' => 'Worker Safety Symbols');
		return $training_images;
	}
    
}

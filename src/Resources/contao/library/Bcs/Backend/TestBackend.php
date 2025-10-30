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

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

        $training_images = $training_images + array('image_1' => 'Image One');
        $training_images = $training_images + array('image_2' => 'Image Two');
        
		return $training_images;
	}
    
}

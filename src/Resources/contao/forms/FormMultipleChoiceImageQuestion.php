<?php

namespace Bcs\OSIBundle;

use Contao\FormRadio;
use Contao\Config;
use Contao\FormModel;

class FormMultipleChoiceImageQuestion extends FormRadio
{
    protected $strTemplate = 'form_radio_with_images';
    protected $strPrefix = 'widget widget-radio widget-multi-choice-question widget-multi-choice-image-question';
  
}

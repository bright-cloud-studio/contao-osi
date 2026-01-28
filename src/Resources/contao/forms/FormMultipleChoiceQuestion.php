<?php

namespace Bcs\OSIBundle;

use Contao\FormRadio;
use Contao\Config;
use Contao\FormModel;

class FormMultipleChoiceQuestion extends FormRadio
{
    protected $strTemplate = 'form_multi_choice_question';
    protected $strPrefix = 'widget widget-radio widget-multi-choice-question';
}

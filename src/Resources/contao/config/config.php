<?php

use Bcs\OSIBundle\FormMultipleChoiceQuestion;
use Bcs\MultiChoiceWizard;

// Customized version of Contao's Form Checkbox field
$GLOBALS['TL_FFL']['multiple_choice_question'] = 'Bcs\OSIBundle\FormMultipleChoiceQuestion';

// Customized version of the "OptionWizard" Contao Backend Form Widget
$GLOBALS['BE_FFL']['multi_choice_wizard'] = 'Bcs\MultiChoiceWizard';

// Hook when form is submitted
$GLOBALS['TL_HOOKS']['processFormData'][]      = array('Bcs\Hooks\FormHooks', 'onSubmitTest');

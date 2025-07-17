<?php

use Bcs\OSIBundle\FormMultipleChoiceQuestion;
use Bcs\MultiChoiceWizard;

// Module to display a Test's results
$GLOBALS['FE_MOD']['osi']['mod_test_results'] = 'Bcs\Module\ModTestResults';
$GLOBALS['FE_MOD']['osi']['mod_test_history'] = 'Bcs\Module\ModTestHistory';
$GLOBALS['FE_MOD']['osi']['mod_display_form'] = 'Bcs\Module\ModDisplayForm';
$GLOBALS['FE_MOD']['osi']['mod_display_tests'] = 'Bcs\Module\ModDisplayTests';


// Customized version of Contao's Form Checkbox field
$GLOBALS['TL_FFL']['multiple_choice_question'] = 'Bcs\OSIBundle\FormMultipleChoiceQuestion';

// Customized version of the "OptionWizard" Contao Backend Form Widget
$GLOBALS['BE_FFL']['multi_choice_wizard'] = 'Bcs\MultiChoiceWizard';

// Hook when form is submitted
$GLOBALS['TL_HOOKS']['processFormData'][]      = array('Bcs\Hooks\FormHooks', 'onSubmitTest');

$GLOBALS['TL_MODELS']['tl_test_result'] = 'Bcs\Model\TestResult';

$GLOBALS['BE_MOD']['content']['test_result'] = array(
	'tables' => array('tl_test_result')
);

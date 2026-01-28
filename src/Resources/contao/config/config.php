<?php

use Contao\System;
use Bcs\OSIBundle\FormMultipleChoiceQuestion;
use Bcs\OSIBundle\FormMultipleChoiceQuestionMultipleAnswers;
use Bcs\MultiChoiceWizard;

// Module to display a Test's results
$GLOBALS['FE_MOD']['osi']['mod_test_results'] = 'Bcs\Module\ModTestResults';
$GLOBALS['FE_MOD']['osi']['mod_my_certificates'] = 'Bcs\Module\ModMyCertificates';
$GLOBALS['FE_MOD']['osi']['mod_test_history'] = 'Bcs\Module\ModTestHistory';
$GLOBALS['FE_MOD']['osi']['mod_display_form'] = 'Bcs\Module\ModDisplayForm';
$GLOBALS['FE_MOD']['osi']['mod_list_tests'] = 'Bcs\Module\ModListTests';
$GLOBALS['FE_MOD']['osi']['mod_generate_certificate'] = 'Bcs\Module\ModGenerateCertificate';


// Customized version of Contao's Form Checkbox field
$GLOBALS['TL_FFL']['multiple_choice_question'] = 'Bcs\OSIBundle\FormMultipleChoiceQuestion';
$GLOBALS['TL_FFL']['multiple_choice_question_multiple_answers'] = 'Bcs\OSIBundle\FormMultipleChoiceQuestionMultipleAnswers';

// Customized version of the "OptionWizard" Contao Backend Form Widget
$GLOBALS['BE_FFL']['multi_choice_wizard'] = 'Bcs\MultiChoiceWizard';

// Hook when form is submitted
//$GLOBALS['TL_HOOKS']['processFormData'][]      = array('Bcs\Hooks\FormHooks', 'onSubmitTest');
$GLOBALS['TL_HOOKS']['prepareFormData'][]      = array('Bcs\Hooks\FormHooks', 'onPrepareFormData');

$GLOBALS['TL_MODELS']['tl_test_result'] = 'Bcs\Model\TestResult';

$GLOBALS['BE_MOD']['content']['test_result'] = array(
	'tables' => array('tl_test_result')
);

/* Add Backend CSS */
$request = System::getContainer()->get('request_stack')->getCurrentRequest();
if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
{
    //$GLOBALS['TL_JAVASCRIPT'][] = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js';
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/bcsosi/js/be_helper.js';
    
    $GLOBALS['TL_CSS'][]        = 'bundles/bcsosi/css/be_multiple_choice.css';
}

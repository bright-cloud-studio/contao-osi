<?php

use Contao\Config;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Database;
use Contao\FormModel;
use Contao\Input;
use Contao\System;

// Add our custom 'multiple_choice_question' palette
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['multiple_choice_question'] = '{type_legend},type,name,label,label_image;{fconfig_legend},mandatory,help;{options_legend},options;{expert_legend:hide},class;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['multiple_choice_question_multiple_answers'] = '{type_legend},type,name,label,label_image;{fconfig_legend},mandatory,help;{options_legend},options;{expert_legend:hide},class;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['multiple_choice_image_question'] = '{type_legend},type,name,label;{fconfig_legend},mandatory,help;{options_legend},options;{expert_legend:hide},class;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';

// Override the default 'options_callback' for the 'type' field with our custom function below
$GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options_callback'] = array('tl_test_field', 'getFilteredFields');

$GLOBALS['TL_DCA']['tl_form_field']['fields']['options']['inputType'] = 'multi_choice_wizard';

// Change the SQL definition of the “label” field to TEXT so Contao’s database updater will ALTER the column for you.
$GLOBALS['TL_DCA']['tl_form_field']['fields']['label']['eval']['maxlength'] = null;
$GLOBALS['TL_DCA']['tl_form_field']['fields']['label']['sql'] = "text NULL";

$GLOBALS['TL_DCA']['tl_form_field']['fields'] += [
    'label_image' => [
        'label'             => &$GLOBALS['TL_LANG']['tl_form_field']['label_image'],
        'inputType' => 'fileTree',
        'eval'      => array(
            'filesOnly'   => true,
            'fieldType'   => 'radio',
            'extensions'  => Config::get('validImageTypes'),
            'tl_class'    => 'clr w50'
        ),
        'sql'       => "binary(16) NULL"
    ]
];

// customized extension of the 'tl_form_field' class
class tl_test_field extends \tl_form_field
{

    // Customized function to ask as a sort of switch, only displaying our custom 'multiple_choice_question' for 'test' Form types
	public function getFilteredFields(DataContainer $dc)
	{
        // Get our parent Form and check the type
        $parent_form = FormModel::findOneBy('id', $dc->activeRecord->pid);
        if($parent_form->formType == 'test') {
            $fields[] = 'multiple_choice_question';
            $fields[] = 'multiple_choice_question_multiple_answers';
            $fields[] = 'submit';
            return $fields;
        } else {
            
    		$fields = array();
    		$security = System::getContainer()->get('security.helper');
    
    		foreach ($GLOBALS['TL_FFL'] as $k=>$v)
    		{
    			if ($security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_FIELD_TYPE, $k))
    			{
                    if($k != 'multiple_choice_question' && $k != 'multiple_choice_question_multiple_answers')
    				    $fields[] = $k;
    			}
    		}
            
    		return $fields;
        }
	}
}

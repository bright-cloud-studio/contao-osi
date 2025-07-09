<?php

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Database;
use Contao\FormModel;
use Contao\Input;
use Contao\System;



$GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options_callback'] 	= array('tl_test_field', 'getFilteredFields');


class tl_test_field extends \tl_form_field
{
	public function getFilteredFields(DataContainer $dc)
	{
        // Get our parent Form and check the type
        $parent_form = FormModel::findOneBy('id', $dc->activeRecord->pid);
        if($parent_form->formType == 'test') {
            echo "Test!";
            die();
        }

		$fields = array();
		$security = System::getContainer()->get('security.helper');

		foreach ($GLOBALS['TL_FFL'] as $k=>$v)
		{
			if ($security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_FIELD_TYPE, $k))
			{
				$fields[] = $k;
			}
		}
        
		return $fields;
	}
}

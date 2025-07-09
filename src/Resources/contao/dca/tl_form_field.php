<?php

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Database;
use Contao\Input;
use Contao\System;

$GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options_callback'] 	= array('tl_test_field', 'getFields');


class tl_test_field extends \tl_form_field
{
	public function getFields()
	{
		$fields = array();
		$security = System::getContainer()->get('security.helper');

		foreach ($GLOBALS['TL_FFL'] as $k=>$v)
		{
			if ($security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_FIELD_TYPE, $k))
			{
				$fields[] = $k;
			}
		}

        echo "<pre>";
        print_r($fields);
        echo "</pre>";
        die();
        
		return $fields;
	}
}

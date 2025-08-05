<?php

use Contao\DataContainer;
use Contao\Database;
use Contao\Input;
use Contao\System;

// Insert the newsType field into the palette at an appropriate spot.
$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = str_replace(
    '{account_legend}',
    '{test_legend},test_assignment;{account_legend}',
    $GLOBALS['TL_DCA']['tl_member_group']['palettes']['default']
);

/* MEMBER GROUP SELECTION */
$GLOBALS['TL_DCA']['tl_member_group']['fields']['test_assignment'] = array(
    'label'            => &$GLOBALS['TL_LANG']['tl_member_group']['test_embed'],
    'inputType'        => 'checkboxWizard',
    'eval'             => array('multiple'=> true, 'mandatory'=>false, 'tl_class'=>'long'),
    'flag'             => DataContainer::SORT_ASC,
    'options_callback' => array('Bcs\Backend\MemberGroupBackend', 'getTests'),
    'sql'              => "blob NULL",
    'save_callback' => array
	(
		array('Bcs\Backend\MemberGroupBackend', 'saveCallback')
	),
);

<?php

/**
 * @copyright  Bright Cloud Studio
 * @author     Bright Cloud Studio
 * @package    Contao CE Chart
 * @license    LGPL-3.0+
 * @see        https://github.com/bright-cloud-studio/dixon-inspiration
 */

use Contao\DataContainer;
use Contao\Database;
use Contao\Input;
use Contao\System;

// Insert the newsType field into the palette at an appropriate spot.
$GLOBALS['TL_DCA']['tl_form']['palettes']['default'] = str_replace(
    '{title_legend}',
    '{type_legend},formType;{title_legend}',
    $GLOBALS['TL_DCA']['tl_form']['palettes']['default']
);

// Append 'newsType' to the existing __selector__ array rather than overwriting it.
$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'formType';

// Define subpalettes for the various newsType options
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['formType_test'] = ';{test_content_legend},embed_code,additional_info;{member_group_legend},member_groups;';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['formType_default'] = '';


// New 'formType' selector
$GLOBALS['TL_DCA']['tl_form']['fields']['formType'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['formType'],
    'inputType' => 'select',
    'options'   => array(
        'test' => 'Test',
        'default' => 'Default'
    ),
    'filter'    => true,
    'eval'      => array('submitOnChange' => true, 'mandatory' => true, 'tl_class' => 'w50'),
    'default'   => 'default',
    'sql'       => "varchar(255) NULL default 'default'"
);


/* TEST PAGE CONTENT */
$GLOBALS['TL_DCA']['tl_form']['fields']['embed_code'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['test_embed'],
    'inputType' => 'textarea',
    'eval'      => array('allowHtml' => true, 'mandatory' => false, 'tl_class' => 'w100'),
    'default'   => 'default',
    'sql'       => "text NULL"
);
$GLOBALS['TL_DCA']['tl_form']['fields']['additional_info'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['test_embed'],
    'inputType' => 'textarea',
    'eval'      => array('allowHtml' => true, 'mandatory' => false, 'tl_class' => 'w100'),
    'default'   => 'default',
    'sql'       => "text NULL"
);


/* MEMBER GROUP SELECTION */
$GLOBALS['TL_DCA']['tl_form']['fields']['member_groups'] = array(
    'label'            => &$GLOBALS['TL_LANG']['tl_form']['test_embed'],
    'inputType'        => 'checkbox',
    'eval'             => array('multiple'=> true, 'mandatory'=>false, 'tl_class'=>'long'),
    'flag'             => DataContainer::SORT_ASC,
    'options_callback' => array('Bcs\Backend\TestBackend', 'getMemberGroups'),
    'sql'              => "blob NULL"
);


// Ensure newsType dropdown appears when editing old content
$GLOBALS['TL_DCA']['tl_form']['config']['onload_callback'][] = function (DataContainer $dc) {
    if (!$dc->id) {
        return;
    }

    $newsType = Database::getInstance()
        ->prepare("SELECT formType FROM tl_form WHERE id=?")
        ->execute($dc->id)
        ->newsType;
};


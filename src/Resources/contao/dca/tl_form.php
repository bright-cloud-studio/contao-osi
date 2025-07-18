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
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['formType_test'] = '{test_legend},test_embed;';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['formType_default'] = '';


// Fields for the 'newsType' selection
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


$GLOBALS['TL_DCA']['tl_form']['fields']['test_embed'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['test_embed'],
    'inputType' => 'textarea',
    'eval'      => array('mandatory' => false, 'tl_class' => 'w50'),
    'default'   => 'default',
    'sql'       => "text NULL"
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


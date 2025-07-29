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

// Append Publish Legend to palettes
$GLOBALS['TL_DCA']['tl_form']['palettes']['default'] .= '{publish_legend},publish;';

// Append 'newsType' to the existing __selector__ array rather than overwriting it.
$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'formType';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['formType_test'] = ';{test_content_legend},embed_code,additional_info;{scoring_legend}, scoringType;{certificate_legend}, cert_image;';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['formType_default'] = '';

$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'scoringType';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['scoringType_percentage_correct'] = 'percentage;';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['scoringType_total_correct'] = 'total_correct';

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


// New 'formType' selector
$GLOBALS['TL_DCA']['tl_form']['fields']['scoringType'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['scoringType'],
    'inputType' => 'select',
    'options'   => array(
        'percentage_correct' => 'Percentage',
        'total_correct' => 'Total Correct Answers'
    ),
    'filter'    => true,
    'eval'      => array('submitOnChange' => true, 'mandatory' => true, 'tl_class' => 'w50'),
    'default'   => 'percentage_correct',
    'sql'       => "varchar(20) NULL default 'percentage_correct'"
);
$GLOBALS['TL_DCA']['tl_form']['fields']['percentage'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['percentage'],
    'inputType' => 'text',
    'eval'      => array('allowHtml' => false, 'mandatory' => false, 'tl_class' => 'w100'),
    'default'   => '0',
    'sql'       => "text NULL"
);
$GLOBALS['TL_DCA']['tl_form']['fields']['total_correct'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['total_correct'],
    'inputType' => 'text',
    'eval'      => array('allowHtml' => false, 'mandatory' => false, 'tl_class' => 'w100'),
    'default'   => '0',
    'sql'       => "text NULL"
);


/* CERTIFICATE */
$GLOBALS['TL_DCA']['tl_form']['fields']['cert_image'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_form']['cert_image'],
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => array(
        'fieldType' => 'radio',
        'filesOnly' => true,
        'extensions' => '%contao.image.valid_extensions%',
        'mandatory' => false // Set to true if it's required
    ),
    'sql'       => "binary(16) NULL"
);



// Publish toggle
$GLOBALS['TL_DCA']['tl_form']['fields']['publish'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form']['test_embed'],
    'toggle' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'default' => '1',
    'eval' => array('doNotCopy'=>true),
    'sql' => array('type' => 'boolean', 'default' => true)
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


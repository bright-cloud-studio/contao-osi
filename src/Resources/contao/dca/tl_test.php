<?php

use Contao\Backend;
use Contao\BackendUser;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;

$GLOBALS['TL_DCA']['tl_test'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => DC_Table::class,
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'ctable'                      => array('tl_form_field'),
		'markAsCopy'                  => 'title',
		'onload_callback' => array
		(
			array('tl_test', 'adjustDca')
		),
		'oncreate_callback' => array
		(
			array('tl_test', 'adjustPermissions')
		),
		'oncopy_callback' => array
		(
			array('tl_test', 'adjustPermissions')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'tstamp' => 'index',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => DataContainer::MODE_SORTED,
			'fields'                  => array('title'),
			'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
			'panelLayout'             => 'filter;search,limit',
			'defaultSearchField'      => 'title'
		),
		'label' => array
		(
			'fields'                  => array('title', 'formID'),
			'format'                  => '%s <span class="label-info">[%s]</span>'
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('sendViaEmail', 'storeValues'),
		'default'                     => '{title_legend},title,alias,jumpTo;{config_legend},ajax,allowTags,storeSession;{confirm_legend},confirmation;{email_legend},sendViaEmail;{store_legend:hide},storeValues;{template_legend:hide},customTpl;{expert_legend:hide},method,novalidate,attributes,formID'
	),

	// Sub-palettes
	'subpalettes' => array
	(
		'sendViaEmail'                => 'mailerTransport,recipient,subject,format,skipEmpty',
		'storeValues'                 => 'targetTable'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'title' => array
		(
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_test', 'generateAlias')
			),
			'sql'                     => "varchar(255) BINARY NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'confirmation' => array
		(
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'basicEntities'=>true, 'helpwizard'=>true),
			'explanation'             => 'insertTags',
			'sql'                     => "text NULL"
		),
		'sendViaEmail' => array
		(
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		),
		'mailerTransport' => array
		(
			'inputType'               => 'select',
			'eval'                    => array('tl_class'=>'w50', 'includeBlankOption'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'recipient' => array
		(
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>1022, 'rgxp'=>'emails', 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(1022) NOT NULL default ''"
		),
		'subject' => array
		(
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'format' => array
		(
			'inputType'               => 'select',
			'options'                 => array('raw', 'xml', 'csv', 'csv_excel', 'email'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form'],
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(12) NOT NULL default 'raw'"
		),
		'skipEmpty' => array
		(
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		),
		'storeValues' => array
		(
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		),
		'targetTable' => array
		(
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_test', 'getAllTables'),
			'eval'                    => array('chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'customTpl' => array
		(
			'inputType'               => 'select',
			'options_callback' => static function () {
				return Controller::getTemplateGroup('form_wrapper_', array(), 'form_wrapper');
			},
			'eval'                    => array('chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'method' => array
		(
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => array('POST', 'GET'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(12) NOT NULL default 'POST'"
		),
		'novalidate' => array
		(
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		),
		'attributes' => array
		(
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'formID' => array
		(
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>true, 'doNotCopy'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'ajax' => array
		(
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w25'),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		),
		'allowTags' => array
		(
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w25'),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		),
		'storeSession' => array
		(
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => array('type' => 'boolean', 'default' => false)
		)
	)
);

class tl_test extends tl_form
{
  
}

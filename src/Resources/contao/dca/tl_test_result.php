<?php

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_test_result'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' 	=> 	'primary',
                'alias' =>  'index'
            )
        )
    ),
 
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'panelLayout'             => 'filter;search,limit,sort',
            'fields'                  => array('approved','country'),
            'flag'                    => 12,
        ),
        'label' => array
        (
            'fields'                  => array('test', 'member', 'submission_date'),
            'format'                  => '%s - %s - %s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_test_result']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
			
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_test_result']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_test_result']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_test_result']['toggle'],
                'icon'                => 'visible.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('Bcs\Backend\TestResultBackend', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_test_result']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
    // Palettes
    'palettes' => array
    (
        'default'                       => '{test_legend}test, submission_date, result_total_correct, result_percentage, answers;{publish_legend},published;'
    ),
 
    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                       => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                       => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array
        (
            'sql'                       => "int(10) unsigned NOT NULL default '0'"
        ),

        'test' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['test'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        
        'submission_date' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['submission_date'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(20) NOT NULL default ''",
            'default'                 => time()
        ),

        
        'result_total_correct' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['result_correct_answers'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'result_percentage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['test'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        
        'answers' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['answers'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        
		'published' => array
		(
			'exclude'                   => true,
			'label'                     => &$GLOBALS['TL_LANG']['tl_test_result']['published'],
			'inputType'                 => 'checkbox',
			'eval'                      => array('submitOnChange'=>true, 'doNotCopy'=>true),
			'sql'                       => "char(1) NOT NULL default ''"
		)		
    )
);

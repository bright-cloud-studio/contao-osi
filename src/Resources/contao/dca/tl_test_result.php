<?php

use Contao\DataContainer;
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
                'id' 	=> 	'primary'
            )
        )
    ),
 
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => DataContainer::MODE_UNSORTED,
            'flag'                    => DataContainer::SORT_ASC,
            'fields'                  => array('submission_date DESC'),
            'panelLayout'             => 'filter;search,limit,sort'
        ),
        'label' => array
        (
            'fields'                  => array('submission_date', 'test', 'member'),
			'format'                  => '%s | %s | %s',
			'label_callback' 		  => array('Bcs\Backend\TestResultBackend', 'generateLabel')
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
        'default'                       => '{test_legend},test,member;{submission_legend},submission_date,answers;{results_legend},result_total_correct,result_percentage;{member_group_legend}, member_groups;{publish_legend},published;'
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
            'inputType'               => 'select',
            'filter'                  => true,
            'search'                  => true,
            'flag'                    => DataContainer::SORT_ASC,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'chosen'=>true),
            'options_callback'	      => array('Bcs\Backend\TestResultBackend', 'getTests'),
            'foreignKey'              => 'tl_form.title',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'member' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['member'],
            'inputType'               => 'select',
            'filter'                  => true,
            'search'                  => true,
            'flag'                    => DataContainer::SORT_ASC,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'chosen'=>true),
            'options_callback'	      => array('Bcs\Backend\TestResultBackend', 'getMembers'),
            'foreignKey'              => 'tl_member.CONCAT(firstname," ",lastname)',
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),

        
        'submission_date' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['submission_date'],
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'mandatory'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''",
            'default'                 => time()
        ),
        'answers' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['answers'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr w100'),
            'sql'                     => 'blob NULL'
        ),

        'result_passed' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['result_passed'],
            'inputType'               => 'radio',
            'options'                 => array('yes' => 'Yes', 'no' => 'No'),
            'default'                 => 'no',
            'filter'                  => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(5) NOT NULL default 'no'"
            
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
            'label'                   => &$GLOBALS['TL_LANG']['tl_test_result']['result_percentage'],
            'inputType'               => 'text',
            'default'                 => '',
            'filter'                  => false,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),


        'member_groups' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_test_result']['member_groups'],
            'inputType'        => 'checkboxWizard',
            'eval'             => array('multiple'=> true, 'mandatory'=>false, 'tl_class'=>'long'),
            'flag'             => DataContainer::SORT_ASC,
            'filter'           => true,
            'foreignKey'       => 'tl_member_group.name',
            'options_callback' => array('Bcs\Backend\TestResultBackend', 'getMemberGroups'),
            'save_callback'    => array('Bcs\Backend\TestResultBackend', 'saveCallback'),
            'sql'              => "blob NULL"
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

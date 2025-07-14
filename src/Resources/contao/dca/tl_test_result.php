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
            'fields'                  => array('approved', 'country', 'state', 'first_name', 'last_name'),
            'format'                  => '<span class="%s"><span style="font-weight: bold;">Country: </span>%s <span style="font-weight: bold;">State: </span>%s <span style="font-weight: bold;">Name: </span>%s %s</span>'
        ),
        'global_operations' => array
        (
            'export' => array
            (
                'label'               => 'Export Listings CSV',
                'href'                => 'key=exportListings',
                'icon'                => 'system/modules/contao_directory/assets/icons/file-export-icon-16.png'
            ),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_listing']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
			
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_listing']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_listing']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_listing']['toggle'],
                'icon'                => 'visible.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('Bcs\Backend\ListingsBackend', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_listing']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
    // Palettes
    'palettes' => array
    (
        'default'                       => '{approved_legend},approved;{listing_legend},photo,first_name,last_name,phone,email_internal,email_public,website;{address_legend},address_1,address_2,city,state,zip,country;{service_area_legend}, service_area_worldwide, service_area_country, service_area_state, service_area_province;{details_legend},credentials,profession,remote_consultations,training_program,describe_practice;{specialties_legend},specialties_1,specialties_2,specialties_3,specialties_4;{practice_details_legend},language, practice_area;{provide_legend},provide_mms,provide_cas;{contact_legend},how_to_contact;{internal_legend},internal_notes,specific_services,date_created,date_approved;{publish_legend},published;'
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
        'alias' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['alias'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'search'                    => true,
            'eval'                      => array('unique'=>true, 'rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'sql'                       => "varchar(128) COLLATE utf8_bin NOT NULL default ''",
            'save_callback' => array
            (
                array('Bcs\Backend\ListingsBackend', 'generateAlias')
            )
        ),
        
        
        
        'approved' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['approved'],
            'inputType'                 => 'select',
            'options'                   => array('approved' => 'Approved', 'unapproved' => 'Unapproved'),
            'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                       => "varchar(32) NOT NULL default ''"
        ),
        
        
        
        
        'photo' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['photo'],
            'inputType'                 => 'fileTree',
            'default'                   => '',
            'search'                    => true,
            'eval'                      => [
                                            'mandatory' => true,
                                            'fieldType' => 'radio', 
                                            'filesOnly' => true
                                        ],
            'sql'                       => ['type' => 'binary', 'length' => 16, 'notnull' => false, 'fixed' => true]
		),
		'first_name' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['first_name'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'last_name' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['last_name'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'phone' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['phone'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'email_internal' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['email_internal'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'email_public' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['email_public'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'website' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['website'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        
        
        
        
        'address_1' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['address_1'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'address_2' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['address_2'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'city' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['city'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'state' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['state'],
			'inputType'                 => 'select',
			'default'                   => '',
			'options_callback'          => array('Bcs\Backend\ListingsBackend', 'optionsStates'),
			'eval'                      => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'zip' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['zip'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'country' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['country'],
			'inputType'                 => 'select',
			'default'                   => '',
			'options_callback'          => array('Bcs\Backend\ListingsBackend', 'optionsCountries'),
			'eval'                      => array('includeBlankOption'=>false, 'mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),



        // Service Area
        'service_area_worldwide' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['service_area_worldwide'],
            'inputType'                 => 'radio',
            'default'                   => 'no',
            'options'                   => array('yes' => 'Yes', 'no' => 'No'),
            'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                       => "varchar(32) NOT NULL default ''"
		),
        'service_area_country' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['service_area_country'],
			'inputType'                 => 'select',
			'default'                   => '',
			'options_callback'          => array('Bcs\Backend\ListingsBackend', 'optionsServiceAreaCountry'),
			'eval'                      => array('includeBlankOption'=>false, 'multiple'=>true, 'mandatory'=>false, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                       => "text default ''"
		),
        'service_area_state' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['service_area_state'],
			'inputType'                 => 'select',
			'default'                   => '',
			'options_callback'          => array('Bcs\Backend\ListingsBackend', 'optionsServiceAreaStates'),
			'eval'                      => array('includeBlankOption'=>false, 'multiple'=>true, 'mandatory'=>false, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                       => "text default ''"
		),
        'service_area_province' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['service_area_province'],
			'inputType'                 => 'select',
			'default'                   => '',
			'options_callback'          => array('Bcs\Backend\ListingsBackend', 'optionsServiceAreaProvinces'),
			'eval'                      => array('includeBlankOption'=>false, 'multiple'=>true, 'mandatory'=>false, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                       => "text default ''"
		),
        
        
        
        'credentials' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['credentials'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
        ),
        'profession' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['profession'],
			'inputType'                 => 'checkbox',
            'options_callback'          => array('Bcs\Backend\ListingsBackend', 'getProfessions'),
            'default'                   => '',
			'eval'                      => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'w50'),
			'sql'                       => ['type' => 'blob']
		),
        'remote_consultations' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['remote_consultation'],
            'inputType'                 => 'radio',
            'options'                   => array('yes' => 'Yes', 'no' => 'No'),
            'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                       => "varchar(32) NOT NULL default ''"
		),
        'training_program' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['training_program'],
			'inputType'                 => 'radio',
            'options'                   => array('yes' => 'Yes', 'no' => 'No'),
            'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                       => "varchar(32) NOT NULL default ''"
		),
        'describe_practice' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['describe_practice'],
            'inputType'                 => 'textarea',
            'default'                   => '',
            'search'                    => true,
            'eval'                      => array('mandatory' => true, 'tl_class'=>'clr'),
            'sql'                       => "varchar(1000) NOT NULL default ''"
        ),
        
        
        
        
        'specialties_1' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['specialties_1'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'specialties_2' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['specialties_2'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'specialties_3' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['specialties_3'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'specialties_4' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['specialties_4'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>false, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        
        
        
        'language' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['language'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        'practice_area' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['practice_area'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(255) NOT NULL default ''"
		),
        
        
        
        'provide_mms' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['provide_mms'],
			'inputType'                 => 'radio',
            'options'                   => array('yes' => 'Yes', 'no' => 'No'),
            'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                       => "varchar(32) NOT NULL default ''"
		),
        'provide_cas' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['provide_cas'],
			'inputType'                 => 'radio',
            'options'                   => array('yes' => 'Yes', 'no' => 'No'),
            'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                       => "varchar(32) NOT NULL default ''"
		),
        
        
        
        
        'how_to_contact' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_listing']['how_to_contact'],
			'inputType'               => 'checkbox',
            'options'                 => array('Office Address' => 'Office Address',  'Phone' => 'Phone',  'Email' => 'Email',  'Website' => 'Website'),
            'default'                 => '',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'w50'),
			'sql'                     => ['type' => 'blob']
        ),

        
        
        
        
        'internal_notes' => array
        (
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['internal_notes'],
            'inputType'                 => 'textarea',
            'default'                   => '',
            'search'                    => true,
            'eval'                      => array('tl_class'=>'long', 'rte' => 'tinyMCE'),
            'sql'                       => "varchar(1000) NOT NULL default ''"
        ),
        'specific_services' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['specific_services'],
			'inputType'                 => 'text',
			'default'                   => '',
			'search'                    => true,
			'eval'                      => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                       => "varchar(1000) NOT NULL default ''"
		),
        'date_created' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['date_created'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'search'                    => true,
            'eval'                      => array('unique'=>true, 'rgxp'=>'date', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('Bcs\Backend\ListingsBackend', 'getDateCreated')
            ),
            'sql'                       => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
        'date_approved' => array
		(
            'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['date_approved'],
            'exclude'                   => true,
            'inputType'                 => 'text',
            'search'                    => true,
            'eval'                      => array('unique'=>true, 'rgxp'=>'date', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('Bcs\Backend\ListingsBackend', 'getDateApproved')
            ),
            'sql'                       => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
        

        
        
        
		'published' => array
		(
			'exclude'                   => true,
			'label'                     => &$GLOBALS['TL_LANG']['tl_listing']['published'],
			'inputType'                 => 'checkbox',
			'eval'                      => array('submitOnChange'=>true, 'doNotCopy'=>true),
			'sql'                       => "char(1) NOT NULL default ''"
		)		
    )
);

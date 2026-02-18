<?php

use Contao\DataContainer;

/* Modify default Member Group sorting to be alphabetical */
$GLOBALS['TL_DCA']['tl_member']['fields']['groups']['flag'] = 2;

/* Modify default Member listing sorting */
$GLOBALS['TL_DCA']['tl_member']['list']['sorting']['fields'] = array('firstname ASC');

/* Add Member Groups to the list view */
$GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'][] = 'groups';
$GLOBALS['TL_DCA']['tl_member']['list']['label']['label_callback'] = array('Bcs\Backend\MemberBackend', 'generateLabel');
/* Export Operation */
$GLOBALS['TL_DCA']['tl_member']['list']['global_operations']['export_csv'] = array(
    'label'      => array('Export CSV', 'Export current list to CSV'),
    'href'       => 'key=export_csv',
    'class'      => 'header_icon',
    'attributes' => 'onclick="Backend.getScrollOffset()"',
    'button_callback'     => ['Bcs\Backend\MemberBackend', 'exportCSVButton']
);
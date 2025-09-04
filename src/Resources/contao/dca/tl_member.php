<?php

use Contao\DataContainer;

/* Modify default Member Group sorting to be alphabetical */
$GLOBALS['TL_DCA']['tl_member']['fields']['groups']['flag'] = DataContainer::SORT_INITIAL_LETTER_ASC;

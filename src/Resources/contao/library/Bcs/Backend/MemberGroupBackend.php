<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;

class MemberGroupBackend extends Backend
{
    // Get Members as options for a Select DCA field
    public function getTests(DataContainer $dc) {
        $tests = array();
        $this->import('Database');
        $result = $this->Database->prepare("SELECT * FROM tl_form WHERE disable=0 AND formType='test' ORDER BY tlte ASC")->execute();
        while($result->next())
        {
            $tests = $tests + array($result->id => $result->title);   
        }
        return $tests;
    }

}

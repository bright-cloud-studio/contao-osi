<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\FormModel;
use Contao\Image;
use Contao\Input;
use Contao\MemberModel;
use Contao\StringUtil;

class TestBackend extends Backend
{
    // Get Members as options for a Select DCA field
    public function getMemberGroups(DataContainer $dc) {
        $member_groups = array();
        $this->import('Database');
        $result = $this->Database->prepare("SELECT * FROM tl_member_group WHERE disable=0 ORDER BY name ASC")->execute();
        while($result->next())
        {
            $member_groups = $member_groups + array($result->id => $result->name);   
        }
        return $member_groups;
    }

}

<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;

use Contao\FormModel;

use Bcs\Model\TestResult;

class MemberBackend extends Backend
{

    public function saveCallback($varValue, DataContainer $dc) {

        echo "We hookin!";
        die();
    }

}

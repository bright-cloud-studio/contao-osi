<?php

namespace Bcs\Hooks;

use Bcs\Model\PoolModel;
use Bcs\Model\PoolVariant;
use Bcs\Model\QuoteRecord;

use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\PageModel;
use Contao\StringUtil;
use DateTime;

class FormHooks
{

    public function onSubmitTest($submittedData, $formData, $files, $labels, $form)
    {
        if($form['formType'] == 'test') {
            echo "Test Submitted";
            die();
        }
    }

}

<?php

namespace Bcs\Hooks;

use Bcs\Model\TestResult;

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

    public function onSubmitTest($submittedData, $formData, $files, $labels, $test)
    {
        if($test->formType == 'test') {
            echo "Test Submitted";
            
            // Get Member
            $member = FrontendUser::getInstance();

            // Create Test Result record
            $test_result = new TestResult();
            $test_result->tstamp = time();
            $test_result->submission_date = time();
            $test_result->answers = json_encode($submittedData);
            $test_result->member = $member->id;
            $test_result->test = $test->id;
            $test_result->save();
            
            die();
        }
    }

}

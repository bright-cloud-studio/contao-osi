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

class MemberGroupBackend extends Backend
{
    public function saveCallback($varValue, DataContainer $dc) {

        // Get clean integer Test IDs
        $data = StringUtil::deserialize($varValue, true);
        $group_ids = !empty($data)
            ? array_values(array_unique(array_filter(array_map('intval', array_values($data)))))
            : [];

        if (empty($group_ids)) {
            return serialize([]);
        }

        // Fetch all assigned tests in 1 single query using Contao findMultipleByIds (fixes N+1 issue)
        $sorted = [];
        $tests = FormModel::findMultipleByIds($group_ids, ['order' => 'title ASC']);
        if ($tests !== null) {
            foreach ($tests as $test) {
                $key = $test->title ?: ('Test ' . $test->id);
                // Prevent duplicate titles from overwriting each other
                if (isset($sorted[$key])) {
                    $key .= ' [' . $test->id . ']';
                }
                $sorted[$key] = (int)$test->id;
            }
        }

        // Only process test results for newly added tests to prevent timeouts from re-processing all tests
        $old_ids = [];
        if ($dc->activeRecord && isset($dc->activeRecord->test_assignment)) {
            $oldData = StringUtil::deserialize($dc->activeRecord->test_assignment, true);
            $old_ids = array_values(array_unique(array_filter(array_map('intval', array_values($oldData)))));
        } elseif ($dc->id) {
            $group = MemberGroupModel::findById($dc->id);
            if ($group !== null && $group->test_assignment) {
                $oldData = StringUtil::deserialize($group->test_assignment, true);
                $old_ids = array_values(array_unique(array_filter(array_map('intval', array_values($oldData)))));
            }
        }
        $added_tests = array_diff($group_ids, $old_ids);

        // Update TestResult in 1 single batch query using Contao Model (fixes N+1 issue)
        $groupId = (string)($dc->id ?: ($dc->activeRecord ? $dc->activeRecord->id : ''));
        if (!empty($added_tests) && $groupId !== '' && $groupId !== '0') {
            $inClause = implode(',', array_map('intval', $added_tests));
            $test_results = TestResult::findBy(["test IN ($inClause)"], []);

            if ($test_results !== null) {
                foreach ($test_results as $test_result) {
                    $mem_groups = StringUtil::deserialize($test_result->member_groups, true);
                    if (!in_array($groupId, $mem_groups, true)) {
                        $mem_groups[] = $groupId;
                        $test_result->member_groups = serialize($mem_groups);
                        $test_result->save();
                    }
                }
            }
        }

        // Send back a serialized array of our sorted version of selected values
        return serialize($sorted);
    }

    // Get Tests as options for DCA field using Contao FormModel
    public function getTests(DataContainer $dc) {
        $tests = [];
        $objForms = FormModel::findBy(['formType = ?'], ['test'], ['order' => 'title ASC']);

        if ($objForms !== null) {
            foreach ($objForms as $form) {
                $tests[$form->id] = $form->title . '[' . $form->id . ']';
            }
        }

        return $tests;
    }
}

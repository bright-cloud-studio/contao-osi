<?php

namespace Bcs\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;

use Contao\FormModel;

use Bcs\Model\TestResult;

class MemberBackend extends Backend
{

    public function saveCallback($varValue, DataContainer $dc) {

        echo "We hookin!";
        die();
    }

    public function exportCSVButton($href, $label, $title, $class, $attributes) {

        if (Input::get('key') === 'export_csv') {
            
            $session = System::getContainer()->get('request_stack')->getSession();
            $bag = $session->getBag('contao_backend');
            $sessionData = $bag->all();
            
            $search = $sessionData['search']['tl_member'] ?? null;
            $filter = $sessionData['filter']['tl_member'] ?? null;
            $sorting = $sessionData['sorting']['tl_member'] ?? null;
            
            $columns = [];
            $values = [];
            
            if (is_array($filter)) {
                foreach ($filter as $field => $value) {
                    if ($field === 'limit' || $value === '' || $value === null) continue;

                    if(is_array($value))
                    {
                         continue;
                    }
                    

                    if ($field === 'groups') {
                         $columns[] = "`$field` LIKE ?";
                         $values[] = '%"' . $value . '"%';
                         continue;
                    }

                    $columns[] = "`$field`=?";
                    $values[] = $value;
                }
            }
            
            if (is_array($search) && !empty($search['value'])) {
                $term = $search['value'];
                if(!empty($search['field'])) {
                     $columns[] = "`" . $search['field'] . "` LIKE ?";
                     $values[] = "%$term%";
                }
            }
            
            $order = '';
            if ($sorting && is_array($sorting) && isset($sorting['field'])) {
                $order = " ORDER BY " . $sorting['field'] . " " . ($sorting['order'] ?? 'ASC');
            } else {
                $order = " ORDER BY firstname ASC"; 
            }
            
            $where = !empty($columns) ? " WHERE " . implode(" AND ", $columns) : "";

            $this->import('Database');
            $sql = "SELECT * FROM tl_member" . $where . $order;

            if (!empty($values)) {
                $result = $this->Database->prepare($sql)->execute(...$values);
            } else {
                $result = $this->Database->prepare($sql)->execute();
            }

            ob_end_clean();
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="members_export_' . date('Y-m-d') . '.csv"');
            
            $handle = fopen('php://output', 'w+');
            
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'Firstname', 'Lastname', 'Username', 'Email', 'Company', 'Groups', 'Date Added']);
            
            try {
                while ($result->next()) {
                    $groups = StringUtil::deserialize($result->groups);
                    $groupNames = [];
                    if (is_array($groups) && !empty($groups)) {
                        $objGroups = MemberGroupModel::findMultipleByIds($groups);
                        if ($objGroups) {
                            while ($objGroups->next()) {
                                $groupNames[] = $objGroups->name;
                            }
                        }
                    }
                    
                    fputcsv($handle, [
                        $result->id,
                        $result->firstname,
                        $result->lastname,
                        $result->username,
                        $result->email,
                        $result->company,
                        implode(', ', $groupNames),
                        date('Y-m-d H:i', (int)$result->dateAdded) // Cast to int to be safe
                    ]);
                }
            } catch (\Exception $e) {
                fputcsv($handle, ['ERROR: ' . $e->getMessage()]);
            }
            
            fclose($handle);
            exit;
        }

        return '<a href="' . $this->addToUrl($href) . '" class="' . $class . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml('export.svg', $label, 'style="width:15px;height:15px;vertical-align:text-top;"') . ' ' . $label . '</a> ';
    }
    
    public function generateLabel($row, $label, DataContainer $dc, $args)
    {
        if (class_exists('tl_member')) {
            $tlMember = new \tl_member();
            $args = $tlMember->addIcon($row, $label, $dc, $args);
        }

        $fields = $GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'];
        $groupIndex = array_search('groups', $fields);

        if ($groupIndex !== false && isset($args[$groupIndex])) {
            $groupIds = StringUtil::deserialize($row['groups']);
            $groupNames = [];

            if (is_array($groupIds) && !empty($groupIds)) {
                $groups = MemberGroupModel::findMultipleByIds($groupIds);
                if ($groups) {
                    while ($groups->next()) {
                        $groupNames[] = $groups->name;
                    }
                }
            }

            $args[$groupIndex] = implode(', ', $groupNames);
        }

        return $args;
    }

}

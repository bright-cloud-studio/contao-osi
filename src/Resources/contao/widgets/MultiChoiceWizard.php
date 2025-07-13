<?php

namespace Bcs;

use Contao\Image;
use Contao\OptionWizard;
use Contao\StringUtil;
use Contao\System;
use Contao\Widget;

class MultiChoiceWizard extends OptionWizard
{
    public function generate()
	{
		$arrButtons = array('copy', 'delete', 'drag');

		// Make sure there is at least an empty array
		if (!\is_array($this->varValue) || empty($this->varValue[0]))
		{
			$this->varValue = array(array(''));
		}

		// Begin the table
		$return = '<table id="ctrl_' . $this->strId . '" class="tl_optionwizard">
  <thead>
    <tr>
      <th>' . $GLOBALS['TL_LANG']['MSC']['ow_value'] . '</th>
      <th>' . $GLOBALS['TL_LANG']['MSC']['ow_label'] . '</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody class="sortable">';

		// Add fields
		for ($i=0, $c=\count($this->varValue); $i<$c; $i++)
		{
			$return .= '
    <tr>
      <td><input type="text" name="' . $this->strId . '[' . $i . '][value]" id="' . $this->strId . '_value_' . $i . '" class="tl_text" value="' . self::specialcharsValue($this->varValue[$i]['value'] ?? '') . '"></td>
      <td><input type="text" name="' . $this->strId . '[' . $i . '][label]" id="' . $this->strId . '_label_' . $i . '" class="tl_text" value="' . self::specialcharsValue($this->varValue[$i]['label'] ?? '') . '"></td>
      <td><input type="checkbox" name="' . $this->strId . '[' . $i . '][default]" id="' . $this->strId . '_default_' . $i . '" class="fw_checkbox" value="1"' . (($this->varValue[$i]['default'] ?? null) ? ' checked="checked"' : '') . '> <label for="' . $this->strId . '_default_' . $i . '">' . $GLOBALS['TL_LANG']['MSC']['ow_default'] . '</label></td>
      <td><input type="checkbox" name="' . $this->strId . '[' . $i . '][group]" id="' . $this->strId . '_group_' . $i . '" class="fw_checkbox" value="1"' . (($this->varValue[$i]['group'] ?? null) ? ' checked="checked"' : '') . '> <label for="' . $this->strId . '_group_' . $i . '">' . $GLOBALS['TL_LANG']['MSC']['ow_group'] . '</label></td>';

			// Add row buttons
			$return .= '
      <td>';

			foreach ($arrButtons as $button)
			{
				if ($button == 'drag')
				{
					$return .= ' <button type="button" class="drag-handle" title="' . StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['move']) . '" aria-hidden="true">' . Image::getHtml('drag.svg') . '</button>';
				}
				else
				{
					$return .= ' <button type="button" data-command="' . $button . '" title="' . StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['ow_' . $button]) . '">' . Image::getHtml($button . '.svg') . '</button>';
				}
			}

			$return .= '</td>
    </tr>';
		}

		return $return . '
  </tbody>
  </table>
  <script>Backend.optionsWizard("ctrl_' . $this->strId . '")</script>';
	}
}

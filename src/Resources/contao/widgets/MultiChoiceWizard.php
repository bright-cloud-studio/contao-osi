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
      
      <td>
          <div>
            <input type="hidden" name="' . $this->strId . '[' . $i . '][image]" id="' . $this->strId . '_upload_' . $i . '" value="">
              <div class="selector_container">
                <ul id="sort_singleSRC" class=""></ul>
                <p><a href="/contao/picker?context=file&amp;extras%5BfieldType%5D=radio&amp;extras%5BfilesOnly%5D=1&amp;extras%5Bextensions%5D=jpg,jpeg,gif,png,tif,tiff,bmp,svg,svgz,webp,avif&amp;value=" class="tl_submit" id="' . $this->strId . '_ft_upload_' . $i . '">Image</a></p>
                
            
            <script>
              $("' . $this->strId . '_ft_upload_' . $i . '").addEvent("click", function(e) {
                e.preventDefault();
                Backend.openModalSelector({
                  "id": "tl_listing",
                  "title": "Source file",
                  "url": this.href + document.getElementById("' . $this->strId . '_upload_' . $i . '").value,
                  "callback": function(table, value) {
                    new Request.Contao({
                      evalScripts: false,
                      onSuccess: function(txt, json) {
                        // add value to hidden input so it gets into the db
                        document.getElementById("' . $this->strId . '_upload_' . $i . '").value = value;
                        // add value to label, wrapping in image tag
                        document.getElementById("' . $this->strId . '_label_' . $i . '").value = "[img]" + value + "[img]";
                      }
                    }).post();
                  }
                });
              });
            </script></div></div>
      </td>
      
      <td><input type="checkbox" name="' . $this->strId . '[' . $i . '][correct]" id="' . $this->strId . '_correct_' . $i . '" class="fw_checkbox" value="1"' . (($this->varValue[$i]['correct'] ?? null) ? ' checked="checked"' : '') . '> <label for="' . $this->strId . '_correct_' . $i . '">Correct</label></td>';

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

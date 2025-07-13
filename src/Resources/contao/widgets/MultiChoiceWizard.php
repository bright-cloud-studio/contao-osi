<?php

namespace Bcs;

use Contao\OptionWizard;
use Contao\System;
use Contao\Widget;

class MultiChoiceWizard extends Widget
{
    protected $blnSubmitInput = true;
	protected $strTemplate = 'be_widget';

	public function validate()
	{
		$mandatory = $this->mandatory;
		$options = $this->getPost($this->strName);

		// Check labels only (values can be empty)
		if (\is_array($options))
		{
			foreach ($options as $key=>$option)
			{
				// Unset empty rows
				if (trim($option['label']) === '')
				{
					unset($options[$key]);
					continue;
				}

				$options[$key]['label'] = trim($option['label']);
				$options[$key]['value'] = trim($option['value']);

				if ($options[$key]['label'])
				{
					$this->mandatory = false;
				}

				// Strip double quotes (see #6919)
				if ($options[$key]['value'])
				{
					$options[$key]['value'] = str_replace('"', '', $options[$key]['value']);
				}
			}
		}

		$options = array_values($options);
		$varInput = $this->validator($options);

		if (!$this->hasErrors())
		{
			$this->varValue = $varInput;
		}

		// Reset the property
		if ($mandatory)
		{
			$this->mandatory = true;
		}
	}
	
	public function generate()
	{
		// Make sure there is at least an empty array
		if (!\is_array($this->varValue) || empty($this->varValue[0]))
		{
			$this->varValue = array(array(''));
		}

		return System::getContainer()->get('twig')->render('@BcsOSI/multi_choice_wizard.html.twig', array(
			'id' => $this->strId,
			'rows' => $this->varValue,
		));
	}
  
}

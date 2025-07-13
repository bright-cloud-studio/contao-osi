<?php


namespace Bcs;

use Contao\OptionWizard;
use Contao\System;

/**
 * Provide methods to handle form field options.
 */
class MultiChoiceWizard extends OptionWizard
{
	
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

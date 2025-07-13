<?php

Use Contao\OptionWizard;

namespace Bcs;

class MultiChoiceWizard extends \OptionWizard
{

	protected $blnSubmitInput = true;

	protected $strTemplate = 'be_widget';

	public function generate()
	{
    
		// Make sure there is at least an empty array
		if (!\is_array($this->varValue) || empty($this->varValue[0]))
		{
			$this->varValue = array(array(''));
		}

		return System::getContainer()->get('twig')->render('@Contao/backend/widget/multi_choice_wizard.html.twig', array(
			'id' => $this->strId,
			'rows' => $this->varValue,
		));
    
	}
  
}

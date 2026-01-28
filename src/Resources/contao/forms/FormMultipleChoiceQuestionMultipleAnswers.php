<?php

namespace Bcs\OSIBundle;

use Contao\FormCheckbox;
use Contao\Config;
use Contao\FormModel;

class FormMultipleChoiceQuestionMultipleAnswers extends FormCheckbox
{
    protected $strTemplate = 'form_multi_choice_question_multiple_answers';
    protected $strPrefix = 'widget widget-checkbox widget-multi-choice-question-multiple-answers';
}

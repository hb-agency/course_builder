<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Winans Creative 2011, Helmut Schottmüller 2009
 * @author     Blair Winans <blair@winanscreative.com>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Adam Fisher <adam@winanscreative.com>
 * @author     Includes code from survey_ce module from Helmut Schottmüller <typolight@aurealis.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
 
 
class FormLessonSegmentPlainText extends FormLessonSegment
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_lesson_plaintext';
	protected $strOtherTitle = "";
	protected $blnOther = false;
	protected $strStyle = false;
	protected $arrChoices = array();

	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'lessondata':
				parent::__set($strKey, $varValue);
				$this->strClass = "pt";
				$this->strOtherTitle = $varValue['othertitle'];
				$this->blnOther = ($varValue['addother']) ? true : false;
				$this->strStyle = $varValue['pt_style'];
				$this->arrChoices = deserialize($varValue["choices"]);
				if (!is_array($this->arrChoices)) $this->arrChoices = array();
				$this->segmenttype = $varValue['plaintext_subtype'];
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	/**
	 * Return a parameter
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'empty':
				$found = (is_array($this->varValue["value"])) ? (count($this->varValue["value"]) > 0) : false;
				if (!$found) $found = (!is_array($this->varValue["value"])) ? (strlen($this->varValue["value"]) > 0) : false;
				if (!$found) $found = strlen($this->varValue["other"]) > 0;
				return (!$found) ? true : false;
				break;
			default:
				return parent::__get($strKey);
				break;
		}
	}
	
	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$submit = $this->getPost("segment");
		$submit_other = $this->getPost("other_segment");
		$value = array();
		$value["value"] = $submit[$this->id];
		$value["other"] = $submit_other[$this->id];
		$varInput = $this->validator($value);
		$this->value = $varInput;
	}

	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		return $varInput;
		
		if (((strcmp($this->questiontype, "mc_singleresponse") == 0) || (strcmp($this->questiontype, "mc_dichotomous") == 0)) && $this->mandatory && !strlen($varInput["value"]))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'], $this->title));
			return $varInput;
		}
		else if ((strcmp($this->questiontype, "mc_multipleresponse") == 0) && $this->mandatory && (!is_array($varInput["value"]) || count($varInput["value"]) == 0))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_mr'], $this->title));
			return $varInput;
		}
		
		if ((strcmp($this->questiontype, "mc_singleresponse") == 0))
		{
			if (($varInput["value"] == count($this->arrChoices) + 1) && (strlen($varInput["other"]) == 0))
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['missing_other_value'], $this->title));
				return $varInput;
			}
			if ($varInput["value"] == 0 && $this->mandatory)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'], $this->title));
				return $varInput;
			}
		}
		else if ((strcmp($this->questiontype, "mc_dichotomous") == 0))
		{
			if ($varInput["value"] == 0)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'], $this->title));
				return $varInput;
			}
		}
		else if ((strcmp($this->questiontype, "mc_multipleresponse") == 0))
		{
			if (is_array($varInput["value"]))
			{
				if ((in_array(count($this->arrChoices) + 1, $varInput["value"])) && (strlen($varInput["other"]) == 0))
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['missing_other_value'], $this->title));
					return $varInput;
				}
			}
		}
		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->loadLanguageFile('tl_lesson_segment');
		$template = new FrontendTemplate('lesson_segment_plaintext');
		$template->ctrl_name = specialchars($this->strName);
		$template->ctrl_id = specialchars($this->strId);
		$template->ctrl_class = (strlen($this->strClass) ? ' ' . $this->strClass : '');
		//$template->singleResponse = strcmp($this->questiontype, "mc_singleresponse") == 0;
		//$template->multipleResponse = strcmp($this->questiontype, "mc_multipleresponse") == 0;
		//$template->dichotomous = strcmp($this->questiontype, "mc_dichotomous") == 0;
		$template->styleHorizontal = strcmp($this->strStyle, "horizontal") == 0;
		$template->styleVertical = strcmp($this->strStyle, "vertical") == 0;
		$template->styleSelect = strcmp($this->strStyle, "select") == 0;
		$template->values = $this->varValue;
		//$template->choices = $this->arrChoices;
		$template->blnOther = $this->blnOther;
		//$template->lngYes = $GLOBALS['TL_LANG']['tl_quiz_question']['yes'];
		//$template->lngNo = $GLOBALS['TL_LANG']['tl_quiz_question']['no'];
		$template->otherTitle = specialchars($this->strOtherTitle);
		$widget = $template->parse();
		$widget .= $this->addSubmit();
		return $widget;
	}

	/**
	 * Create a string representation of the question result
	 * @return string
	 */
	public function getResultStringRepresentation()
	{
		$result = "";
		$choices = array();
		$counter = 1;
		foreach ($this->arrChoices as $choice)
		{
			if ($this->varValue["value"] == $counter)
			{
				array_push($choices, $choice);
			}
			$counter++;
		}
		if ($this->blnOther)
		{
			if ($this->varValue["value"] == $counter)
			{
				array_push($choices, $this->varValue["other"]);
			}
		}
		if (count($choices))
		{
			$result .= join($choices, ", ") . "\n";
		}
		return $result;
	}
}

?>
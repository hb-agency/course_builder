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
 
class FormQuizQuestion extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $questionNumber = 0;
	protected $pageQuestionNumber = 0;
	protected $pageNumber = 0;
	protected $absoluteNumber = 0;
	protected $question = "";
	protected $title = "";
	protected $help = "";
	protected $questiontype = "";
	protected $hidetitle;

	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'quizdata':
				$this->arrConfiguration['mandatory'] = $varValue['obligatory'] ? true : false;
				$this->strId = $varValue['id'];
				$this->strName = "question[" . $varValue['id'] . "]";
				$this->question = $varValue['question'];
				$this->title = $varValue['title'];
				$this->help = $varValue['help'];
				$this->hidetitle = $varValue['hidetitle'];
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;
				
			case 'pageNumber':
				$this->pageNumber = $varValue;
				break;

			case 'absoluteNumber':
				$this->absoluteNumber = $varValue;
				break;

			case 'questionNumber':
				$this->questionNumber = $varValue;
				break;

			case 'pageQuestionNumber':
				$this->pageQuestionNumber = $varValue;
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
			case 'question':
				return $this->question;
				break;
			case 'title':
				return $this->setQuestionNumber($this->title);
				break;
			case 'questionNumber':
				return $this->questionNumber;
				break;
			case 'pageQuestionNumber':
				return $this->pageQuestionNumber;
				break;
			case 'pageNumber':
				return $this->pageNumber;
				break;
			case 'absoluteNumber':
				return $this->absoluteNumber;
				break;
			case 'showTitle':
				return ($this->hidetitle == false);
				break;
			case 'help':
				return $this->help;
				break;
			case 'empty':
				return (!is_array($this->varValue) && !strlen($this->varValue)) ? true : false;
				break;
		}
		return parent::__get($strKey);
	}

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// overwrite in parent classes
	}

	/**
	 * Create a string representation of the question result
	 * @return string
	 */
	public function getResultStringRepresentation()
	{
		$result = "";
		if (!is_array($this->varValue) && strlen($this->varValue))
		{
			$result .= $this->varValue . "\n";
		}
		return $result;
	}
	
	/**
	 * Set the question number if appropriate insert tag is found
	 * @param string
	 * @return string
	 */
	public function setQuestionNumber( $strBuffer )
	{
		$tags = preg_split('/\{\{([^\}]+)\}\}/', $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE);
				
		if(count($tags))
		{
			$strBuffer = '';
			
			for($_rit=0; $_rit<count($tags); $_rit=$_rit+2)
			{
				$strBuffer .= $tags[$_rit];
				$strTag = $tags[$_rit+1];
				
				// Skip empty tags
				if (!strlen($strTag))
				{
					continue;
				}
				$arrTag = trimsplit('::', $strTag);
				
				if (count($arrTag) == 2 && $arrTag[0] == 'question' && $arrTag[1] == 'number')
				{
					$strBuffer .= $this->absoluteNumber;
				
				}
			}
			
		}
		return $strBuffer;
	}
	
}

?>
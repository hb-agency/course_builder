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
 
class FormLessonSegment extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = false;

	/**
	 * Template
	 * @var string
	 */
	protected $segmentNumber = 0;
	protected $pageSegmentNumber = 0;
	protected $pageNumber = 0;
	protected $absoluteNumber = 0;
	protected $segment = "";
	protected $title = "";
	protected $help = "";
	protected $segmenttype = "";
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
			case 'lessondata':
				$this->arrConfiguration['mandatory'] = $varValue['obligatory'] ? true : false;
				$this->strId = $varValue['id'];
				$this->strName = "segment[" . $varValue['id'] . "]";
				$this->segment = $varValue['segment'];
				$this->title = $varValue['title'];
				$this->help = $varValue['help'];
				$this->hidetitle = $varValue['hidetitle'];
				break;

			/*case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;*/
				
			case 'pageNumber':
				$this->pageNumber = $varValue;
				break;

			case 'absoluteNumber':
				$this->absoluteNumber = $varValue;
				break;

			case 'segmentNumber':
				$this->segmentNumber = $varValue;
				break;

			case 'pageSegmentNumber':
				$this->pageSegmentNumber = $varValue;
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
			case 'segment':
				return $this->segment;
				break;
			case 'title':
				return $this->title;
				break;
			case 'segmentNumber':
				return $this->segmentNumber;
				break;
			case 'pageSegmentNumber':
				return $this->pageSegmentNumber;
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
	 * Create a string representation of the segment result
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
}

?>
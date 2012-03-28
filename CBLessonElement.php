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
 * @copyright  Winans Creative 2011, Helmut SchottmÙller 2009
 * @author     Blair Winans <blair@winanscreative.com>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Adam Fisher <adam@winanscreative.com>
 * @author     Includes code from survey_ce module from Helmut SchottmÙller <typolight@aurealis.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class CBLessonElement extends CBCourseElement
{

	/**
	 * Construct the object
	 */
	public function __construct($arrData, $blnReview=false)
	{
		parent::__construct($arrData);
		
		$this->arrData = $arrData;
		
		//Build the Lesson's Page Object based on the User's current active page - only return the current page data
		$arrLessonPage = $this->Database->prepare("SELECT * FROM tl_cb_lessonpage WHERE pid=? ORDER by sorting ASC")->execute($this->id)->fetchAllAssoc();

		if(count($arrLessonPage))
		{
			$intCurrPage = $this->CourseBuilder->getCurrentPage('Lesson');
			$intTotalPages = count($arrLessonPage);
			$arrPageData = ($intCurrPage > 0 ? $arrLessonPage[$intCurrPage-1] : array());
			$arrPageData['total_pages'] = $intTotalPages;
			$this->page = new CBLessonPage($arrPageData, $this, $intCurrPage, $blnReview);
		}
	
	}
	
	/**
	 * Set an object property
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}
	
	/**
	 * Return an object property
	 *
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}

	
	/**
	 * Generate a lesson template
	 */
	public function generate($strTemplate, $objModule)
	{
		$strTemplate = $strTemplate ? 'cb_lesson_default' : $strTemplate;
		
		$objTemplate = new FrontendTemplate($strTemplate);
		
		//Get Page Output
		if($this->page)
		{
			$objTemplate->page = $this->page->generate($objModule);
		}
		
		// HOOK for altering course data before output
		if (isset($GLOBALS['TL_HOOKS']['cb_generateLesson']) && is_array($GLOBALS['TL_HOOKS']['cb_generateLesson']))
		{
			foreach ($GLOBALS['TL_HOOKS']['cb_generateLesson'] as $callback)
			{
				$this->import($callback[0]);
				$objTemplate = $this->$callback[0]->$callback[1]($objTemplate, $this);
			}
		}

		return $objTemplate->parse();
	}



}
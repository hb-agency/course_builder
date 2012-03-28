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


class CBLessonPage extends CBPage
{
	
	/**
	 * Page template
	 */
	protected $strTemplate = 'cb_lessonpage_default';
	
	/**
	 * Segment Sequence
	 * @var array
	 */
	protected $arrSegmentSequence;
	
	/**
	 * Lesson Object
	 * @var array
	 */
	protected $objLesson;
	
	/**
	 *Review status
	 * @var bool
	 */
	protected $blnReview;
	
	
	/**
	 * Construct the object
	 */
	public function __construct($arrData, $objLesson, $intCurrpage, $blnReview=false)
	{
		parent::__construct($arrData, $objLesson, $intCurrpage);
		
		$this->blnReview = $blnReview;
		$this->objLesson = $objLesson;
			
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
	 * Generate the page
	 */
	public function generate($objModule)
	{		
		$strTemplate = $this->page_template ? $this->page_template : $this->strTemplate;
		$objTemplate = new FrontendTemplate($strTemplate);
		
		$objTemplate->segments = $this->buildSegments();
		$objTemplate->buttons = $this->CourseBuilder->getButtons($this);
		
		//Standard config
		$objTemplate->enctype ='application/x-www-form-urlencoded';
		$objTemplate->formId = $this->CourseBuilder->getFormID();
		$objTemplate->pageID = $this->intCurrpage;
		$objTemplate->sectionID = 'lesson|' . $this->objLesson->id;
		$objTemplate->parentID = $this->CourseBuilder->Lesson->pid;
		$objTemplate->action = ampersand($this->Environment->request, true);
		$objTemplate->formSubmit = $this->CourseBuilder->getFormID();
		
		return $objTemplate->parse();
	
	}
	
	/**
	 * Create an array of widgets containing the segments on a given page
	 *
	 * @param array
	 * @param boolean
	 */
	protected function buildSegments()
	{	
		$this->CourseBuilder->Lesson->lastpage = $this->intCurrpage;
		if($this->intCurrpage > $this->CourseBuilder->Lesson->maxpage)
		{
			$this->CourseBuilder->Lesson->maxpage = $this->intCurrpage;
		}
		
		//Checks for Introduction/Success/Failure
		if($this->intCurrpage==0 && $this->Input->post('FORM_SUBMIT') != $this->CourseBuilder->getFormID())
		{
			return $this->objLesson->introduction;
		}
		elseif($this->intCurrpage > $this->total_pages)
		{					
			$this->CourseBuilder->Lesson->status = 'complete';
			$this->CourseBuilder->Lesson->lastitem = 0;
			
			$this->strStatus = 'success'; //Will always be success on a lesson
			
			//Check for success
			return $this->objLesson->{$this->strStatus};
		}

		$contentElements = '';
		$arrSegments = array();
		
		// Get all visible content elements
		$objCte = $this->Database->prepare("SELECT id FROM tl_cb_lessonsegment WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . " ORDER BY sorting")
								 ->execute($this->id);

		while ($objCte->next())
		{
			$contentElements .= $this->CourseBuilder->getCBContentElement($objCte->id, 'tl_cb_lessonsegment');
			$arrSegments[] = $objCte->row();
		}

		if ($this->Input->post('FORM_SUBMIT') == $this->CourseBuilder->getFormID() && !$this->doNotSubmit && $this->Input->post('SECTION_ID')=='lesson|' . $this->objLesson->id)
		{
			// HOOK: pass validated segments to callback functions
			if (isset($GLOBALS['TL_HOOKS']['lessonSegmentsValidated']) && is_array($GLOBALS['TL_HOOKS']['lessonSegmentsValidated']))
			{
				foreach ($GLOBALS['TL_HOOKS']['lessonSegmentsValidated'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($arrSegments, $this);
				}
			}
			
			if($this->Input->post('prev') && $this->Input->post('prev') == $GLOBALS['TL_LANG']['CB']['buttonprevpage'])
			{
				$this->CourseBuilder->moveBackward('Lesson', $this->total_pages);
			}
			else
			{
				$this->CourseBuilder->moveForward('Lesson', $this->total_pages);
			}
			
		}
		else
		{
			// HOOK: pass loaded segments to callback functions
			if (isset($GLOBALS['TL_HOOKS']['lessonSegmentsLoaded']) && is_array($GLOBALS['TL_HOOKS']['lessonSegmentsLoaded']))
			{
				foreach ($GLOBALS['TL_HOOKS']['lessonSegmentsLoaded'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($arrSegments, $this);
				}
			}
			
		}

		return $contentElements;
	}


	/**
	 * Return the total position of a segment in the sequence of the lesson
	 *
	 * @param array
	 * @param boolean
	 */
	protected function getSegmentSequence($intSegmentID, $intLessonID)
	{
		if ($intSegmentID > 0 && $intLessonID > 0)
		{
			if (!count($this->arrSegmentSequence))
			{
				$this->arrSegmentSequence = $this->Database->prepare("SELECT tl_cb_lessonsegment.id FROM tl_cb_lessonsegment, tl_cb_lessonpage WHERE tl_cb_lessonsegment.pid = tl_cb_lessonpage.id AND tl_cb_lessonpage.pid =? ORDER BY tl_cb_lessonpage.sorting, tl_cb_lessonsegment.sorting")
					->executeUncached($intLessonID)
					->fetchEach('id');
			}
			
			return array_search($intSegmentID, $this->arrSegmentSequence) + 1;
		}
		else
		{
			return 0;
		}
	}


}
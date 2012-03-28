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

class ModuleCBCourseNavigation extends ModuleCB
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_courseprogressbar_default';
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### COURSE BUILDER: COURSE NAVIGATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = $this->Environment->script.'?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		$intDataId = 0;
		
		
		// Get the template
		$this->Template = new FrontendTemplate(strlen($this->cb_coursenavigation_layout) ? $this->cb_coursenavigation_layout : 'mod_coursenavigation_default');
		
		
		// Get the course object if we don't already ahve it	
		if (!$this->CourseBuilder->Course )
		{
			if(!$this->Input->get('course'))	
				return 'nocourse'; //@todo create error msg
				
			//Run a check to make sure this course is available
			$arrIDs = array_keys($this->getAvailableCourses());

			//Generate Course Object and CourseElement Data
			$arrCourse = CBFrontend::getCoursebyAlias($this->Input->get('course'), $arrIDs);
	
			if(!count($arrCourse))
				return 'noids'; //@todo create error msg
			
			$this->CourseBuilder->Course = new CBCourse($arrCourse);
		}
		
		//Find the last known position for the logged in user for the assigned course
		if($this->User->id && $this->CourseBuilder->Course->id)
		{
			$objCurrentData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid=? AND courseid=? AND status IN ('in_progress', 'ready')")
											 ->execute($this->User->id, $this->CourseBuilder->Course->id);
			
										  
			$objSuccessData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid=? AND courseid=? AND status='complete'")
										  ->execute($this->User->id, $this->CourseBuilder->Course->id);						  
			
			if( $objSuccessData->numRows )
			{
				$intDataId = $objSuccessData->id;
			}
			else
			{
				$intDataId = $objCurrentData->id;
			}
		}
				
		// Build the data array for the course navigation
		$blnCurrentUsed = false;
		$arrCourseElements = array();
		$arrCourseData = $this->CourseBuilder->Course->getElements();

		if (is_array($arrCourseData))
		{
			foreach ($arrCourseData as $value)
			{
				$strClass = '';
				$arrElement = explode('|',$value);
				
				if (is_array($GLOBALS['CB_ELEMENT'][$arrElement[0]]))
				{
					$elConfig = $GLOBALS['CB_ELEMENT'][$arrElement[0]];
					$strTable = $elConfig['data']['table'];
					$strColumn = $elConfig['data']['column'];
					
					// Get data related to the course element's data
					$objData = $this->Database->prepare("SELECT * FROM $strTable WHERE pid=$intDataId AND $strColumn=? AND status<>'skipped'")->limit(1)->executeUncached($arrElement[1]);
					
					// Get data related to the course element itself
					$objElementData = $this->Database->prepare("SELECT * FROM {$elConfig['table']} WHERE id=?")->limit(1)->execute($arrElement[1]);
					
					$blnCanReview = $arrElement[0] == 'quiz' ? ($objElementData->reviewable && $objElementData->canretake && strlen($objData->status) ? true : false) : ($objElementData->reviewable && strlen($objData->status) ? true : false);
					
					if (!$blnCurrentUsed && (!$objData->numRows || $objData->status != 'complete'))
					{
						$strClass = 'current';
						$blnCurrentUsed = true;
					}
															
					$arrCourseElements[$value] = array
					(
						'name'		=> specialchars($objElementData->name),
						'href'		=> $blnCanReview ? $this->CourseBuilder->addToUrl(htmlentities('&section=' . $value)) : '',
						'class'		=> strlen($strClass) ? $strClass : ($blnCanReview ? 'enabled' : 'disabled'),
					);					
				}
			}		
		}
							
		$this->Template->sections = $arrCourseElements;	
	}
	
}

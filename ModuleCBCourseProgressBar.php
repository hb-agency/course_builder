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

class ModuleCBCourseProgressBar extends ModuleCB
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
			$objTemplate->wildcard = '### COURSE BUILDER: COURSE PROGRESS BAR ###';
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
		if ($this->cb_courseprogressbar_layout)
		{
			$this->Template = new FrontendTemplate($this->cb_courseprogressbar_layout);
		}
		
		if (!$this->CourseBuilder->Course )
		{
			if(!$this->Input->get('course'))	
				return ''; //@todo create error msg
				
			//Generate Course Object and CourseElement Data
			$arrIDs = array_keys($this->getAvailableCourses());
			$arrCourse = CBFrontend::getCoursebyAlias($this->Input->get('course'), $arrIDs);

			if(!count($arrCourse))
				return ''; //@todo create error msg
			
			$this->CourseBuilder->Course = new CBCourse($arrCourse);
		}
		
		$strCurrType = $this->CourseBuilder->Course->currType;
		$strCurrElement = $this->CourseBuilder->Course->currEl;
		
		if( !$strCurrType )
			return ''; //@todo create error msg
			
		$strCurrObj = ucwords($strCurrType);
		
		$intCurrPage = (int)$this->CourseBuilder->{$strCurrObj}->lastpage;
		$intTotalPages = (int)$this->CourseBuilder->{$strCurrObj}->totalPages;
		$arrPages = $this->CourseBuilder->{$strCurrObj}->pageData;
								
		$arrProgressBarData = array();
		
		for ($i = 0; $i < $intTotalPages; $i++)
		{
			$arrProgressBarData[] = array
			(
				'class'		=> (($i < $intCurrPage - 1) ? 'completed' : (($i == $intCurrPage - 1) ? 'current' : 'incomplete')) . ' row_' . $i . ($i%2 ? ' even' : ' odd') . ($i==0 ? ' row_first' : (($i == $intTotalPages - 1) ? ' row_last' : '')),
				'title'		=> $arrPages[$i]['title'],
				'href'		=> $this->getPageHref($i, $arrPages[$i], $strCurrObj, $strCurrType, $strCurrElement)
			);
		}
		
		$this->Template->progressunits = $arrProgressBarData;
		
	}
	
	/**
	 * Generate a link directly to an already visited page
	 * @param array
	 * @return string
	 */
	protected function getPageHref( $intPage, $arrPage, $strCurrObj, $strCurrType, $strCurrElement )
	{
		$strHref = '';
		
		$intActual = (int)$intPage+1;
				
		//First check that the page has been previously visited, so people cannot skip ahead

		if($intActual > $this->CourseBuilder->{$strCurrObj}->maxpage)
			return '';
						
		return $this->CourseBuilder->addToUrl('page='. $intActual . '&section=' . $strCurrType . '|' . $strCurrElement);
	}
	
	
}

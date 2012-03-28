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

class ModuleCBQuizReader extends ModuleCB
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_quizreader_default';
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### COURSE BUILDER: QUIZ READER ###';
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
		if($this->cb_quizreader_layout)
		{
			$this->Template = new FrontendTemplate($this->cb_quizreader_layout);
		}
		
		if(!$this->Input->get('course'))	
			return ''; //@todo create error msg
			
		//Generate Course Object and CourseElement Data
		$arrCourse = CBFrontend::getCoursebyAlias($this->Input->get('course'));

		if(!count($arrCourse))
			return ''; //@todo create error msg
					
		$arrQuiz['quiz'] = CBFrontend::getCourseElementbyAlias($this->Input->get('quiz'), 'quiz');
		
		$objCourse = new CBCourse($arrCourse, $arrQuiz);
		
		$this->Template->quiz = $objCourse->generate($this->cb_course_template, $this);
			
	}



}
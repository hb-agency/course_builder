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


abstract class CBCourseElement extends Frontend
{

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();
	
	/**
	 * Cache properties
	 */
	protected $arrCache = array();
	
	/**
	 * for quizzes - don't submit if certain validation(s) fail
	 * @var boolean
	 */
	protected $doNotSubmit = false;
	
	/**
	 * CourseBuilder object
	 * @var object
	 */
	protected $CourseBuilder;
	
	/**
	 * Construct the object
	 */
	public function __construct($arrData)
	{
		parent::__construct();
		$this->import('Database');
		$this->import('CourseBuilder');
		
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
		}
		
		$this->arrData = $arrData;
	
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
	 * Must be implemented by child class
	 */
	abstract public function generate($strTemplate, $objModule);
	

}
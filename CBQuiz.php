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


class CBQuiz extends CBCourseElement
{

	/**
	 * Table
	 * @var string
	 */
	protected $strTable='tl_cb_quizdata';
	
	/**
	 * Name of the child table
	 * @var string
	 */
	protected $ctable='tl_cb_quizdata_items';
	
	/**
	 * Current quiz ID
	 * @var integer
	 */
	protected $intId;

	/**
	 * CourseBuilder object
	 * @var object
	 */
	protected $CourseBuilder;
	
	/**
	 * Cache all questions for speed improvements
	 * @var array
	 */
	protected $arrSegments;
	
	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}
	
	
	public function __construct()
	{
		parent::__construct();

		// Do not use __destruct, because Database object might be destructed first (see http://dev.contao.org/issues/2236)
		register_shutdown_function(array($this, 'saveDatabase'));
	}
	
	/**
	 * Shutdown function to save data if modified
	 */
	public function saveDatabase()
	{
		$this->save();
	}


}
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


class CBLessonData extends CBCourseData
{

	/**
	 * Table
	 * @var string
	 */
	protected $strTable='tl_cb_lessondata';
	
	/**
	 * Name of the child table
	 * @var string
	 */
	protected $ctable='tl_cb_lessondata_items';
	
	/**
	 * Current lesson ID
	 * @var integer
	 */
	protected $intId;

	/**
	 * CourseBuilder object
	 * @var object
	 */
	protected $CourseBuilder;
	
	/**
	 * Cache all segments for speed improvements
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

		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
		}		
		
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
	
	/**
	 * Return data.
	 *
	 * @access public
	 * @param string $strKey
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch( $strKey )
		{
			case 'segments':
			case 'segment':
				if (!isset($this->arrCache[$strKey]))
				{
					$arrSegments = array();
					$objData = $this->Database->execute("SELECT * FROM {$this->ctable} WHERE pid={$this->id}");
					while( $objData->next() )
					{
						$arrSegments[$objData->segment] = deserialize($objData->response);
					}
					$this->arrCache[$strKey] = $arrSegments;
				}
				return $this->arrCache[$strKey];
				break;
				
			case 'totalPages':
			case 'pageData':
				if (!isset($this->arrCache[$strKey]))
				{
					$arrPages = $this->Database->prepare("SELECT * FROM tl_cb_lessonpage WHERE pid=? ORDER by sorting ASC")
													->execute($this->lessonid)
													->fetchAllAssoc();
													
					$this->arrCache['totalPages'] = count($arrPages);
					$this->arrCache['pageData'] = $arrPages;
				}
				return $this->arrCache[$strKey];
				break;
			

			default:
				return parent::__get($strKey);
		}
	}
	
	/**
	 * Set data.
	 *
	 * @access public
	 * @param string $strKey
	 * @param string $varValue
	 * @return void
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrCache = array();

		if ($strKey == 'modified')
		{
			$this->blnModified = (bool)$varValue;
			$this->arrCache = array();
		}

		// If there is a database field for that key, we store it there
		if (array_key_exists($strKey, $this->arrData) || $this->Database->fieldExists($strKey, $this->strTable))
		{
			$this->arrData[$strKey] = $varValue;
			$this->arrCache = array();
			$this->blnModified = true;
		}

		// We dont want $this->import() objects to be in arrSettings
		elseif (is_object($varValue))
		{
			$this->$strKey = $varValue;
		}

		// Everything else goes into arrSettings and is serialized
		else
		{
			if (is_null($varValue))
			{
				unset($this->arrSettings[$strKey]);
			}
			else
			{
				$this->arrSettings[$strKey] = $varValue;
			}

			$this->arrCache = array();
			$this->blnModified = true;
		}
	}
	

	/**
	 * Load current data or create new
	 */
	public function initializeData($intCourse, $intLesson, $blnReview=false)
	{
		$time = time();
		$this->strHash = $this->Input->cookie($this->strCookie);
		
		// blnReview will change the query
		$strQuery = $this->blnLocked ? "AND status ='complete'" : ($blnReview ? "AND status ='review'" : "AND status IN ('in_progress', 'ready')");

		//  Check to see if the user is logged in.
		if (!FE_USER_LOGGED_IN || !$this->User->id)
		{
			if (!strlen($this->strHash))
			{
				$this->strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . $intConfig . $this->strCookie);
				$this->setCookie($this->strCookie, $this->strHash, $time+$GLOBALS['TL_CONFIG']['cb_dataTimeout'], $GLOBALS['TL_CONFIG']['websitePath']);
			}

			$objCourseData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE session='{$this->strHash}' AND courseid=$intCourse $strQuery ORDER BY attempt DESC")->limit(1)->executeUncached();
		}
		else
		{
			$this->strHash = '';
			$objCourseData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid={$this->User->id} AND courseid=$intCourse $strQuery ORDER BY attempt DESC")->limit(1)->executeUncached();
		}
		
		if( !$objCourseData->numRows )
		{
			//Need to generate a new record for this course
			$arrSet = array(
				'pid'		=> FE_USER_LOGGED_IN ? $this->User->id : 0,
				'session'	=> $this->strHash,
				'tstamp'	=> time(),
				'courseid'	=> $intCourse,
				'status' 	=> 'in_progress',
				'settings'	=> array()
			);
			
			$intCourseDataId = $this->Database->prepare("INSERT INTO tl_cb_coursedata %s")->set($arrSet)->execute()->insertId;
				
		}
		else
		{
			$intCourseDataId = $objCourseData->id;
		}
		
		$strDataQuery = $this->blnLocked ? "AND status ='complete'" : "AND status<>'skipped'";
		
		//  Check to see if data exists for the current course data attempt
		$objData = $this->Database->prepare("SELECT * FROM tl_cb_lessondata WHERE pid=$intCourseDataId AND lessonid=$intLesson $strDataQuery")->limit(1)->execute();
		
		// Set or create new data
		if ($objData->numRows)
		{
			$this->setFromRow($objData, $this->strTable, 'id');
			$this->tstamp = $time;
			$this->lastitem = 1;
		}
		else
		{
			$this->setData(array
			(
				'pid'			=> $intCourseDataId,
				'tstamp'		=> time(),
				'lessonid'		=> $intLesson,
				'lastpage'		=> 0,
				'maxpage'		=> 0,
				'status'		=> 'in_progress',
				'lastitem'		=> 1
			));
			$this->modified = true;
		}

		// Temporary data available, move to this data. Must be after creating new data!
 		if (FE_USER_LOGGED_IN && strlen($this->strHash))
 		{
			$objData = new CBLessonData();
			if ($objData->findBy('session', $this->strHash))
			{
				$this->transferFromSubmission($objData, 'lessonid', false);
				$objData->delete();
			}

			// Delete cookie
			$this->setCookie($this->strCookie, '', ($time - 3600), $GLOBALS['TL_CONFIG']['websitePath']);
 		}
 		
	}

	/**
	 * Return wrong answers
	 */	
	public function getWrong()
	{
		return ''; //@todo - get the failure message in there
	}


}
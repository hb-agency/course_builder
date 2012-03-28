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

class CBCourse extends Controller
{


	/**
	 * Name of the current table
	 * @var string
	 */
	protected $strTable = 'tl_cb_course';

	/**
	 * Success template
	 * @var string
	 */
	protected $strSuccessTemplate = 'cb_success';
	
	/**
	 * Failure template
	 * @var string
	 */
	protected $strFailureTemplate = 'cb_failure';
	
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
	 * whether to lock all data, for example, in displaying results
	 * @var boolean
	 */
	protected $blnLocked = false;
	
	/**
	 * Final element ID
	 * @var array
	 */
	public $arrFinalElement;
	
	/**
	 * current element ID for the logged in User
	 * @var int
	 */
	public $intCurrElement;
	
	/**
	 * current element object for the logged in User
	 * @var object
	 */
	public $currElement;
	
	/**
	 * current element object for the logged in User
	 * @var string
	 */
	public $strCurrElement;
	
	/**
	 * current element type for the logged in User
	 * @var string
	 */
	public $strCurrType;
	
	/**
	 * current status
	 * @var bool
	 */
	public $blnComplete = false;
	
	/**
	 * whether to show nav item on initial review
	 * @todo - make this the primary method
	 * @var bool
	 */
	public $blnShowNav = false;
	
	/**
	 * current status message (returned on success/failure)
	 * @var string
	 */
	protected $strCurrMessage;
	
	/**
	 * CourseBuilder object
	 * @var object
	 */
	protected $CourseBuilder;
	
	
	/**
	 * Construct the object
	 */
	public function __construct($arrData, $blnLocked=false)
	{
		parent::__construct();
		$this->import('Database');
		$this->import('CourseBuilder');
		
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
		}
		
		$this->arrData = $arrData;
		
		//Get the current element
		$this->CourseBuilder->getCurrentElement($this);
				
		//No elements left - user has finished the course.
		if(!$this->strCurrType && !$this->strCurrElement && is_array($this->arrFinalElement))
		{
			$arrElement = $this->arrFinalElement;
			$strObj = $arrElement['type'];
			
			//Mark the course as completed
			if( !$this->blnComplete )
			{
				$this->Database->prepare("UPDATE tl_cb_coursedata SET status='complete' WHERE id=?")
							   ->executeUncached($arrElement['dataid']);
			}
			
			
			//Lock data to prevent any modifications
			$this->CourseBuilder->$strObj->blnLocked = true;
			$this->CourseBuilder->$strObj->initializeData($this->id, $arrElement['id']);
			if( $this->CourseBuilder->$strObj->score >= $arrElement['passing_score'] )
			{
				//Course passed. Log as passed if needed and set success template
				if( !$this->blnComplete )
				{
					$strUniqId = uniqid($intDataId, true);
					$this->Database->prepare("UPDATE tl_cb_coursedata SET pass=1, uniqid=? WHERE id=?")
								   ->execute($strUniqId, $arrElement['dataid']);
				}
				else
				{
					$strUniqId = $this->Database->prepare("SELECT uniqid FROM tl_cb_coursedata WHERE id=?")
												->limit(1)->execute($arrElement['dataid'])
												->uniqid;
				}
				$objTemplate = new FrontendTemplate( $this->strSuccessTemplate );
				$objTemplate->certificate = $this->CourseBuilder->addToUrl('certificate=' . $strUniqId );
				$objTemplate->headline = $GLOBALS['TL_LANG']['CB']['MISC']['hsuccess'];
				$objTemplate->message = $GLOBALS['TL_LANG']['CB']['MISC']['success'];		
				$objTemplate->showCourseNav = ($strObj == 'Quiz' && !$this->CourseBuilder->$strObj->final) ? '1' : '';		
			}
			else
			{
				//Course failed. Show failure template.
				$objTemplate = new FrontendTemplate( $this->strFailureTemplate );
				$objTemplate->href = $this->Environment->request;
				$objTemplate->headline = $GLOBALS['TL_LANG']['CB']['MISC']['hfailure'];
				$objTemplate->message = $GLOBALS['TL_LANG']['CB']['MISC']['failure'];
				$objTemplate->showCourseNav = ($this->CourseBuilder->$strObj->final && $this->CourseBuilder->$strObj->reviewable && $this->CourseBuilder->$strObj->canretake) ? '1' : ($strObj == 'Quiz' && !$this->CourseBuilder->$strObj->final ? '1' : '');
			}
			
			$objTemplate->score = round($this->CourseBuilder->$strObj->score, 0);
			$objTemplate->wrongMsg = $GLOBALS['TL_LANG']['CB']['MISC']['wrong'];
			$objTemplate->question_label = $GLOBALS['TL_LANG']['CB']['MISC']['question_label'];
			$objTemplate->response_label = $GLOBALS['TL_LANG']['CB']['MISC']['response_label'];
			$objTemplate->correct_label = $GLOBALS['TL_LANG']['CB']['MISC']['correct_label'];
			
			$objTemplate->wrong = $this->CourseBuilder->$strObj->getWrong();
			
			$this->strCurrStatus = $objTemplate->parse();	
		}
		
		if($this->blnShowNav)
		{
			if($this->CourseBuilder->blnReview)
			{
				$this->strCurrType = '';
				$this->strCurrElement = '';
			}
		}

	}
	
	
	/**
	 * Get a property
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch( $strKey)
		{
			case 'id':
			case 'pid':
			case 'href_reader':
			case 'coursenavmodule':
				return $this->arrData[$strKey];
				break;
			case 'currType':
				return $this->strCurrType;
				break;
			case 'currEl':
				return $this->strCurrElement;
				break;
			default:
				return $this->arrCache[$strKey] ? $this->arrCache[$strKey] : '';
		}
	}
	
	/**
	 * Set a property
	 */
	public function __set($strKey, $varValue)
	{
		switch( $strKey )
		{
			default:
				$this->arrCache[$strKey] = $varValue;
		}

	}
	
	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}
	
	/**
	 * Return the current data as associative array
	 * @return array
	 */
	public function getData()
	{
		return $this->arrData;
	}
	
	/**
	 * Return the current element data array
	 * @return array
	 */
	public function getElements()
	{
		return deserialize($this->arrData['courseelements']);
	}
	
	
	/**
	 * Generate a course template
	 */
	public function generate($strTemplate, &$objModule)
	{
		$strTemplate = strlen($strTemplate) ? $strTemplate : 'cb_course_default';
		$objTemplate = new FrontendTemplate($strTemplate);
		$arrSegments = array();
		
		if($this->blnShowNav)
		{
			if($this->CourseBuilder->blnReview)
			{
				$objTemplate->showNav = true;
				if($this->coursenavmodule)
				{
					$objTemplate->courseNav = $this->getFrontendModule($this->coursenavmodule);
				}
			}
		}
		
		//generate the current course element if it exists
		if( is_array($GLOBALS['CB_ELEMENT'][$this->strCurrType]) )
		{
			$elConfig = $GLOBALS['CB_ELEMENT'][$this->strCurrType];
			$strTable = $elConfig['table'];
			$strTemplate = $elConfig['template'];
			$arrData = $this->Database->prepare("SELECT * FROM $strTable WHERE id=?")->limit(1)->execute($this->strCurrElement)->row();
			
			$strClass = $elConfig['class'];
	        $arrData['courseid'] = $this->id;
	        if ($strClass)
	        {
	          try
	          {
	            $objElement = new $strClass($arrData, $this->blnReview);
	          }
	          catch (Exception $e)
	          {
	            $this->log(sprintf('Unable to load CBCourseElement %s, Error: %s', $strClass, $e), 'CBCourse __construct()', TL_ERROR);
	          }
	        }
	        if($objElement)
	        { 
	          $this->currElement = $objElement;
	          $objTemplate->segment = $this->currElement->generate($objModule->{$strTemplate}, $objModule);
	        
	        }
		}
		
		$objTemplate->statusmessage = $this->strCurrStatus;
		
		// HOOK for altering course data before output
		if (isset($GLOBALS['TL_HOOKS']['cb_generateCourse']) && is_array($GLOBALS['TL_HOOKS']['cb_generateCourse']))
		{
			foreach ($GLOBALS['TL_HOOKS']['cb_generateCourse'] as $callback)
			{
				$this->import($callback[0]);
				$objTemplate = $this->$callback[0]->$callback[1]($objTemplate, $this);
			}
		}

		return $objTemplate->parse();
	
	}

}
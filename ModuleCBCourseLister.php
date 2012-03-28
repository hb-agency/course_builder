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

class ModuleCBCourseLister extends ModuleCB
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_courselist_default';
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### COURSE BUILDER: COURSE LIST ###';
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
		if($this->cb_courselist_layout)
		{
			$this->Template = new FrontendTemplate($this->cb_courselist_layout);
		}
	
		$arrCourses = $this->getAvailableCourses();
		$arrRows = array();
				
		if(count($arrCourses))
		{
			$arrRows = $this->generateCourseRows($arrCourses);
		}
		else
		{
			$_SESSION['CB_ERROR'][] = $GLOBALS['TL_LANG']['CB']['ERR']['nocourses'];
		}
				
		$this->Template->courses = $arrRows;
	}
	
	/**
	 * Build rows of data for each course element type
	 * @param  array
	 * @return array
	 */
	protected function generateCourseRows($arrCourses)
	{
		$return = array();
		$count=0;
		foreach( $arrCourses as $arrCourse)
		{
			$arrRow = array();
			$blnResume = $this->checkForProgress( $arrCourse['id'] );
			$blnRetake = $this->checkForProgress( $arrCourse['id'], true, false );
			$blnReview = $this->checkForReview( $arrCourse );
			$blnPassed = $this->checkForProgress( $arrCourse['id'], true, true );
			$arrRow = $arrCourse;
			$arrRow['label'] = $arrCourse['name'];
			$arrRow['resume'] = $blnPassed ? $GLOBALS['TL_LANG']['CB']['MISC']['retake'] : ($blnResume ? $GLOBALS['TL_LANG']['CB']['MISC']['resume'] : ($blnRetake ? $GLOBALS['TL_LANG']['CB']['MISC']['retake'] : $GLOBALS['TL_LANG']['CB']['MISC']['begin']));
			$arrRow['class'] = ($count==0 ? 'first ' : ($count==count($arrCourses)-1 ? 'last ' : ''));
			$arrRow['class'] .= 'row_'.$count;
			
			$intPage = $this->cb_reader_jumpTo;
			
			// If the user has already passed the course, send them to the store page
			if (!$blnPassed)
			{
				$arrRow['href'] = $this->generateFrontendURL($this->Database->execute("SELECT * FROM tl_page WHERE id={$intPage}")->fetchAssoc(), '/course/' . $arrCourse['alias']); // @todo accommodate for non-alias URLs
			}
			else
			{
				$arrRow['href'] = $this->generateFrontendURL($this->Database->execute("SELECT * FROM tl_page WHERE id={$this->cb_productlist_jumpTo}")->fetchAssoc());
			}
			
			if( $blnResume && !$blnPassed )
			{
				$arrRow['restart'] = $GLOBALS['TL_LANG']['CB']['MISC']['restart'];								
				$arrRow['restarthref'] = $this->generateFrontendURL($this->Database->execute("SELECT * FROM tl_page WHERE id={$intPage}")->fetchAssoc(), '/course/' . $arrCourse['alias'] . '/action/restart'); // @todo accommodate for non-alias URLs
			}
			
			if( $blnReview )
			{
				$arrRow['review'] = $GLOBALS['TL_LANG']['CB']['MISC']['review'];
				$arrRow['reviewhref'] = $this->generateFrontendURL($this->Database->execute("SELECT * FROM tl_page WHERE id={$intPage}")->fetchAssoc(), '/course/' . $arrCourse['alias'] . '/action/review/firstrun/true'); // @todo accommodate for non-alias URLs
			}
			
			if( $blnPassed )
			{
				$objCourseData = $this->getCourseData($arrCourse['id'], true, true);
				//$objUniqid = $this->Database->prepare("SELECT uniqid FROM tl_cb_coursedata WHERE courseid=? AND status='complete' AND LENGTH(uniqid) > 1")->limit(1)->execute($arrCourse['id']);
				if ($objCourseData->numRows)
				{
					$arrRow['certificate'] = $GLOBALS['TL_LANG']['CB']['MISC']['printcert'];
					$arrRow['certificatehref'] = $this->CourseBuilder->addToUrl('certificate=' . $objCourseData->uniqid ); // @todo accommodate for non-alias URLs
				}		
			}			
			
			$return[] = $arrRow;
			$count++;
		}
				
		return $return;
	}
	
	/**
	 * Performs a check to see if a course has been started or not by this user
	 * @param  int
	 * @param bool
	 * @return bool
	 */
	protected function checkForProgress( $intCourse, $blnComplete=false, $blnPass=false )
	{
		$strCheck = $blnComplete ? 'complete' : 'in_progress';
		$strPass = $blnPass ? 'c.pass=1' : 'c.pass<>1';
		
		//Loop through all Data Objects until we hit one that has been visited
		foreach($GLOBALS['CB_ELEMENT'] as $strKey => $arrData)
		{
			$objData = $this->Database->prepare("SELECT x.maxpage FROM tl_cb_coursedata c LEFT JOIN ". $arrData['data']['table'] ." x ON x.pid=c.id WHERE (x.status='in_progress' OR x.status='complete') AND c.status=? AND c.courseid=? AND c.pid=? AND $strPass")->execute( $strCheck , $intCourse, $this->User->id );
			if( $objData->numRows )
				return true;
		}
		
		return false;
	
	}
	
	/**
	 * Performs a check to see if a course has been finished and also whether it is allowed to be reviewable
	 * @param  array
	 * @return bool
	 */
	protected function checkForReview( $arrCourse )
	{
		//First check to see if course is completed… otherwise the rest is moot
		if( $this->checkForProgress( $arrCourse['id'], true, true ) )
		{
			//Loop through all Elements until we find one that is reviewable
			$arrElements = deserialize( $arrCourse['courseelements'], true );
			
			foreach( $arrElements as $element)
			{
				$arrEl = explode('|', $element);
				$strTable = $GLOBALS['CB_ELEMENT'][ $arrEl[0] ]['table'];
				
				$objData = $this->Database->prepare("SELECT * FROM $strTable WHERE id=? AND reviewable=1")->execute( $arrEl[1] );
				if( $objData->numRows )
					return true;
			}
		}
		
		return false;
	
	}
	
	/**
	 * Returns data for both logged in members or mon-members
	 * @return object
	 */
	protected function getCourseData($intCourseID=0, $blnComplete=false, $blnPass=false)
	{	
		$time = time();
		$this->strHash = $this->Input->cookie($this->strCookie);
		
		$strQuery = $blnComplete ? "AND status ='complete'" : "AND status ='in_progress' ";
		$strQuery .= " AND " . ($blnPass ? "pass=1 " : "pass<>1 ");
		$strQuery .= ($intCourseID > 0) ? "AND courseid = '{$intCourseID}' " : " ";

		//  Check to see if the user is logged in.
		if (!FE_USER_LOGGED_IN || !$this->User->id)
		{
			if (!strlen($this->strHash))
			{
				$this->strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . $intConfig . $this->strCookie);
				$this->setCookie($this->strCookie, $this->strHash, $time+$GLOBALS['TL_CONFIG']['cb_dataTimeout'], $GLOBALS['TL_CONFIG']['websitePath']);
			}

			$objCourseData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE session='{$this->strHash}' $strQuery ORDER BY attempt DESC")->limit(1)->execute();
		}
		else
		{
			$this->strHash = '';
			$objCourseData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid={$this->User->id} $strQuery ORDER BY attempt DESC")->limit(1)->execute();
		}
		
		return $objCourseData;
	}
	

}
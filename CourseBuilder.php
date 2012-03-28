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


class CourseBuilder extends Controller
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;
	
	/**
	 * Current course info
	 * @var array
	 */	
	public $courseInfo = array();
	
	/**
	 * Current CBCourse object
	 * @var object
	 */	
	public $Course;
	
	/**
	 * Current quiz object
	 * @var object
	 */	
	public $Quiz;
	
	/**
	 * Current lesson object
	 * @var object
	 */	
	public $Lesson;
	
	/**
	 * Whether we are in review mode
	 * @var bool
	 */
	public $blnReview = false;
	
	/**
	 * Whether to force show nav
	 * @var bool
	 */
	public $blnShowNav = false;
	
	/**
	 * Whether we are overriding a section
	 * @var string
	 */
	public $strSection;
	
	/**
	 * Whether we are overriding a section
	 * @var string
	 */
	protected $strFormID= 'coursebuilder';
	

	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}

	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('FrontendUser', 'User');
		
		if (TL_MODE == 'FE')
		{
			//Create all Data Objects
			foreach($GLOBALS['CB_ELEMENT'] as $strKey => $arrData)
			{
				$strRef = ucwords($strKey);
				$strClass = $arrData['data']['class'];
				try
				{
					$this->{$strRef} = new $strClass();
				}
				catch (Exception $e)
				{
					$this->log(sprintf('Unable to load CBCourseData %s, Error: %s', $strClass, $e), 'CourseBuilder __construct()', TL_ERROR);
				}
			}
			//If there a course available, we get its info
			if($this->Input->get('course'))
			{
				$this->courseInfo = CBFrontend::getCourseByAlias($this->Input->get('course'));
				$this->strFormID = 'coursebuilder_' . $this->Input->get('course');
			}
						
			$this->handleActions();
			$this->handlePosts();
		}

	}
	
	
	/**
	 * Set an object property
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 */
	public function setNextElement($varValue)
	{
		$this->nextElement = $varValue;
	}
	
	/**
	 * Set an object property
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 */
	public function setPrevElement($varValue)
	{
		$this->prevElement = $varValue;
	}
	
	
	/**
	 * Get an object property
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 */
	public function getNextElement()
	{
		return $this->nextElement;
	}
	
	/**
	 * Get an object property
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 */
	public function getPrevElement()
	{
		return $this->prevElement;
	}
	
	
	/**
	 * Get an object property
	 *
	 * @access public
	 * @param string
	 * @param mixed
	 */
	public function getFormID()
	{
		return $this->strFormID;
	}
	
	
	
	/**
	 * Instantiate a database driver object and return it (Factory)
	 *
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new CourseBuilder();
		}

		return self::$objInstance;
	}
	

	/**
	 * Generate a content element return it as HTML string, only from another table
	 * @param integer
	 * @param string
	 * @return string
	 */
	public function getCBContentElement($intId, $strTable)
	{
		if (!strlen($intId) || $intId < 1 || !strlen($strTable))
		{
			return '';
		}

		$this->import('Database');

		$objElement = $this->Database->prepare("SELECT * FROM {$strTable} WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

		if ($objElement->numRows < 1)
		{
			return '';
		}

		// Show to guests only
		if ($objElement->guests && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$objElement->protected)
		{
			return '';
		}

		// Protected element
		if ($objElement->protected && !BE_USER_LOGGED_IN)
		{
			if (!FE_USER_LOGGED_IN)
			{
				return '';
			}

			$this->import('FrontendUser', 'User');
			$groups = deserialize($objElement->groups);

			if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
			{
				return '';
			}
		}

		// Remove spacing in the back end preview
		if (TL_MODE == 'BE')
		{
			$objElement->space = null;
		}

		$strClass = $this->findContentElement($objElement->type);

		// Return if the class does not exist
		if (!$this->classFileExists($strClass))
		{
			$this->log('Content element class "'.$strClass.'" (content element "'.$objElement->type.'") does not exist', 'CBCourse getContentElement()', TL_ERROR);
			return '';
		}

		$objElement->typePrefix = 'ce_';
		$objElement = new $strClass($objElement);
		$strBuffer = $objElement->generate();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getContentElement']) && is_array($GLOBALS['TL_HOOKS']['getContentElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getContentElement'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($objElement, $strBuffer);
			}
		}

		// Disable indexing if protected
		if ($objElement->protected && !preg_match('/^\s*<!-- indexer::stop/i', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}


	/**
	 * Overwrite parent method as front end URLs are handled differently
	 * @todo - Find a way to do this using the Frontend method instead of duplicating
	 * @param string
	 * @param boolean
	 * @return string
	 */
	protected function addToUrl($strRequest, $blnIgnoreParams=false)
	{
		$arrGet = $blnIgnoreParams ? array() : $_GET;

		// Clean the $_GET values (thanks to thyon)
		foreach (array_keys($arrGet) as $key)
		{
			$arrGet[$key] = $this->Input->get($key, true);
		}

		$arrFragments = preg_split('/&(amp;)?/i', $strRequest);

		// Merge the new request string
		foreach ($arrFragments as $strFragment)
		{
			list($key, $value) = explode('=', $strFragment);

			if ($value == '')
			{
				unset($arrGet[$key]);
			}
			else
			{
				$arrGet[$key] = $value;
			}
		}

		$strParams = '';

		foreach ($arrGet as $k=>$v)
		{
			$strParams .= $GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' . $k . '=' . $v  : '/' . $k . '/' . $v;
		}

		// Do not use aliases
		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
		}

		global $objPage;
		$pageId = strlen($objPage->alias) ? $objPage->alias : $objPage->id;

		// Get page ID from URL if not set
		if (empty($pageId))
		{
			$pageId = $this->getPageIdFromUrl();
		}

		return ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $pageId . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
	}
	
	
	/**
	 * Get the current element
	 * @param string
	 * @return string
	 */
	public function getCurrentElement($objCourse)
	{	
		//Setup initial values
		$arrElements = $objCourse->getElements();
		
		$intDataId = 0;
		
		//Find the last known position for the logged in user for the assigned course
		if($this->User->id && $objCourse->id)
		{
			$objCurrentData = $this->getCurrentData($objCourse->getData());
			$objPastData 	= $this->getPastData($objCourse->getData());
			$objSuccessData = $this->getSuccessData($objCourse->getData());			  
			
			if( !$objCurrentData->numRows && (!$objSuccessData->numRows || $this->blnReview) )
			{
				$intNewAttempt = $this->blnReview ? 1 : (int)$objPastData->numRows + 1;
			
				//Need to generate a new record for this course
				$arrSet = array(
					'pid'		=> $this->User->id,
  					'tstamp'	=> time(),
  					'session'	=> '',
  					'courseid'	=> $objCourse->id,
  					'status' 	=> $this->blnReview ? 'review' : 'in_progress',
  					'settings'	=> array(),
  					'attempt'	=> $intNewAttempt,
  				);
  				
				// HOOK: used for setting unknown fields in tl_cb_coursedata
				if (isset($GLOBALS['CB_HOOKS']['insertCourseData']) && is_array($GLOBALS['CB_HOOKS']['insertCourseData']))
				{
					foreach ($GLOBALS['CB_HOOKS']['insertCourseData'] as $callback)
					{
						$this->import($callback[0]);
						$arrSet = $this->$callback[0]->$callback[1]($arrSet, $objCourse, $this);
					}
				}
  				
  				$blnNewRecord = true;
  				$intDataId = $this->Database->prepare("INSERT INTO tl_cb_coursedata %s")->set($arrSet)->execute()->insertId;
  				  				
			}
			elseif( $objSuccessData->numRows && !$this->blnReview )
			{
				$intDataId = $objSuccessData->id;
				$objCourse->blnComplete = true;
			}
			else
			{
				$intDataId = $objCurrentData->id;
			}
			
		}
		
		//Create all data elements in a review stage
		if($this->blnReview)
		{
			$objCourse->blnReview = true;
			$this->createAllDataElements($objCourse->getData(), $intDataId, true);
			
			//The first time accessing the review
			if($blnNewRecord)
			{
				$objCourse->blnShowNav = true;
			}
		}
		
		//Build an array of the active course elements & loop through each one until we find the current place the user left off
		if(count($arrElements))
		{
			foreach( $arrElements as $value )
			{	
				list($elConfig, $strTable, $strColumn, $strObj, $arrElement) = $this->getConfigData($value);
				
				if( is_array($elConfig) )
				{
					
					$objData = $this->Database->prepare("SELECT * FROM $strTable WHERE pid=$intDataId AND $strColumn=? AND status<>'skipped'")->limit(1)->execute($arrElement[1]);
					
					if( !$objData->numRows || $objData->status != 'complete' || $objData->lastitem==1)
					{
						//Tell the CB object about the next & previous element in the array
						$this->nextElement = $arrElements[$count+1];
						$this->previousElement = $arrElements[$count-1];
						
						//Initialize
						$this->$strObj->initializeData($objCourse->id, $arrElement[1], $this->blnReview);
						
						$objCourse->strCurrType = $arrElement[0];
						$objCourse->strCurrElement = $arrElement[1];
						break;
					}
					
					//Determine the final course element and its passing score...
					$strSrcTable = $elConfig['table'];
					$objSrc = $this->Database->prepare("SELECT id, passing_score FROM $strSrcTable WHERE id=? AND final=1")->limit(1)->execute($arrElement[1]);
					if( $objSrc->numRows )
					{
						$arrFinal = array(
							'type'	=> $strObj, 
							'id'	=> $arrElement[1], 
							'passing_score'=> $objSrc->passing_score, 
							'dataid'=>$intDataId
						);
						$objCourse->arrFinalElement = $arrFinal;
						
					}
				}
			}
		}
	}
	
	/**
	 * Handle all incoming $_POST actions appropriately
	 * @param string
	 * @return string
	 */
	public function handlePosts()
	{
		if($this->Input->post('FORM_SUBMIT')==$this->strFormID)
		{
			//Handle proceed
			if($_POST['proceed'] && $this->Input->post('SECTION_ID'))
			{
				$this->handleSection($this->Input->post('SECTION_ID'), true);
			}
			
			//Handle retake
			if($_POST['retakelesson'] && $this->Input->post('SECTION_ID'))
			{
				$this->handleRetake($this->Input->post('SECTION_ID'));
			}
			
		}
	}
	
	/**
	 * Handle all incoming $_GET actions appropriately
	 * @param string
	 * @return string
	 */
	public function handleActions()
	{
		global $objPage;
		$blnRefresh = false;
	
		// Handle input actions		
		if($this->Input->get('action'))
		{
			switch ($this->Input->get('action'))
			{
				case 'review':
					$this->blnReview = true;
					break;
					
				case 'restart':
					$this->handleRestart();
					$blnRefresh = true;
					break;
				
				default:
					// HOOK: pass unknown actions to callback functions
					if (isset($GLOBALS['CB_HOOKS']['handleActions']) && is_array($GLOBALS['CB_HOOKS']['handleActions']))
					{
						foreach ($GLOBALS['CB_HOOKS']['handleActions'] as $callback)
						{
							$this->import($callback[0]);
							$blnRefresh = $this->$callback[0]->$callback[1]($this->Input->get('action'), $this);
						}
					}
			}
			
			if($blnRefresh)
			{
				//Redirect to a clean URL - @todo - accommodate listing pages
				$this->redirect( $this->generateFrontendURL($this->Database->execute("SELECT * FROM tl_page WHERE id={$objPage->id}")->fetchAssoc(), '/course/' . $this->Input->get('course') ));
			}
		}
		
		//Handle first run setting
		if($this->Input->get('firstrun') && $this->Input->get('firstrun')=='true')
		{
			if($this->blnReview)
			{
				$this->flushData($this->courseInfo, true);
				$this->redirect( $this->generateFrontendURL($this->Database->execute("SELECT * FROM tl_page WHERE id={$objPage->id}")->fetchAssoc(), '/course/' . $this->Input->get('course') . '/action/review' ));
			}
			else
			{
				$this->flushData($this->courseInfo, false);
			}
			$this->blnShowNav = true;
		}
		
		// Handle section navigation last
		if($this->Input->get('section'))
		{
			$this->handleSection($this->Input->get('section'));
		}
	}
	
	/**
	 * Handle a course restart
	 * @return void
	 */
	protected function handleRestart()
	{
		$arrCourse = $this->courseInfo;
		
		//Reset any available data
		if($arrCourse['id'])
		{
			$objCurrentData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid=? AND courseid=? AND status='in_progress'")
													->limit(1)
												 	->execute($this->User->id, $arrCourse['id']);

			if( $objCurrentData->numRows )
			{
				$objReset = $this->Database->prepare("UPDATE tl_cb_coursedata SET status='skipped' WHERE id=?")->execute($objCurrentData->id);
			}
		}
		
	}
	
	/**
	 * Handle a jumpto a section or its next element
	 * @return void
	 */
	protected function handleRetake($strSection)
	{
		list($elConfig, $strTable, $strColumn, $strObj, $arrElement) = $this->getConfigData($strSection);
		$objEl = $this->Database->prepare("SELECT * FROM {$elConfig['table']} WHERE id=?")->limit(1)->execute($arrElement[1]);
		
		//Get Current Data
		$objCurrentData = $this->getCurrentData();
		
		if($objCurrentData->numRows)
		{
			//Set status to skipped on retake
			$objData = $this->Database->prepare("SELECT * FROM $strTable WHERE pid=? AND $strColumn=?")
									  ->execute($objCurrentData->id, $arrElement[1]);
									  
			if($objData->numRows && $objData->status != 'complete')
			{
				$this->Database->prepare("UPDATE $strTable SET status='skipped' WHERE pid=? AND $strColumn=?")
							   ->execute($objCurrentData->id, $arrElement[1]);
			}
		}
		//Handle parent lessons
		if($objEl->numRows && $objEl->plesson)
		{
			$strSection = 'lesson|'.$objEl->plesson;
		}
		
		$this->handleSection($strSection);
	}
	
	
	/**
	 * Handle a jumpto a section or its next element
	 * @return void
	 */
	protected function handleSection($strSection, $blnNextItem=false)
	{
		$arrCourse = $this->courseInfo;
		$blnReset = false;
		$blnRunonnext = false;
				
		$objCurrentData = $this->getCurrentData($arrCourse);
		$strAction = $this->blnReview ? '&action=review&course=' : '&course=';
		
		$arrElements = deserialize($arrCourse['courseelements'], true);
		
		foreach($arrElements as $value)
		{
			$blnReset = ($value == $strSection || $blnRunonnext) ? true : false;
			$blnRunonnext = false;
			
			if($blnNextItem && $value == $strSection)
			{
				$blnRunonnext = true;
				$blnReset = false;
			}
			
			$intLast = ($blnReset ? 1 : 0);
			
			list($elConfig, $strTable, $strColumn, $strObj, $arrElement) = $this->getConfigData($value);
			
			//Check for current data
			$objCurr = $this->Database->prepare("SELECT * FROM $strTable WHERE pid=? and $strColumn=?")
									  ->execute($objCurrentData->id, $arrElement[1]);
			
			$intPage = $this->Input->get('page') && $value == $strSection ? ($objCurr->maxpage < $this->Input->get('page') ? $objCurr->maxpage : $this->Input->get('page')) : 0;
									
			if( is_array($elConfig) )
			{
				
				//Update data
				$objData = $this->Database->prepare("UPDATE $strTable SET lastpage=?, lastitem=? WHERE pid=? and $strColumn=?")
							    ->execute($intPage, $intLast, $objCurrentData->id, $arrElement[1]);
							    
			}
		}
							  
		$this->redirect($this->addToUrl($strAction . $this->Input->get('course'), true));
	}
		
	/**
	 * Create all data elements for a review stage
	 * @return void
	 */
	protected function createAllDataElements($arrCourse, $intParent, $blnComplete=false)
	{
		$strStatus = $blnComplete ? 'complete' : 'in_progress';
		$arrElements = deserialize($arrCourse['courseelements'], true);
		$count=1;
		foreach($arrElements as $value)
		{
			list($elConfig, $strTable, $strColumn, $strObj, $arrElement) = $this->getConfigData($value);
			if( is_array($elConfig) )
			{
				$objCurr = $this->Database->prepare("SELECT * FROM $strTable WHERE pid=? and $strColumn=?")
						    ->execute($intParent, $arrElement[1]);
				
				if(!$objCurr->numRows)		    
				{
					$arrSet = array
					(
						'pid'	=> $intParent,
						'tstamp'=>time(),
						'status'=>$strStatus,
						$strColumn => $arrElement[1],
						'lastitem' => $count
					);
					
					$objInsert = $this->Database->prepare("INSERT INTO $strTable %s")->set($arrSet)->executeUncached();
				}
				
				$count=0;
			}
		}
	}
	
	
	/**
	 * Clear the current page setting and optionally delete all data associated with this record. Danger!
	 * @return void
	 */
	protected function flushData($arrCourse=array(), $blnClearData=false)
	{
		if(!$arrCourse['courseelements'] || !$arrCourse['id'])
			$arrCourse = $this->courseInfo;
			
		$objCurrentData = $this->getCurrentData($arrCourse);
		
		if($objCurrentData->numRows)
		{
			$arrElements = deserialize($arrCourse['courseelements'], true);
			foreach($arrElements as $value)
			{
				list($elConfig, $strTable, $strColumn, $strObj, $arrElement) = $this->getConfigData($value);
				if( is_array($elConfig) )
				{
					if($blnClearData)
					{
						$this->Database->prepare("DELETE FROM $strTable WHERE pid=? and $strColumn=?")
							    					->execute($objCurrentData->id, $arrElement[1]);
					}
					else
					{
						$this->Database->prepare("UPDATE $strTable SET lastitem=0,status='in_progress' WHERE pid=? and $strColumn=?")
							    					->execute($objCurrentData->id, $arrElement[1]);
					}
				}
			}
			
			if($blnClearData)
			{
				//Clear the parent record
				$this->Database->prepare("DELETE FROM tl_cb_coursedata WHERE id=?")->execute($objCurrentData->id);
			}
		}
	}
	
	
	/**
	 * Get the current course data
	 * @return DatabaseResult
	 */
	protected function getCurrentData($arrCourse=array())
	{
		if(!$arrCourse['id'])
			$arrCourse = $this->courseInfo;
			
		$strSelect = $this->blnReview ? "'in_progress','review', 'ready'" : "'in_progress', 'ready'";
		
		return $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid=? AND courseid=? AND status IN ($strSelect)")
							  ->execute($this->User->id, $arrCourse['id']);
	}
	
	/**
	 * Get the past course data
	 * @return DatabaseResult
	 */
	protected function getPastData($arrCourse=array())
	{
		if(!$arrCourse['id'])
			$arrCourse = $this->courseInfo;
		
		return $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid=? AND courseid=? AND status NOT IN ('in_progress', 'ready') AND pass<>1")
							  ->execute($this->User->id, $arrCourse['id']);
	}
	
	/**
	 * Get the past course data
	 * @return DatabaseResult
	 */
	protected function getSuccessData($arrCourse=array())
	{
		if(!$arrCourse['id'])
			$arrCourse = $this->courseInfo;
		
		return $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE pid=? AND courseid=? AND status='complete' AND pass=1")
							  ->execute($this->User->id, $arrCourse['id']);
	}
	
	
	/**
	 * Build the buttons to display on a page
	 */
	public function getButtons($objPage)
	{
		//Build prev/next/first/last/skip buttons
		$arrButtons = array();
		
		if($objPage->intCurrpage < $objPage->total_pages && $objPage->intCurrpage != 0 )
		{
			$arrButtons[] = array('name'=>'next', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonnextpage']);
		}
		elseif($objPage->intCurrpage == $objPage->total_pages) //Final page
		{
			$arrButtons[] = array('name'=>'final', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonfinalsegment']);
		}
		elseif( $objPage->intCurrpage==0 ) //First page
		{
			$arrButtons[] = array('name'=>'first', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonfirstsegment']);
		}
		elseif( $objPage->intCurrpage > $objPage->total_pages ) //Success page. Skip to next segment
		{				
			switch( $objPage->strStatus )
			{
				case 'success':
					$arrButtons[] = array('name'=>'proceed', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonnextsegment']);
					break;
					
				case 'failure':			
					
				default:
					$arrButtons[] = array('name'=>'fail', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonretakesegment']);
			}
			
		}
		if($objPage->intCurrpage!=0 && $objPage->intCurrpage <= $objPage->total_pages) //Only hide previous on first page and final page
		{
			$arrButtons[] = array('name'=>'prev', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonprevpage']);
		}
		
		if ($objPage->strStatus == 'failure' && $objPage->objQuiz)
		{
			// Don't show the proceed button if this must be retaken until the user passes
			if ($objPage->objQuiz->canretake && $objPage->objQuiz->retakeuntilpass)
			{
				$arrButtons = array(array('name'=>'retakelesson', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonretakesegment']));
			}
			elseif ($objPage->objQuiz->canretake)
			{
				$arrButtons = array(array('name'=>'retakelesson', 'label'=>$GLOBALS['TL_LANG']['CB']['buttonretakesegment']));
				$arrButtons[] = array('name'=>'proceed', 'label'=>($objPage->objQuiz->final ? $GLOBALS['TL_LANG']['CB']['buttonfinalsegment'] : $GLOBALS['TL_LANG']['CB']['buttonnextsegment']));
			}
			else
			{
				$arrButtons = array(array('name'=>'proceed', 'label'=>($objPage->objQuiz->final ? $GLOBALS['TL_LANG']['CB']['buttonfinalsegment'] : $GLOBALS['TL_LANG']['CB']['buttonnextsegment'])));
			}					
		}
				
		$GLOBALS['TL_MOOTOOLS'][] = '<script type="text/javascript">window.addEvent(\'domready\', function() { $$(\'input.submit\').each(function(item, index){
    item.setProperty("onclick", "this.setProperty(\'onclick\', \'return false;\');"); }); });</script>';
		
		return $arrButtons;
	
	}
	
	
	/**
	 * Determine the current page index, either by default or detect override and check access
	 * @param string
	 * @return int
	 */
	public function getCurrentPage( $strData )
	{
		$intLastPage = (int)$this->{$strData}->lastpage;
		
		//Check for override
		if( $this->Input->get('page') )
		{
			$intOverride = (int)$this->Input->get('page');
			if( $intOverride <= (int)$this->CourseBuilder->$strData->maxpage ) //Don't skip ahead.. Shame on you!
				$this->CourseBuilder->$strData->lastpage = $intOverride; //Set new page
				
						
			//clean up URL and reload
			global $objPage;
			$strRedirect = $this->generateFrontendUrl($this->Database->prepare("SELECT * FROM tl_page WHERE id=?")->execute($objPage->id)->fetchAssoc(), ($this->blnReview ? '/action/review' : '') . '/course/'. $this->Input->get('course'));
			$this->redirect($strRedirect);
		
		}
		
		return $intLastPage;
		
	}
	
	/**
	 * Shortcut to return config data
	 * @param string
	 * @return array
	 */
	protected function getConfigData($strSection)
	{
		$arrElement = explode('|', $strSection);
		$arrConfig = array();
		
		if( is_array($GLOBALS['CB_ELEMENT'][$arrElement[0]]) )
		{			
			$elConfig = $GLOBALS['CB_ELEMENT'][$arrElement[0]];
			$strTable = $elConfig['data']['table'];
			$strColumn = $elConfig['data']['column'];
			$strObj = ucwords($arrElement[0]);
		}
		
		return array($elConfig, $strTable, $strColumn, $strObj, $arrElement);
	
	}
	
	/**
	 * Shortcut to move a page forward
	 * @param string
	 * @return array
	 */
	public function moveForward($strData, $intTotalPages)
	{
		if($this->{$strData})
		{
			$this->{$strData}->lastpage = $this->{$strData}->lastpage <= $intTotalPages ? ((int)$this->{$strData}->lastpage + 1) : $this->{$strData}->lastpage;
		}
		$this->reload();
	}
	
	
	/**
	 * Shortcut to move a page forward
	 * @param string
	 * @return array
	 */
	public function moveBackward($strData, $intTotalPages)
	{
		if($this->{$strData})
		{
			$this->{$strData}->lastpage = ((int)$this->{$strData}->lastpage - 1);
		}
		$this->reload();
	}
	

}
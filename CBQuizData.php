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


class CBQuizData extends CBCourseData
{

	/**
	 * Cookie hash value
	 * @var string
	 */
	protected $strHash = '';
	
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
	 * Name of the temporary quiz cookie
	 * @var string
	 */
	protected $strCookie = 'CB_TEMP_QUIZDATA';
	
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
			case 'questions':
			case 'question':
				if (!isset($this->arrCache[$strKey]))
				{
					$arrQuestions = array();
					$objData = $this->Database->execute("SELECT * FROM {$this->ctable} WHERE pid={$this->id}");
					while( $objData->next() )
					{
						$arrQuestions[$objData->question] = deserialize($objData->response);
					}
					$this->arrCache[$strKey] = $arrQuestions;
				}
				return $this->arrCache[$strKey];
				break;
				
			case 'correct':
			case 'total':
				if (!isset($this->arrCache[$strKey]))
				{
					$objData = $this->Database->execute("SELECT SUM(valid) as correct, COUNT(id) as total FROM {$this->ctable} WHERE pid={$this->id}");
					$this->arrCache[$strKey] = (int)$objData->{$strKey};
				}
				return $this->arrCache[$strKey];
				break;
				
			case 'totalPages':
			case 'pageData':
				if (!isset($this->arrCache[$strKey]))
				{
					$arrPages = $this->Database->prepare("SELECT * FROM tl_cb_quizpage WHERE pid=? ORDER by sorting ASC")
													->execute($this->quizid)
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
	 * Load current data or create new
	 */
	public function initializeData($intCourse, $intQuiz, $blnReview=false)
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
				'session'	=> $this->strHash ? $this->strHash : 0,
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
		
		//Check whether this quiz is a final exam and can be retaken
		$objQuiz = $this->Database->prepare("SELECT * FROM tl_cb_quiz WHERE id=?")->execute($intQuiz);
		$blnResetData = ($objQuiz->canretake && !$this->blnLocked) ? true : false;
		
		$strDataQuery = $this->blnLocked ? "AND status ='complete'" : "AND status<>'skipped'";
		if($blnResetData)
		{
			$this->Database->prepare("UPDATE tl_cb_quizdata SET status='skipped' WHERE pid=$intCourseDataId AND quizid=$intQuiz AND status='complete'")->executeUncached();
		}
		
		//  Check to see if data exists for the current course data attempt
		$objData = $this->Database->prepare("SELECT * FROM tl_cb_quizdata WHERE pid=$intCourseDataId AND quizid=$intQuiz $strDataQuery")->limit(1)->execute();
				
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
				'quizid'		=> $intQuiz,
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
			$objData = new CBQuizData();
			if ($objData->findBy('session', $this->strHash))
			{
				$this->transferFromSubmission($objData, 'quizid', false);
				$objData->delete();
			}

			// Delete cookie
			$this->setCookie($this->strCookie, '', ($time - 3600), $GLOBALS['TL_CONFIG']['websitePath']);
 		}
 		 		
	}

	
	/**
	 * Add user submissions to database
	 */
	public function addResponse($intQuestionID, $varValue, $boolValid)
	{
		// HOOK for adding additional scoring/validation functionality when adding a submission to the database
		if (isset($GLOBALS['TL_HOOKS']['cb_addQuizData']) && is_array($GLOBALS['TL_HOOKS']['cb_addQuizData']))
		{
			foreach ($GLOBALS['TL_HOOKS']['cb_addQuizData'] as $callback)
			{
				$this->import($callback[0]);
				$boolValid = $this->$callback[0]->$callback[1]($intQuestionID, $varValue, $this);
			}
		}
		
		$this->modified = true;

		// Make sure collection is in DB before adding data
		if (!$this->blnRecordExists)
		{
			$this->save();
		}
		
		$finalValue = is_array($varValue) ? serialize($varValue) : $varValue;
		$finalValid = $boolValid ? '1' : '0';
		
		//Check for existing record to update
		$objData = $this->Database->prepare("SELECT * FROM {$this->ctable} WHERE pid={$this->id} AND question={$intQuestionID}")->limit(1)->execute();

		if ($objData->numRows)
		{
			$this->Database->prepare("UPDATE {$this->ctable} SET response=?, valid=? WHERE id={$objData->id}")->execute($finalValue, $finalValid);

			return $objData->id;
		}
		else
		{
			$arrSet = array
			(
				'pid'					=> $this->id,
				'tstamp'				=> time(),
				'question'				=> (int)$intQuestionID,
				'response'				=> $finalValue,
				'valid'					=> $finalValid
			);

			$intInsertId = $this->Database->prepare("INSERT INTO {$this->ctable} %s")->set($arrSet)->executeUncached()->insertId;

			return $intInsertId;
		}
	
	}
	
	
	/**
	 * Clear answers
	 */	
	public function clearAnswers()
	{
		$this->sequencekey = null;
		$this->Database->execute("DELETE FROM {$this->ctable} WHERE pid={$this->id}");
	}
	

	/**
	 * Return incorrect answers
	 */	
	public function getWrong()
	{
		$arrFailedQuestions = array();
		
		$objData = $this->Database->execute("SELECT c.response, q.question, q.choices FROM {$this->ctable} c LEFT JOIN tl_cb_quizquestion q ON q.id=c.question WHERE c.pid={$this->id} AND c.valid<>1");
		
		if( $objData->numRows )
		{
			while( $objData->next() )
			{
				$arrResponse = deserialize($objData->response);
				$arrChoices = deserialize($objData->choices);
				
				$arrFailedQuestions[] = array
				(
					'question'		=> strip_tags($objData->question),
					'response'		=> $arrChoices['text'][((int)$arrResponse['value']) - 1],
					'correct'		=> $arrChoices['text'][$arrChoices['answer']],
					'data'			=> $objData->row(),
				);
			}
		}
				
		return $arrFailedQuestions;
	}

}
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


class CBQuizPage extends CBPage
{
	
	/**
	 * Page template
	 */
	protected $strTemplate = 'cb_quizpage_default';
	
	/**
	 * Question Sequence
	 * @var array
	 */
	protected $arrQuestionSequence;
	
	/**
	 * Quiz Object
	 * @var array
	 */
	public $objQuiz;
	
	/**
	 * Name of the temporary quiz cookie
	 * @var string
	 */
	protected $strCookie = 'CB_TEMP_QUIZDATA';
	
	/**
	 *Review status
	 * @var bool
	 */
	protected $blnReview;
	
	/**
	 * Construct the object
	 */
	public function __construct($arrData, $objQuiz, $intCurrpage, $blnReview)
	{
		parent::__construct($arrData, $objQuiz, $intCurrpage);
		$this->blnReview = $blnReview;
		$this->objQuiz = $objQuiz;
			
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
	 * Generate the page
	 */
	public function generate($objModule)
	{		
		$strTemplate = $this->page_template ? $this->page_template : $this->strTemplate;
		$objTemplate = new FrontendTemplate($strTemplate);
		$objTemplate->questions = array();
		
		$arrQuestions = $this->buildQuestions();
		if(is_array($arrQuestions))
		{
			$objTemplate->questions = $arrQuestions;
		}
		else
		{
			$objTemplate->text = $arrQuestions;
		}
		$objTemplate->buttons = $this->CourseBuilder->getButtons($this);

		//Standard config
		$objTemplate->enctype ='application/x-www-form-urlencoded';
		$objTemplate->formId = $this->CourseBuilder->getFormID();
		$objTemplate->pageID = $this->intCurrpage;
		$objTemplate->sectionID = 'quiz|' . $this->objQuiz->id;
		$objTemplate->parentID = $this->CourseBuilder->Quiz->pid;
		$objTemplate->action = ampersand($this->Environment->request, true);
		$objTemplate->formSubmit = $this->CourseBuilder->getFormID();
		$objTemplate->score = $this->intCurrpage > $this->total_pages ? $this->CourseBuilder->Quiz->score : '';
		$objTemplate->showCourseNav = ($this->objQuiz->final && $this->strStatus == 'failure' && $this->objQuiz->reviewable && $this->objQuiz->canretake) ? true : ((!$this->objQuiz->final && $this->intCurrpage > $this->total_pages) ? true : false);
		if($this->CourseBuilder->Course->coursenavmodule)
		{
			$objTemplate->courseNav = $this->getFrontendModule($this->CourseBuilder->Course->coursenavmodule);
		}
		$objTemplate->wrongMsg = $GLOBALS['TL_LANG']['CB']['MISC']['wrong'];
		$objTemplate->question_label = $GLOBALS['TL_LANG']['CB']['MISC']['question_label'];
		$objTemplate->response_label = $GLOBALS['TL_LANG']['CB']['MISC']['response_label'];
		$objTemplate->correct_label = $GLOBALS['TL_LANG']['CB']['MISC']['correct_label'];
		$objTemplate->wrong = $this->intCurrpage > $this->total_pages ? $this->CourseBuilder->Quiz->getWrong() : array();	
		return $objTemplate->parse();
	
	}
	
	
	/**
	 * Create an array of widgets containing the questions on a given page
	 *
	 * @param array
	 * @param boolean
	 */
	protected function buildQuestions()
	{	
		$this->CourseBuilder->Quiz->lastpage = $this->intCurrpage;
		if($this->intCurrpage > $this->CourseBuilder->Quiz->maxpage)
		{
			$this->CourseBuilder->Quiz->maxpage = $this->intCurrpage;
		}
		
		//Checks for Introduction/Success/Failure
		if($this->intCurrpage==0 && $this->Input->post('FORM_SUBMIT') != $this->CourseBuilder->getFormID())
		{
			return $this->objQuiz->introduction;
		}
		elseif($this->intCurrpage > $this->total_pages)
		{
			//Set score and log attempt/status. Check for division by zero
			$this->CourseBuilder->Quiz->score = $this->CourseBuilder->Quiz->total > 0 ? ($this->CourseBuilder->Quiz->correct/$this->CourseBuilder->Quiz->total)*100 : 0; 
			
			$this->strStatus = ($this->CourseBuilder->Quiz->score >= $this->objQuiz->passing_score ? 'success' : 'failure');
						
			$this->CourseBuilder->Quiz->status = ($this->strStatus == 'success' || ($this->objQuiz->canretake && !$this->objQuiz->final) ? 'complete' : ($this->objQuiz->final ? 'in_progress' : 'skipped'));
			
			if($this->strStatus == 'failure' && !$this->objQuiz->final && $this->objQuiz->canretake)
			{
				$this->CourseBuilder->Quiz->maxpage = 0;
				$this->CourseBuilder->Quiz->lastpage = 0;
			}
			
			if ($this->CourseBuilder->Quiz->status == 'complete')
			{			
				
				if ($this->objQuiz->final && $this->strStatus == 'success')
				{
					$this->CourseBuilder->Quiz->lastitem = 0;
					$this->reload(); //If this is the final quiz, just reload to let the Course display a success/fail message unless 
				}
				elseif(!$this->objQuiz->final)
				{
					$this->CourseBuilder->Quiz->lastitem = 0;
				}
				
			}
			
			//Return success/failure
			return $this->objQuiz->{$this->strStatus};
		}
		
		$arrPageQuestions = array();
		$intQuestionNum = 1;

		$arrQuestions = $this->getQuestions();

		foreach ($arrQuestions as $question)
		{		
			$strClass = $GLOBALS['CB_QUESTION'][$question['questiontype']];
			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$objWidget = new $strClass();
			$objWidget->quizdata = $question;
			$objWidget->absoluteNumber = $this->getQuestionSequence($question['id'], $this->objQuiz->id);
			$objWidget->pageQuestionNumber = $intQuestionNum;
			$objWidget->pageNumber = $this->intCurrpage;
			
			if ($this->Input->post('FORM_SUBMIT') == $this->CourseBuilder->getFormID() && $this->Input->post('SECTION_ID')=='quiz|' . $this->objQuiz->id)
			{
				//Do not require mandatory answers on previous button or if the currentPage equals 0
				if($this->Input->post('prev') && $this->Input->post('prev') == $GLOBALS['TL_LANG']['CB']['buttonprevpage'] || $this->intCurrpage==0)
				{
					$objWidget->mandatory = false;
				}
				
				$objWidget->validate();
				if ($objWidget->hasErrors())
				{
					$this->doNotSubmit = true;
				}
				//Write responses to db
				//@todo - Add in ability to handle text ("other") responses
				$arrChoices = deserialize($question['choices']);
				$boolValid = ($arrChoices['answer']==($objWidget->value['value']-1)) ? true : false;
				$this->CourseBuilder->Quiz->addResponse( $question['id'], $objWidget->value, $boolValid );
			} 
			else 
			{
				if ($this->CourseBuilder->Quiz->question[$question['id']])
				{
					$objWidget->value = $this->CourseBuilder->Quiz->question[$question['id']];
				}
			}
			
			array_push($arrPageQuestions, $objWidget);			
			$intQuestionNum++;
			
		}
		if ($this->Input->post('FORM_SUBMIT') == $this->CourseBuilder->getFormID() && !$this->doNotSubmit && $this->Input->post('SECTION_ID')=='quiz|' . $this->objQuiz->id)
		{
			// HOOK: pass validated questions to callback functions
			if (isset($GLOBALS['TL_HOOKS']['quizQuestionsValidated']) && is_array($GLOBALS['TL_HOOKS']['quizQuestionsValidated']))
			{
				foreach ($GLOBALS['TL_HOOKS']['quizQuestionsValidated'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($arrPageQuestions, $this);
				}
			}
			
			if($this->Input->post('prev') && $this->Input->post('prev') == $GLOBALS['TL_LANG']['CB']['buttonprevpage'])
			{
				$this->CourseBuilder->moveBackward('Quiz', $this->total_pages);
			}
			else
			{
				$this->CourseBuilder->moveForward('Quiz', $this->total_pages);
			}
		}
		else
		{
			// HOOK: pass loaded questions to callback functions
			if (isset($GLOBALS['TL_HOOKS']['quizQuestionsLoaded']) && is_array($GLOBALS['TL_HOOKS']['quizQuestionsLoaded']))
			{
				foreach ($GLOBALS['TL_HOOKS']['quizQuestionsLoaded'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($arrPageQuestions, $this);
				}
			}
			
		}
		
		return $arrPageQuestions;
	}


	/**
	 * Return the total position of a question in the sequence of the quiz
	 *
	 * @param array
	 * @param boolean
	 */
	protected function getQuestionSequence($intQuestionID, $intQuizID)
	{
		if ($intQuestionID > 0 && $intQuizID > 0)
		{
			if (!count($this->arrQuestionSequence))
			{
				$strOrderby = "ORDER BY tl_cb_quizpage.sorting, tl_cb_quizquestion.sorting";
				
				if( $this->objQuiz->randomize && strlen( $this->CourseBuilder->Quiz->sequencekey ) )
				{
					$arrKeys = trimsplit(',',$this->CourseBuilder->Quiz->sequencekey);
					$strOrderby = "ORDER BY FIELD(tl_cb_quizquestion.id,". implode(',',$arrKeys).")";
				
				}
			
				$this->arrQuestionSequence = $this->Database->prepare("SELECT tl_cb_quizquestion.id FROM tl_cb_quizquestion, tl_cb_quizpage WHERE tl_cb_quizquestion.pid = tl_cb_quizpage.id AND tl_cb_quizpage.pid =? ". $strOrderby)
					->executeUncached($intQuizID)
					->fetchEach('id');
			}
			
			return array_search($intQuestionID, $this->arrQuestionSequence) + 1;
		}
		else
		{
			return 0;
		}
	}
	
	
	/**
	 * Return the array of questions for this page
	 *
	 * @return array
	 */
	protected function getQuestions( )
	{
		$arrQuestions = array();
		
		//Check for randomization
		if( $this->objQuiz->randomize && $this->intCurrpage > 0 )
		{			
			if( strlen( $this->CourseBuilder->Quiz->sequencekey ) ) //Check for existing sequence key
			{
				$arrKeys = trimsplit(',',$this->CourseBuilder->Quiz->sequencekey);
				
				if( $this->objQuiz->questionsPerPage > 0 )
				{
					$arrChunks = array_chunk( $arrKeys, $this->objQuiz->questionsPerPage  );
					$arrKeys  = $arrChunks[$this->intCurrpage-1];
				}
				
				//Thanks to http://optimmysql.blogspot.com/2007/09/customized-order-by-sequence-small-hack.html for this customized order-by trick
				$arrQuestions = $this->Database->prepare("SELECT * FROM tl_cb_quizquestion WHERE id IN(".implode(',',$arrKeys).") ORDER BY FIELD(id,". implode(',',$arrKeys).")")
											->execute()
											->fetchAllAssoc();
				
			}
			else
			{
				//Get count of all pages
				$arrPages = $this->Database->prepare("SELECT * FROM tl_cb_quizpage WHERE pid=? ORDER BY sorting ASC")->execute( $this->objQuiz->id )->fetchEach('id');
				
				if( count($arrPages) )
				{
					//Need to get all questions for this Quiz
					//@todo - FInd a more random method - See http://www.greggdev.com/web/articles.php?id=6
					$objQuestions = $this->Database->prepare("SELECT * FROM tl_cb_quizquestion WHERE pid IN(".implode(',',$arrPages).") ORDER BY RAND()");
				
					//Check for need to limit
					if( $this->objQuiz->questionsPerPage > 0 )
					{
						$intTotal = (int) $this->objQuiz->questionsPerPage * (int) count( $arrPages );
						$objQuestions->limit( $intTotal );
						$blnChunk = true;
					}
					
					//Execute query
					$objQuestions = $objQuestions->execute();
					$arrQuestions = $objQuestions->fetchAllAssoc();
					$arrIDs = $objQuestions->fetchEach('id');
					
					if( $blnChunk )
					{
						$arrChunks = array_chunk( $arrQuestions, $this->objQuiz->questionsPerPage  );
						$arrQuestions = $arrChunks[$this->intCurrpage-1];
					}
					
					//Now we set the sequence key so we can retrieve the same random set the next time
					$this->CourseBuilder->Quiz->sequencekey = implode(',',$arrIDs);
				}
			}
		}
		else //Use default
		{
			$arrQuestions = $this->Database->prepare("SELECT * FROM tl_cb_quizquestion WHERE pid=? ORDER BY sorting ASC")
											->execute($this->id)
											->fetchAllAssoc();
		}
		
		return $arrQuestions;
		
	}


}
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

abstract class ModuleCB extends Module
{

	/**
	 * CourseBuilder object
	 * @var object
	 */
	protected $CourseBuilder;
		
	/**
	 * Cache properties
	 */
	protected $arrCourseCache = array();
	
	/**
	 * Certificate Template
	 * @var string
	 */
	protected $strCertificate = 'cb_certificate';
	
	/**
	 * Disable caching of the frontend page if this module is in use.
	 * Usefule to enable in a child classes.
	 * @var bool
	 */
	protected $blnDisableCache = false;


	public function __construct(Database_Result $objModule, $strColumn='main')
	{
		parent::__construct($objModule, $strColumn);

		if (TL_MODE == 'FE')
		{
			$this->import('CourseBuilder');

			if (FE_USER_LOGGED_IN)
			{
				$this->import('FrontendUser', 'User');
			}

			// Load CourseBuilder javascript and css
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/course_builder/html/course_builder.js';
			$GLOBALS['TL_CSS'][] = 'system/modules/course_builder/html/course_builder.css';

			// Disable caching for pages with certain modules (eg. Quiz)
			if ($this->blnDisableCache)
			{
				global $objPage;
				$objPage->cache = 0;
			}
		}
	}

	
	/**
	 * Include messages if enabled
	 * Download certificate PDF if applicable
	 * @return string
	 */
	public function generate()
	{
		if( $this->Input->get('certificate') )
		{
			$objCourseData = $this->Database->prepare("SELECT * FROM tl_cb_coursedata WHERE uniqid=? AND pass=1 AND status='complete'")->limit(1)->execute( $this->Input->get('certificate') );
			
			if( $objCourseData->numRows )
			{
				$arrCourse = $this->Database->prepare("SELECT * FROM tl_cb_course WHERE id=?")->limit(1)->execute( $objCourseData->courseid )->row();
				$arrUser = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->limit(1)->execute( $objCourseData->pid )->row();
				$arrQuizData = $this->Database->prepare("SELECT tstamp AS date_passed FROM tl_cb_quizdata WHERE pid=?")->limit(1)->execute( $objCourseData->id )->row();
				$this->generatePDF( 
					array_merge(
						(is_array($arrUser) ? $arrUser : array()),
						(is_array($arrCourse) ? $arrCourse : array()),
						(is_array($arrQuizData) ? $arrQuizData : array())
					) 
				);
			}
			else
			{
				$_SESSION['CB_ERROR']['pdferror'] = $GLOBALS['TL_LANG']['CB']['ERR']['no_certificate'];
			}
			
		}
		
		$strBuffer = parent::generate();
		
		// Prepend any messages to the module output
		if ($this->cb_includeMessages)
		{
			$strBuffer = CBFrontend::getCourseBuilderMessages() . $strBuffer;
		}
		
		return $strBuffer;
	}
	
	
	/**
	 * Retrieve an array of course data based on the user's groups
	 * @return array
	 */
	protected function getAvailableCourses()
	{
		//Return Cached Data
		if( count($this->arrCourseCache))
		{
			return $this->arrCourseCache;
		} 
		
		$arrData = array();
		$arrFinalIDs = array();
		$arrUserCourseIDs = array();
		$time = time();
		$arrUserData = deserialize($this->User->courses, true);
		
		$objCourse = $this->Database->execute("SELECT * FROM tl_cb_course WHERE (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1");
		
		//Check course group permissions
		while( $objCourse->next() )
		{
			$intId = $objCourse->id;
			
			$arrGroups = deserialize($objCourse->groups, true);
			
			if( $objCourse->protected && count($arrGroups))
			{
				foreach( $arrGroups as $group )
				{
					//If a course is memmber restricted we also check that they have it assigned
					// @todo - Figure out a better way to handle this to make it more flexible
					if( $this->User->isMemberOf($group) && in_array($objCourse->id, $arrUserData) ) 
					{
						$arrData[$intId] = $objCourse->row();
					}
				}
			}
			else //No restrictions
			{
				$arrData[$intId] = $objCourse->row();
			}
		}
		
		// HOOK: getAvailableCourses callback
		if (isset($GLOBALS['CB_HOOKS']['getAvailableCourses']) && is_array($GLOBALS['CB_HOOKS']['getAvailableCourses']))
		{
			foreach ($GLOBALS['CB_HOOKS']['getAvailableCourses'] as $callback)
			{
				$this->import($callback[0]);
				$arrData = $this->$callback[0]->$callback[1]($arrData, $this);
			}
		}
				
		$this->arrCourseCache = $arrData; //Cache data if checking attempts
		
		return $arrData;
	}


	
	/**
	 * Output a certificate PDF
	 * @todo - Add in more configuration options
	 * @param  array
	 * @param  string
	 * @param  bool
	 * @return object
	 */
	protected function generatePDF($arrData, $strTemplate=null, $blnOutput=true)
	{		
		if ($strTemplate)
		{
			$this->strCertificate = $strTemplate;
		}
		
		$this->loadLanguageFile('subdivisions');
		
		$arrCountry = (explode('-', $arrData['state']));
		
		$objTemplate = new BackendTemplate($this->strCertificate);
		$objTemplate->username = ucwords( $arrData['firstname'] . ' ' . $arrData['lastname'] );
		$objTemplate->address = strlen($arrData['company']) ? $arrData['company'] . '<br />' : '<br />';
		$objTemplate->address .= strlen($arrData['street']) ? ucwords(strtolower($arrData['street'])) . '<br />' : '<br />';
		$objTemplate->address .= strlen($arrData['city']) ? ucwords(strtolower($arrData['city'])) . ', ' : '';
		$objTemplate->address .= strlen($arrData['state']) ? ucwords(strtolower($GLOBALS['TL_LANG']['DIV'][strtolower($arrCountry[0])][$arrData['state']])) . ' ' : '';
		$objTemplate->address .= strlen($arrData['postal']) ? ucwords(strtolower($arrData['postal'])) . '<br />' : '';
		$objTemplate->course = $arrData['name'];
		$objTemplate->date_passed = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $arrData['date_passed']);

		// TCPDF configuration
		$l['a_meta_dir'] = 'ltr';
		$l['a_meta_charset'] = $GLOBALS['TL_CONFIG']['characterSet'];
		$l['a_meta_language'] = $GLOBALS['TL_LANGUAGE'];
		$l['w_page'] = 'page';

		// Include library
		require_once(TL_ROOT . '/system/config/tcpdf.php');
		require_once(TL_ROOT . '/plugins/tcpdf/tcpdf.php');

		// Create new PDF document
		$pdf = new TCPDF('L', 'mm', 'LETTER', true);

		// Set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);

		// Remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// Set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		// Set auto page breaks
		$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

		// Set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// Set some language-dependent strings
		$pdf->setLanguageArray($l);

		// Initialize document and add a page
		$pdf->AliasNbPages();

		// Set font
		//$pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);
		
		// Start new page
		$pdf->AddPage();

		// get current auto-page-break mode
		// disable auto-page-break
		$pdf->SetAutoPageBreak(false, 0);
		// set background image
		$img_file =  $arrData['certSRC'];
		
		//$file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false
		
		$pdf->Image($img_file, 0, 0, 277, 210, '', '', '', true, 300, '', false, false, 0, 'CM', false, false);
		// restore auto-page-break status
		$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
		// set the starting point for the page content
		$pdf->setPageMark();

		// Write the HTML content
		$pdf->writeHTML($objTemplate->parse(), true, 0, true, 0);
		
		if ($blnOutput)
		{
			// Close and output PDF document
			$pdf->lastPage();
			$pdf->Output(standardize(ampersand($strInvoiceTitle, false), true) . '.pdf', 'D');
	
			// Stop script execution
			exit;
		}
		
		return $pdf;
	}

	
}

<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Winans Creative 2009, Intelligent Spark 2010, iserv.ch GmbH 2010
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class CBFrontend extends Frontend
{

	/**
	 * Isotope object
	 * @var object
	 */
	protected $CourseBuilder;


	public function __construct()
	{
		parent::__construct();

		$this->import('CourseBuilder');
	}
	
	/**
	 * Callback function for generateBreadcrumb Hook
	 * @param array
	 * @return array
	 */
	public function generateBreadcrumb( $arrItems )
	{
		if($this->Input->get('course'))
		{
		
			//Get the last element
			$arrLast = array_pop($arrItems);
			
			$arrData = array();
			$time = time();
			$strAlias = $this->Input->get('course');
			
			if(is_numeric($strAlias))
			{
				$objCourse = $this->Database->prepare("SELECT * FROM tl_cb_course WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1")->limit(1)->execute($strAlias);
			}
			else
			{
				$objCourse = $this->Database->prepare("SELECT * FROM tl_cb_course WHERE alias=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1")->limit(1)->execute($strAlias);
			}
	
			$arrData = $objCourse->row();
								
			$arrLast['title'] = $arrLast['link'] = $arrData['name'];
			array_push($arrItems, $arrLast);
		}
				
		return $arrItems;
	
	}
	
	/**
	 * Retrieve an array of course data based on the alias and optionally restrict to an array of available course IDs
	 * @param  string
	 * @return array
	 */
	public static function getCourseByAlias($strAlias, $arrIds=array())
	{
		$arrData = array();
		$blnValid = true;
		$time = time();
		$Database = Database::getInstance();
		
		if(is_numeric($strAlias))
		{
			$objCourse = $Database->prepare("SELECT * FROM tl_cb_course WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1")->limit(1)->execute($strAlias);
		}
		else
		{
			$objCourse = $Database->prepare("SELECT * FROM tl_cb_course WHERE alias=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1")->limit(1)->execute($strAlias);
		}
		
		if(count($arrIds))
		{
			if(!in_array($objCourse->id, $arrIds))
			{
				$blnValid = false;
			}
		}

		if($objCourse->numRows && $blnValid )
		{
			$arrData = $objCourse->row();
		}
		
		return $arrData;
		
	}
	
	
	/**
	 * Retrieve an array of course element data based on the alias
	 * @param  string
	 * @param  string
	 * @return array
	 */
	public static function getCourseElementByAlias($strAlias, $strType)
	{
		$arrData = array();
		$strTable = $GLOBALS['CB_ELEMENT'][$strType]['table'];
		$Database = Database::getInstance();
		
		if(is_numeric($strAlias))
		{
			$objElement = $Database->prepare("SELECT * FROM {$strTable} WHERE id=?")->limit(1)->execute($strAlias);
		}
		else
		{
			$objElement = $Database->prepare("SELECT * FROM {$strTable} WHERE alias=?")->limit(1)->execute($strAlias);
		}
		
		if($objElement->numRows)
		{
			$arrData = $objElement->row();
		}
		return $arrData;
	}
	
	
	/**
	 * Retrieve multiple course pages by ID.
	 * @param  array
	 * @return array
	 */
	public static function getPages($arrIds, $strTable)
	{
		if (!is_array($arrIds) || !count($arrIds))
			return array();

		$arrPages = array();
		$Database = Database::getInstance();
		$objPageData = $Database->execute("SELECT * FROM {$strTable} WHERE id IN (" . implode(',', $arrIds) . ")");
		
		while( $objPageData->next() )
		{
			$arrSegments = $this->getSegments($objPageData);

			if (is_array($arrSegments))
			{
				$arrPages[] = $arrSegments;
			}
		}

		return $arrPages;
	}

	/**
	 * Return all error, confirmation and info messages as HTML.
	 * @return string
	 */
	public static function getCourseBuilderMessages()
	{
		$strMessages = '';
		$arrGroups = array('CB_ERROR', 'CB_CONFIRM', 'CB_INFO');

		foreach ($arrGroups as $strGroup)
		{
			if (!is_array($_SESSION[$strGroup]))
			{
				continue;
			}

			$strClass = strtolower($strGroup);

			foreach ($_SESSION[$strGroup] as $strMessage)
			{
				$strMessages .= sprintf('<p class="%s">%s</p>%s', $strClass, $strMessage, "\n");
			}

			$_SESSION[$strGroup] = array();
		}

		$strMessages = trim($strMessages);

		if (strlen($strMessages))
		{
			$strMessages = "\n\n<!-- indexer::stop -->\n<div class=\"cb_message\">\n$strMessages\n</div>\n<!-- indexer::continue -->";
		}

		return $strMessages;
	}
	
	

	
	
}
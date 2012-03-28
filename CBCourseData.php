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

abstract class CBCourseData extends Model
{

	
	/**
	 * Name of the child table
	 * @var string
	 */
	protected $ctable;

	/**
	 * Define if data should be threaded as "locked", eg. we are viewing a report
	 * Public so that we can freeze the ability to modify data
	 */
	public $blnLocked = false;

	/**
	 * Cache all segments for speed improvements
	 * @var array
	 */
	protected $arrSegments;

	/**
	 * CourseBuilder object
	 * @var object
	 */
	protected $CourseBuilder;

	/**
	 * Configuration
	 * @var array
	 */
	protected $arrSettings = array();

	/**
	 * Cache __get() data until __set() is used
	 * @var array
	 */
	protected $arrCache = array();

	/**
	 * Record has been modified
	 * @var bool
	 */
	protected $blnModified = false;
	
	
	public function __construct()
	{
		parent::__construct();

		// Do not use __destruct, because Database object might be destructed first (see http://dev.contao.org/issues/2236)
		if (!$this->blnLocked)
		{
			register_shutdown_function(array($this, 'saveDatabase'));
		}
	}


	/**
	 * Shutdown function to save data if modified
	 */
	public function saveDatabase()
	{
		if (!$this->blnLocked) //Object could be locked at some point after construction
		{	
			$this->save();
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
		if (!isset($this->arrCache[$strKey]))
		{
			switch( $strKey )
			{
				case 'table':
					return $this->strTable;
					break;

				case 'ctable':
					return  $this->ctable;
					break;

				case 'id':
				case 'pid':
				case 'lastpage':
				case 'maxpage':
					return (int)$this->arrData[$strKey];
					break;

				default:
					if (array_key_exists($strKey, $this->arrData))
					{
						return deserialize($this->arrData[$strKey]);
					}
					else
					{
						return deserialize($this->arrSettings[$strKey]);
					}
					break;
			}
		}
		return $this->arrCache[$strKey];
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
			$this->arrSegments = null;
		}

		// If there is a database field for that key, we store it there
		if (array_key_exists($strKey, $this->arrData) || $this->Database->fieldExists($strKey, $this->strTable))
		{
			$this->arrData[$strKey] = $varValue;
			$this->arrCache = array();
			$this->blnModified = true;
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
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		if (isset($this->arrData[$strKey]) || isset($this->arrSettings[$strKey]))
			return true;
		
		return false;
	}


	/**
	 * Load settings from database field
	 * @param  string
	 * @param  mixed
	 * @return boolean
	 */
	public function findBy($strRefField, $varRefId)
	{
		if (parent::findBy($strRefField, $varRefId))
		{
			$this->arrSettings = deserialize($this->arrData['settings'], true);

			return true;
		}

		return false;
	}


	/**
	 * Load settings from database field
	 * @param  object
	 * @param  string
	 * @param  string
	 */
	public function setFromRow(Database_Result $resResult, $strTable, $strRefField)
	{
		parent::setFromRow($resResult, $strTable, $strRefField);

		$this->arrSettings = deserialize($this->arrData['settings'], true);
	}
	
	
	/**
	 * Update database with latest course data
	 */
	public function save($blnForceInsert=false)
	{
		if ($this->blnModified)
		{
			$this->arrData['tstamp'] = time();
			$this->arrData['settings'] = serialize($this->arrSettings);
		}

		if ($this->blnRecordExists && $this->blnModified && !$blnForceInsert)
		{
			return parent::save($blnForceInsert);
		}
		elseif ((!$this->blnRecordExists && $this->blnModified) || $blnForceInsert)
		{
			$this->findBy('id', parent::save($blnForceInsert));

			return $this->id;
		}
	}


	/**
	 * Transfer data from another submission to this one (eg. from non-logged in to logged in)
	 *
	 * @todo: implement addToSubmission (and removeFromSubmission) hooks!
	 */
	public function transferFromSubmission(CBCourseData $objData, $strElField, $blnDuplicate=true)
	{
		if (!$this->blnRecordExists)
		{
			$this->save(true);
		}

		$time = time();
		$arrIds = array();
	 	$objOldItems = $this->Database->execute("SELECT * FROM {$objData->ctable} WHERE pid={$objData->id}");

		while( $objOldItems->next() )
		{
			$objNewItems = $this->Database->execute("SELECT * FROM {$this->ctable} WHERE pid={$objData->id} AND $strElField={$objOldItems->$strElField} AND courseid='{$objOldItems->courseid}'");

			// Data does not exist in this submission, we don't duplicate and are on the same table. Simply change parent id.
			if (!$objNewItems->numRows && !$blnDuplicate && $this->ctable == $objData->ctable)
			{
				$this->Database->query("UPDATE {$this->ctable} SET tstamp=$time, pid={$this->id} WHERE id={$objOldItems->id}");
				$arrIds[] = $objOldItems->id;
			}

			// Duplicate all existing rows to target table
			else
			{
				$arrSet = array('pid'=>$this->id, 'tstamp'=>$time);

				foreach( $objOldItems->row() as $k=>$v )
				{
					if (in_array($k, array('id', 'pid', 'tstamp')))
						continue;

					if ($this->Database->fieldExists($k, $this->ctable))
					{
						$arrSet[$k] = $v;
					}
				}

				$arrIds[] = $this->Database->prepare("INSERT INTO {$this->ctable} %s")->set($arrSet)->executeUncached()->insertId;
			}
		}

		if (count($arrIds))
		{
			$this->modified = true;
		}

		return $arrIds;
	}
	
	/**
	 * Must be implemented by child classes
	 * returns incorrect answers
	 */
	abstract public function getWrong();

	
}
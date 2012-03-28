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

/**
 * Load language file and datacontainer for tl_content
 */
$this->loadDataContainer('tl_content');
$this->loadLanguageFile('tl_content');

/**
 * Table tl_cb_lessonsegment
 */
 
//Set the tl_content DCA to tl_cb_lessonsegment
$GLOBALS['TL_DCA']['tl_cb_lessonsegment'] = $GLOBALS['TL_DCA']['tl_content']; 

//Overwrite sections of DCA for tl_cb_lessonsegment

// Config
$GLOBALS['TL_DCA']['tl_cb_lessonsegment']['config'] = array
(
	'dataContainer'               => 'Table',
	'ptable'                      => 'tl_cb_lessonpage',
	'enableVersioning'            => true,
	'onload_callback' => array
	(
		array('tl_cb_lessonsegment', 'setDBFields')
	)
);

// List
$GLOBALS['TL_DCA']['tl_cb_lessonsegment']['list'] = array
(
	'sorting' => array
	(
		'mode'                    => 4,
		'filter'                  => true,
		'fields'                  => array('sorting'),
		'panelLayout'             => 'search,filter,limit',
		'headerFields'            => array('title', 'tstamp', 'description'),
		'child_record_callback'   => array('tl_cb_lessonsegment', 'addCteType')
	),
	'global_operations' => array
	(
		'all' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
			'href'                => 'act=select',
			'class'               => 'header_edit_all',
			'attributes'          => 'onclick="Backend.getScrollOffset();"'
		)
	),
	'operations' => array
	(
		'edit' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_cb_lessonsegment']['edit'],
			'href'                => 'act=edit',
			'icon'                => 'edit.gif'
		),
		'copy' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_cb_lessonsegment']['copy'],
			'href'                => 'act=paste&amp;mode=copy',
			'icon'                => 'copy.gif',
			'attributes'          => 'onclick="Backend.getScrollOffset();"'
		),
		'cut' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_cb_lessonsegment']['cut'],
			'href'                => 'act=paste&amp;mode=cut',
			'icon'                => 'cut.gif',
			'attributes'          => 'onclick="Backend.getScrollOffset();"'
		),
		'delete' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_cb_lessonsegment']['delete'],
			'href'                => 'act=delete',
			'icon'                => 'delete.gif',
			'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			'button_callback'     => array('tl_cb_lessonsegment', 'deleteElement')
		),
		'toggle' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_cb_lessonsegment']['toggle'],
			'icon'                => 'visible.gif',
			'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
			'button_callback'     => array('tl_cb_lessonsegment', 'toggleIcon')
		),
		'show' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_cb_lessonsegment']['show'],
			'href'                => 'act=show',
			'icon'                => 'show.gif'
		)
	)
);


/**
 * Class tl_cb_lessonsegment
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Winans Creative 2011
 * @author     Blair Winans <blair@winanscreative.com>
 * @package    CourseBuilder
 */
class tl_cb_lessonsegment extends Backend
{
	
	public function __construct()
	{
		parent::__construct();

		$this->import('BackendUser', 'User');
		$this->import('CourseBuilder');
	}
	
	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		return sprintf('<div class="list_icon" style="background-image:url(\'system/modules/survey_ce/html/images/question.png\');">%s</div>', $label);
	}
	
	
	/**
	 * Use the Repository DatabaseInstaller Class to set tl_content fields to tl_cb_lessonsegment
	 * @return array
	 * @todo - Simplify this by just checking the fields in tl_content. Does not need to be nearly so long.
	 */
	public function setDbFields()
	{
		$this->import('Database');
		$this->import('CBDatabase');
				
		$arrContentFields = array();
		$arrSegmentFields = array();
		
		$arrContentFieldData = $this->Database->listFields('tl_content');
		foreach( $arrContentFieldData as $field )
		{
			$strLength = $field['length'] ? '(' . $field['length'] . ')' : '';
			$strNull = strlen($field['null']) ? ' ' . $field['null'] : '';
			$strDefault = !is_null($field['default']) ? " default '" . $field['default'] . "'" : "";
			$strAttributes = $field['attributes'] ? ' ' . $field['attributes'] : '';
			$strExtra = strlen($field['extra']) ? ' ' . $field['extra'] : '';
			if(!$field['index']) //Skip indexes
			{
				$arrContentFields[$field['name']] = $field['type'] . $strLength . $strAttributes . $strNull . $strDefault . $strExtra;
			}
		}
		
		$arrSegmentFieldData = $this->Database->listFields('tl_cb_lessonsegment');
		foreach( $arrSegmentFieldData as $field )
		{
			$strLength = $field['length'] ? '(' . $field['length'] . ')' : '';
			$strNull = strlen($field['null']) ? ' ' . $field['null'] : '';
			$strDefault = !is_null($field['default']) ? " default '" . $field['default'] . "'" : "";
			$strAttributes = $field['attributes'] ? ' ' . $field['attributes'] : '';
			$strExtra = strlen($field['extra']) ? ' ' . $field['extra'] : '';
			if(!$field['index']) //Skip indexes
			{
				$arrSegmentFields[$field['name']] = $field['type'] . $strLength . $strAttributes . $strNull . $strDefault . $strExtra;
			}
		}
		
		//Add fields
		foreach($arrContentFields as $field=>$sql)
		{
			if(!$this->Database->fieldExists($field, 'tl_cb_lessonsegment'))
			{
				$this->Database->query(sprintf("ALTER TABLE tl_cb_lessonsegment ADD %s %s", $field, $sql));
				
			}
			$this->CBDatabase->add($field, $sql);
		}
		
		//Remove fields
		$arrDiff = array_diff(array_keys($arrSegmentFields), array_keys($arrContentFields));
		if(count($arrDiff))
		{
			foreach($arrDiff as $field)
			{
				$this->CBDatabase->delete($field);
			}
		}	

	
	}
	
	/**
	 * Add the type of content element
	 * @param array
	 * @return string
	 */
	public function addCteType($arrRow)
	{
		$key = $arrRow['invisible'] ? 'unpublished' : 'published';

		return '
<div class="cte_type ' . $key . '">' . $GLOBALS['TL_LANG']['CTE'][$arrRow['type']][0] . (($arrRow['type'] == 'alias') ? ' ID ' . $arrRow['cteAlias'] : '') . ($arrRow['protected'] ? ' (' . $GLOBALS['TL_LANG']['MSC']['protected'] . ')' : ($arrRow['guests'] ? ' (' . $GLOBALS['TL_LANG']['MSC']['guests'] . ')' : '')) . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">
' . $this->CourseBuilder->getCBContentElement($arrRow['id'], 'tl_cb_lessonsegment') . '
</div>' . "\n";
	}
	
	/**
	 * Return the delete content element button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteElement($row, $href, $label, $title, $icon, $attributes)
	{
		$objElement = $this->Database->prepare("SELECT id FROM tl_content WHERE cteAlias=? AND type=?")
									 ->limit(1)
									 ->execute($row['id'], 'alias');

		return $objElement->numRows ? $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ' : '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();
	
		$this->createInitialVersion('tl_content', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_content SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_content', $intId);
	}

	
} 
 
 
 
 ?>
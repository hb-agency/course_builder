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
 

/**
 * Table tl_cb_course
 */
$GLOBALS['TL_DCA']['tl_cb_course'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_cb_course', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('name'),
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'label'                   => '<h3>%s</h3>',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_course']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_course']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_course']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_course']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_cb_course', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_course']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('protected'),
		'default'                     => '{title_legend},name,alias;{elements_legend},courseelements,coursenavmodule;{cert_legend},certSRC;{protected_legend:hide},protected;{expert_legend:hide},printable,cssID,space;{publish_legend},published,start,stop'
	),
	
	// Subpalettes
	'subpalettes' => array
	(
		'protected'                   => 'groups',
	),

	// Fields
	'fields' => array
	(

		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['name'],
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_cb_course', 'generateAlias')
			)

		),
		'courseelements' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['courseelements'],
			'inputType'               => 'coursebuilderwizard',
			'eval'                    => array('doNotCopy'=>true, 'includeBlankOption'=>true)
		),
		'coursenavmodule' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['coursenavmodule'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_course', 'getNavmods'),
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
		),
		'certSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['certSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true, 'tl_class'=>'clr')
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['groups'],
			'exclude'                 => true,
			'filter'				  => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'published' => array
		(
			'exclude'                 => true,
			'filter'				  => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['published'],
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'start' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['start'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'stop' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_course']['stop'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		)
	)
);

/**
 * Class tl_cb_course
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Winans Creative 2011
 * @author     Blair Winans <blair@winanscreative.com>
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Adam Fisher <adam@winanscreative.com>
 */

class tl_cb_course extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	/**
	 * Check permissions to edit table tl_cb_course
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->courses) || count($this->User->courses) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->courses;
		}

		$GLOBALS['TL_DCA']['tl_cb_course']['list']['sorting']['root'] = $root;

		// Check permissions to add forms
		if (!$this->User->hasAccess('create', 'coursep'))
		{
			$GLOBALS['TL_DCA']['tl_cb_course']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array($this->Input->get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_cb_course']) && in_array($this->Input->get('id'), $arrNew['tl_cb_course']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT courses, coursep FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrCoursep = deserialize($objUser->coursep);

							if (is_array($arrCoursep) && in_array('create', $arrCoursep))
							{
								$arrCourses = deserialize($objUser->courses);
								$arrCourses[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET forms=? WHERE id=?")
											   ->execute(serialize($arrCourses), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT courses, coursep FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrCoursep = deserialize($objGroup->coursep);

							if (is_array($arrCoursep) && in_array('create', $arrCoursep))
							{
								$arrCourses = deserialize($objGroup->courses);
								$arrCourses[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET courses=? WHERE id=?")
											   ->execute(serialize($arrCourses), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->courses = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'coursep')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' course ID "'.$this->Input->get('id').'"', 'tl_cb_course checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'coursep'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' courses', 'tl_cb_quiz checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}
	
	/**
	 * Autogenerate an article alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->name);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_cb_course WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
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

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
	
	/**
	 * Toggle the visibility of a course
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		$this->createInitialVersion('tl_cb_course', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_cb_course']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_cb_course']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_cb_course SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_cb_course', $intId);
	}
	
	/**
	 * Get navigation modules
	 * @return array
	 */
	public function getNavmods()
	{
		$arrReturn = array(''=>'-');
		
		$objResult = $this->Database->prepare("SELECT * FROM tl_module WHERE type='cb_coursenavigation'")->executeUncached();
		
		if ($objResult->numRows)
		{
			while ($objResult->next())
			{
				$arrReturn[$objResult->id] = $objResult->name;
			}
		}
		
		return $arrReturn;
	}
	
}

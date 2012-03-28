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
 * Table tl_cb_lesson
 */
$GLOBALS['TL_DCA']['tl_cb_lesson'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_cb_lessonpage'),
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_cb_lesson', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_cb_lesson', 'addIcon')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_lesson']['edit'],
				'href'                => 'table=tl_cb_lessonpage',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_lesson']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_lesson']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_lesson']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('limit_groups'),
		'default'                 => '{title_legend},name,alias,author,description,final,reviewable;{activation_legend},online_start,online_end;{access_legend},usecookie,limit_groups;{texts_legend},introduction,success;{expert_legend},cssID',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'limit_groups'                => 'allowed_groups'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'insertTag'=>true, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_cb_lesson', 'generateAlias')
			)

		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('tl_class'=>'w50')
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['description'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;','tl_class'=>'clr')
		),
		'introduction' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['introduction'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE')
		),
		'success' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['success'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE')
		),
		'online_start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['online_start'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'online_end' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['online_end'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'final' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['final'],
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'reviewable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['reviewable'],
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'limit_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['limit_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'clr')
		),
		'allowed_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['allowed_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'usecookie' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['usecookie'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'allowback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['allowback'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'explanation'             => 'jumpTo',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true, 'tl_class'=>'clr')
		),
		'show_title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['show_title'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'show_cancel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['show_cancel'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_lesson']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
	)
);


/**
 * Class tl_cb_lesson
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_cb_lesson extends Backend
{
	/**
	 * Load database object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		
		// somehow dirty patch to allow going back if someone clicks back on a survey segment list
		if (strpos($this->getReferer(ENCODE_AMPERSANDS), 'tl_cb_segment'))
		{
			if (preg_match("/id=(\\d+)/", $this->getReferer(ENCODE_AMPERSANDS), $matches))
			{
				$page_id = $matches[1];
				$survey_id = $this->Database->prepare("SELECT pid FROM tl_cb_page WHERE id=?")
					->execute($page_id)
					->fetchEach('pid');
				if ($survey_id[0] > 0)
				{
					$this->redirect($this->addToUrl('table=tl_cb_page&amp;id=' . $survey_id[0]));
				}
			}
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

		$objAlias = $this->Database->prepare("SELECT id FROM tl_cb_lesson WHERE id=? OR alias=?")
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
	 * Check permissions to edit table tl_cb_lesson
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->lessons) || count($this->User->lessons) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->lessons;
		}

		$GLOBALS['TL_DCA']['tl_cb_lesson']['list']['sorting']['root'] = $root;

		// Check permissions to add forms
		if (!$this->User->hasAccess('create', 'lessonp'))
		{
			$GLOBALS['TL_DCA']['tl_cb_lesson']['config']['closed'] = true;
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

					if (is_array($arrNew['tl_cb_lesson']) && in_array($this->Input->get('id'), $arrNew['tl_cb_lesson']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT lessons, lessonp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrLessonp = deserialize($objUser->lessonp);

							if (is_array($arrLessonp) && in_array('create', $arrLessonp))
							{
								$arrLessons = deserialize($objUser->lessons);
								$arrLessons[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET lessons=? WHERE id=?")
											   ->execute(serialize($arrLessons), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT lessons, lessonp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrLessonp = deserialize($objGroup->lessonp);

							if (is_array($arrLessonp) && in_array('create', $arrLessonp))
							{
								$arrLessons = deserialize($objGroup->lessons);
								$arrLessons[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET lessons=? WHERE id=?")
											   ->execute(serialize($arrLessons), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->lessons = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'lessonp')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' lesson ID "'.$this->Input->get('id').'"', 'tl_cb_lesson checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'lessonp'))
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
					$this->log('Not enough permissions to '.$this->Input->get('act').' lessons', 'tl_cb_lesson checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		return sprintf('<div class="list_icon" style="background-image:url(\'system/modules/course_builder/html/icon-lessons.gif\');">%s</div>', $label);
	}
	
}

?>
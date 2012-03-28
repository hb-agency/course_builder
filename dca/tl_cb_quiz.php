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
 * Table tl_cb_quiz
 */
$GLOBALS['TL_DCA']['tl_cb_quiz'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_cb_quizpage'),
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_cb_quiz', 'checkPermission')
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
			'label_callback'          => array('tl_cb_quiz', 'addIcon')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quiz']['edit'],
				'href'                => 'table=tl_cb_quizpage',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quiz']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quiz']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quiz']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('limit_groups', 'randomize', 'canretake'),
		'default'                 => '{title_legend},name,alias,author,passing_score,plesson,final,reviewable,canretake,randomize,description;{activation_legend},online_start,online_end;{access_legend},usecookie,limit_groups;{texts_legend},introduction,success,failure;{expert_legend},cssID',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'limit_groups'              => 'allowed_groups',
		'randomize'					=> 'questionsPerPage',
		'canretake'					=> 'retakeuntilpass',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'insertTag'=>true, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_cb_quiz', 'generateAlias')
			)

		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('tl_class'=>'w50')
		),
		'passing_score' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['passing_score'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'prcnt', 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'plesson' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['plesson'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_quiz', 'getLessons'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'final' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['final'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'reviewable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['reviewable'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'canretake' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['canretake'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'w50 m12')
		),
		'retakeuntilpass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['retakeuntilpass'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['description'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;','tl_class'=>'clr')
		),
		'introduction' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['introduction'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE')
		),
		'success' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['success'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE')
		),
		'failure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['failure'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte' => 'tinyMCE')
		),
		'online_start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['online_start'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'online_end' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['online_end'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'limit_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['limit_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'clr')
		),
		'allowed_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['allowed_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'usecookie' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['usecookie'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'randomize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['randomize'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'w50 m12')
		),
		'questionsPerPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['questionsPerPage'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'allowback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['allowback'],
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'explanation'             => 'jumpTo',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true, 'tl_class'=>'clr')
		),
		'show_title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['show_title'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'show_cancel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['show_cancel'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class' => 'w50 m12')
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quiz']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
	)
);


/**
 * Class tl_cb_quiz
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_cb_quiz extends Backend
{
	/**
	 * Load database object
	 * Import the back end user object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		
		// somehow dirty patch to allow going back if someone clicks back on a survey question list
		if (strpos($this->getReferer(ENCODE_AMPERSANDS), 'tl_cb_question'))
		{
			if (preg_match("/id=(\\d+)/", $this->getReferer(ENCODE_AMPERSANDS), $matches))
			{
				$page_id = $matches[1];
				$survey_id = $this->Database->prepare("SELECT pid FROM tl_cb_quizpage WHERE id=?")
					->execute($page_id)
					->fetchEach('pid');
				if ($survey_id[0] > 0)
				{
					$this->redirect($this->addToUrl('table=tl_cb_quizpage&amp;id=' . $survey_id[0]));
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

		$objAlias = $this->Database->prepare("SELECT id FROM tl_cb_quiz WHERE id=? OR alias=?")
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
	 * Check permissions to edit table tl_cb_quiz
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->quizzes) || count($this->User->quizzes) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->quizzes;
		}

		$GLOBALS['TL_DCA']['tl_cb_quiz']['list']['sorting']['root'] = $root;

		// Check permissions to add forms
		if (!$this->User->hasAccess('create', 'quizp'))
		{
			$GLOBALS['TL_DCA']['tl_cb_quiz']['config']['closed'] = true;
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

					if (is_array($arrNew['tl_cb_quiz']) && in_array($this->Input->get('id'), $arrNew['tl_cb_quiz']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT quizzes, quizp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrQuizp = deserialize($objUser->quizp);

							if (is_array($arrQuizp) && in_array('create', $arrQuizp))
							{
								$arrQuizzes = deserialize($objUser->quizzes);
								$arrQuizzes[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET quizzes=? WHERE id=?")
											   ->execute(serialize($arrQuizzes), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT quizzes, quizp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrQuizp = deserialize($objGroup->quizp);

							if (is_array($arrQuizp) && in_array('create', $arrQuizp))
							{
								$arrQuizzes = deserialize($objGroup->quizzes);
								$arrQuizzes[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET quizzes=? WHERE id=?")
											   ->execute(serialize($arrQuizzes), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->quizzes = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'quizp')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' quiz ID "'.$this->Input->get('id').'"', 'tl_cb_quiz checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'quizp'))
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
					$this->log('Not enough permissions to '.$this->Input->get('act').' quizzes', 'tl_cb_quiz checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Get lessons for parent lesson options
	 * @return array
	 */
	public function getLessons()
	{
		$arrReturn = array('0'=>'-');
		
		$objResult = $this->Database->prepare("SELECT * FROM tl_cb_lesson")->executeUncached();
		
		if ($objResult->numRows)
		{
			while ($objResult->next())
			{
				$arrReturn[$objResult->id] = $objResult->name;
			}
		}
		
		return $arrReturn;
	}


	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		return sprintf('<div class="list_icon" style="background-image:url(\'system/modules/course_builder/html/icon-quizzes.gif\');">%s</div>', $label);
	}
	
}

?>
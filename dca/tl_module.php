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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['cb_courselist']			= '{title_legend},name,headline,type;{redirect_legend},cb_reader_jumpTo,cb_productlist_jumpTo;{template_legend:hide},cb_includeMessages,cb_courselist_layout;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['cb_coursereader']			= '{title_legend},name,headline,type;{redirect_legend},cb_success_jumpTo,cb_failed_jumpTo;{template_legend:hide},cb_includeMessages,cb_coursereader_layout,cb_course_template,cb_lesson_template,cb_quiz_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['cb_courseprogressbar']		= '{title_legend},name,headline,type;{template_legend:hide},cb_includeMessages,cb_courseprogressbar_layout;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['cb_coursenavigation']		= '{title_legend},name,headline,type;{template_legend:hide},cb_includeMessages,cb_coursenavigation_layout;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


$GLOBALS['TL_DCA']['tl_module']['fields']['cb_course_template'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_course_template'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getCourseTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_quiz_template'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_quiz_template'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getQuizTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_lesson_template'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_lesson_template'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getLessonTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_courselist_layout'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_courselist_layout'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getCourseListTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_quizreader_layout'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_quizreader_layout'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getQuizReaderTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_lessonreader_layout'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_lessonreader_layout'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getLessonReaderTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_courseprogressbar_layout'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_courseprogressbar_layout'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getCourseProgressBarTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_coursenavigation_layout'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['cb_coursenavigation_layout'],
	'exclude'					=> true,
	'inputType'					=> 'select',
	'options_callback'			=> array('tl_module_cb', 'getCourseNavigationTemplates'),
	'eval'						=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_reader_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cb_reader_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_productlist_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cb_productlist_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_failed_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cb_failed_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_success_jumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cb_success_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'explanation'             => 'jumpTo',
	'eval'                    => array('fieldType'=>'radio'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cb_includeMessages'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cb_includeMessages'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'					  => array('tl_class'=>'w50', 'doNotCopy'=>true)
);

class tl_module_cb extends Backend
{

	/**
	 * Return course templates as array
	 * @param object
	 * @return array
	 */
	public function getCourseTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('cb_course_', $intPid);
	}
	
	/**
	 * Return quiz templates as array
	 * @param object
	 * @return array
	 */
	public function getQuizTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('cb_quiz_', $intPid);
	}
	
	/**
	 * Return lesson templates as array
	 * @param object
	 * @return array
	 */
	public function getLessonTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('cb_lesson_', $intPid);
	}
	
	/**
	 * Return course list templates as array
	 * @param object
	 * @return array
	 */
	public function getCourseListTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mod_courselist_', $intPid);
	}
	
	/**
	 * Return course progress bar templates as array
	 * @param object
	 * @return array
	 */
	public function getCourseProgressBarTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mod_courseprogressbar_', $intPid);
	}
	
	/**
	 * Return course navigation templates as array
	 * @param object
	 * @return array
	 */
	public function getCourseNavigationTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mod_coursenavigation_', $intPid);
	}
	
	/**
	 * Return quiz reader templates as array
	 * @param object
	 * @return array
	 */
	public function getQuizReaderTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mod_quizreader_', $intPid);
	}
	
	/**
	 * Return lesson reader templates as array
	 * @param object
	 * @return array
	 */
	public function getLessonReaderTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mod_lessonreader_', $intPid);
	}

}


?>
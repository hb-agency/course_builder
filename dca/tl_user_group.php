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
$GLOBALS['TL_DCA']['tl_user_group']['palettes']['default'] = str_replace('{alexf_legend}', '{course_legend},courses,coursep;{quiz_legend},quizzes,quizp;{lesson_legend},lessons,lessonp;{alexf_legend}', $GLOBALS['TL_DCA']['tl_user_group']['palettes']['default']);


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user_group']['fields']['courses'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['courses'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_cb_course.name',
	'eval'                    => array('multiple'=>true)
);
		
$GLOBALS['TL_DCA']['tl_user_group']['fields']['coursep'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['coursep'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'edit', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);
$GLOBALS['TL_DCA']['tl_user_group']['fields']['quizzes'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['quizzes'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_cb_quiz.name',
	'eval'                    => array('multiple'=>true)
);
		
$GLOBALS['TL_DCA']['tl_user_group']['fields']['quizp'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['quizp'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'edit', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);
$GLOBALS['TL_DCA']['tl_user_group']['fields']['lessons'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['lessons'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_cb_lesson.name',
	'eval'                    => array('multiple'=>true)
);
		
$GLOBALS['TL_DCA']['tl_user_group']['fields']['lessonp'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['lessonp'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'edit', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);



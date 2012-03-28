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
 * CourseBuilder Version
 */
@define('CB_VERSION', '1.0.0');
@define('CB_BUILD', 'beta');


/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['coursebuilder'] = array
(
	'cb_courselist'			=> 'ModuleCBCourseLister',
	'cb_coursereader'		=> 'ModuleCBCourseReader',
	'cb_courseprogressbar'	=> 'ModuleCBCourseProgressBar',
	'cb_coursenavigation'	=> 'ModuleCBCourseNavigation',
);


/**
 * Back end modules
 */
if (!is_array($GLOBALS['BE_MOD']['coursebuilder']))
{
	array_insert($GLOBALS['BE_MOD'], 1, array('coursebuilder' => array()));
}
array_insert($GLOBALS['BE_MOD']['coursebuilder'], 0, array
(
	'cbcourses' => array
	(
		'tables' => array('tl_cb_course', 'tl_cb_quiz', 'tl_cb_lesson'),
		'icon'	 => 'system/modules/course_builder/html/icon-courses.gif',
	),
	'cbquizzes' => array(
		'tables' => array('tl_cb_quiz', 'tl_cb_quizpage', 'tl_cb_quizquestion'),
		'icon' => 'system/modules/course_builder/html/icon-quizzes.gif',
	),
	'cblessons' => array
	(
		'tables' => array('tl_cb_lesson', 'tl_cb_lessonpage', 'tl_cb_lessonsegment'),
		'icon'	 => 'system/modules/course_builder/html/icon-lessons.gif',
	)
));



/**
 * CB Question Types
 */
$GLOBALS['CB_QUESTION']['multiplechoice'] = 'FormQuizQuestionMultipleChoice';
$GLOBALS['CB_SEGMENT']['plaintext'] = 'CBLessonSegmentPlainText';


/**
 * CB Course Element Types
 */
$GLOBALS['CB_ELEMENT'] = array
(
	'quiz'	=> array
	(
		'class'		=>	'CBQuizElement',
		'table'		=>	'tl_cb_quiz',
		'jumpTo'	=>	'cb_quiz_jumpTo',
		'template'	=>	'cb_quiz_template',
		'data'		=>	array(
								'class' =>	'CBQuizData',
								'table'	=>	'tl_cb_quizdata',
								'column'	=>	'quizid'
							)
	),
	'lesson'	=> array
	(
		'class'		=>	'CBLessonElement',
		'table'		=>	'tl_cb_lesson',
		'jumpTo'	=>	'cb_lesson_jumpTo',
		'template'	=>	'cb_lesson_template',
		'data'		=>	array(
								'class' =>	'CBLessonData',
								'table'	=>	'tl_cb_lessondata',
								'column'	=>	'lessonid'
							)
	),
	
);

/**
 * CB Default Config
 */
$GLOBALS['TL_CONFIG']['cb_dataTimeout'] = 2592000;

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generateBreadcrumb'][] = array('CBFrontend', 'generateBreadcrumb');

?>
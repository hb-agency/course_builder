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
 * Backend Modules
 */
$GLOBALS['TL_LANG']['MOD']['coursebuilder']			= 'Course Builder';
$GLOBALS['TL_LANG']['MOD']['cbcourses']				= array('Courses','Assemble lessons and quizzes into course bundles.');
$GLOBALS['TL_LANG']['MOD']['cbquizzes']				= array('Quiz Builder','Create and assemble questions into quizzes for use in a course.');
$GLOBALS['TL_LANG']['MOD']['cblessons']				= array('Lesson Builder','Create and assemble lesson assets into a linear lesson plan for use in a course.');


/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['coursebuilder']			= 'Course Builder';
$GLOBALS['TL_LANG']['FMD']['cb_courselist']			= array('Course Lister', 'Lists multiple courses and their associated elements.');
$GLOBALS['TL_LANG']['FMD']['cb_coursereader']		= array('Course Reader', 'Provides access to a frontend course.');
$GLOBALS['TL_LANG']['FMD']['cb_courseprogressbar']	= array('Course Progress Bar', 'Displays the progress of a course on the frontend.');
$GLOBALS['TL_LANG']['FMD']['cb_coursenavigation']	= array('Course Navigation', 'Displays the sections of a course on the frontend as a nevigation module.');
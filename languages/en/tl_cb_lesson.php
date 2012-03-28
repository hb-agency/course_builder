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
 * @author     Includes code from lesson_ce module from Helmut Schottmüller <typolight@aurealis.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_cb_lesson']['name']   = array('Title', 'Please enter the lesson title.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['alias']       = array('Lesson alias', 'The lesson alias is a unique reference to the lesson which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['author']   = array('Author', 'Please enter the author name.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['description']   = array('Description', 'Please enter a lesson description.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['tstamp']   = array('Last change', 'Date and time of the last change.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['introduction']     = array('Introduction', 'Please enter a lesson introduction. The introduction will be shown on the start page of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['success']     = array('Success Message', 'Please enter a success message. The success message will be shown upon successful completion of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['failure']     = array('Failure Message', 'Please enter a failure message. The failure message will be shown upon failed completion of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['online_start']      = array('Show from', 'Do not show the lesson on the website before this day.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['online_end']       = array('Show until', 'Do not show the page on the website after this day.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['limit_groups']       = array('Limit members', 'Limit the access to selected member groups.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['allowed_groups']       = array('Member groups', 'Choose the member groups that should be able to participate in the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['access']     = array('Lesson access', 'Choose the appropriate access method for the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['access']['explanation']     = 'Please choose the appropriate access method for the lesson.';
$GLOBALS['TL_LANG']['tl_cb_lesson']['access']['anon']     = array('Anonymized access','Everyone can participate in the lesson, even more than once. Access is anonymized. Lesson results cannot be tracked back to a participant.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['access']['anoncode']     = array('Anonymized access with TAN code','Only participants with a valid TAN code can participate in the lesson. A lesson can be finished only once per TAN code. Access is anonymized. Lesson result can be tracked back to each TAN.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['access']['nonanoncode']     = array('Personalized access','Only participants with a valid frontend login can participate in the lesson. A lesson can be finished only once per participant. Lesson results can be tracked back to each participant.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['usecookie']     = array('Remember participants', 'Remembers a lesson participant using a cookie.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['show_title']     = array('Show lesson title', 'Always show the lesson title on top of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['show_cancel']     = array('Show cancel', 'Always show an <strong>Exit this lesson</strong> command on top of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['allowback']     = array('Show "Previous" button', 'Shows a "Previous" button in the lesson navigation to go back to the previous page.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['jumpTo']     = array('Redirect to page', 'Select a page to redirect the lesson after it was finished.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['cssID']        = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['final']   = array('Is this lesson the final lesson?', 'Check here if this lesson determines a pass/fail of the course.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['reviewable']   = array('Is this lesson reviewable?', 'Check here if this lesson permits going back and reviewing more than once.');

/**
* Legends
*/
$GLOBALS['TL_LANG']['tl_cb_lesson']['head_legend']    = 'Head settings';
$GLOBALS['TL_LANG']['tl_cb_lesson']['title_legend']    = 'Title and description';
$GLOBALS['TL_LANG']['tl_cb_lesson']['activation_legend']    = 'Activation';
$GLOBALS['TL_LANG']['tl_cb_lesson']['access_legend']    = 'Access';
$GLOBALS['TL_LANG']['tl_cb_lesson']['texts_legend']    = 'Statements';
$GLOBALS['TL_LANG']['tl_cb_lesson']['misc_legend']    = 'General settings';
$GLOBALS['TL_LANG']['tl_cb_lesson']['expert_legend']    = 'Expert settings';

/**
* Buttons
*/
$GLOBALS['TL_LANG']['tl_cb_lesson']['new']    = array('New lesson', 'Create a new lesson');
$GLOBALS['TL_LANG']['tl_cb_lesson']['show']   = array('Lesson details', 'Show the details of lesson %s');
$GLOBALS['TL_LANG']['tl_cb_lesson']['edit']   = array('Edit lesson', 'Edit lesson ID %s');
$GLOBALS['TL_LANG']['tl_cb_lesson']['edit_']   = array('You cannot edit the lesson', 'Lesson ID %s is locked. Participant results already exist.');
$GLOBALS['TL_LANG']['tl_cb_lesson']['copy']   = array('Duplicate lesson', 'Duplicate lesson ID %s');
$GLOBALS['TL_LANG']['tl_cb_lesson']['delete'] = array('Delete lesson', 'Delete lesson ID %s');

?>
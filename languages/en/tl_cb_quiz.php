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
 * @author     Includes code from quiz_ce module from Helmut Schottmüller <typolight@aurealis.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_cb_quiz']['name']   = array('Title', 'Please enter the quiz title.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['alias']       = array('Quiz alias', 'The quiz alias is a unique reference to the quiz which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['author']   = array('Author', 'Please enter the author name.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['passing_score']   = array('Passing Score', 'Please enter the percentage of questions correct to achieve a passing score.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['plesson']	= array('Associated Lesson', 'This is the lesson that this quiz is associated with (optional).');
$GLOBALS['TL_LANG']['tl_cb_quiz']['final']   = array('Is this quiz the final exam?', 'Check here if this quiz determines a pass/fail of the course.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['reviewable']   = array('Is this quiz reviewable?', 'Check here if this quiz permits going back and reviewing.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['canretake']   = array('Can this quiz be retaken?', 'Check here if this quiz permits going back and attempting to pass more than once.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['retakeuntilpass']   = array('Must retake if failed', 'Check here if the user must pass this quiz. Their number of attempts will be reset each time it\'s retaken.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['description']   = array('Description', 'Please enter a quiz description.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['tstamp']   = array('Last change', 'Date and time of the last change.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['introduction']     = array('Introduction', 'Please enter a quiz introduction. The introduction will be shown on the start page of the quiz.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['success']     = array('Success Message', 'Please enter a success message. The success message will be shown upon successful completion of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['failure']     = array('Failure Message', 'Please enter a failure message. The failure message will be shown upon failed completion of the lesson.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['online_start']      = array('Show from', 'Do not show the quiz on the website before this day.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['online_end']       = array('Show until', 'Do not show the page on the website after this day.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['limit_groups']       = array('Limit members', 'Limit the access to selected member groups.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['allowed_groups']       = array('Member groups', 'Choose the member groups that should be able to participate in the quiz.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['access']     = array('Quiz access', 'Choose the appropriate access method for the quiz.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['access']['explanation']     = 'Please choose the appropriate access method for the quiz.';
$GLOBALS['TL_LANG']['tl_cb_quiz']['access']['anon']     = array('Anonymized access','Everyone can participate in the quiz, even more than once. Access is anonymized. Quiz results cannot be tracked back to a participant.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['access']['anoncode']     = array('Anonymized access with TAN code','Only participants with a valid TAN code can participate in the quiz. A quiz can be finished only once per TAN code. Access is anonymized. Quiz result can be tracked back to each TAN.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['access']['nonanoncode']     = array('Personalized access','Only participants with a valid frontend login can participate in the quiz. A quiz can be finished only once per participant. Quiz results can be tracked back to each participant.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['usecookie']     = array('Remember participants', 'Remembers a quiz participant using a cookie.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['show_title']     = array('Show quiz title', 'Always show the quiz title on top of the quiz.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['show_cancel']     = array('Show cancel', 'Always show an <strong>Exit this quiz</strong> command on top of the quiz.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['allowback']     = array('Show "Previous" button', 'Shows a "Previous" button in the quiz navigation to go back to the previous page.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['jumpTo']     = array('Redirect to page', 'Select a page to redirect the quiz after it was finished.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['cssID']        = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['randomize']     = array('Randomize pool of questions', 'If checked, all questions will be treated as a randomized pool.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['questionsPerPage']     = array('Questions per page', 'Please select the number of randomized questions per page. The total sum will be based on the number of pages you have set up multiplied by this number.');

/**
* Legends
*/
$GLOBALS['TL_LANG']['tl_cb_quiz']['head_legend']    = 'Head settings';
$GLOBALS['TL_LANG']['tl_cb_quiz']['title_legend']    = 'Title and description';
$GLOBALS['TL_LANG']['tl_cb_quiz']['activation_legend']    = 'Activation';
$GLOBALS['TL_LANG']['tl_cb_quiz']['access_legend']    = 'Access';
$GLOBALS['TL_LANG']['tl_cb_quiz']['texts_legend']    = 'Statements';
$GLOBALS['TL_LANG']['tl_cb_quiz']['misc_legend']    = 'General settings';
$GLOBALS['TL_LANG']['tl_cb_quiz']['expert_legend']    = 'Expert settings';

/**
* Buttons
*/
$GLOBALS['TL_LANG']['tl_cb_quiz']['new']    = array('New quiz', 'Create a new quiz');
$GLOBALS['TL_LANG']['tl_cb_quiz']['show']   = array('Quiz details', 'Show the details of quiz %s');
$GLOBALS['TL_LANG']['tl_cb_quiz']['edit']   = array('Edit quiz', 'Edit quiz ID %s');
$GLOBALS['TL_LANG']['tl_cb_quiz']['edit_']   = array('You cannot edit the quiz', 'Quiz ID %s is locked. Participant results already exist.');
$GLOBALS['TL_LANG']['tl_cb_quiz']['copy']   = array('Duplicate quiz', 'Duplicate quiz ID %s');
$GLOBALS['TL_LANG']['tl_cb_quiz']['delete'] = array('Delete quiz', 'Delete quiz ID %s');

?>
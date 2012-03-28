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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['title']   = array('Title', 'Please enter the question title.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['author']   = array('Author', 'Please enter the name of the author.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['questiontype']   = array('Question type', 'Please choose the question type.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['description']   = array('Description', 'Please enter the question description.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['question']   = array('Question text', 'Please enter the question text.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['language']    = array('Language', 'Please choose the question language.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['obligatory']     = array('Mandatory', 'A mandatory question requires an answer.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['help']     = array('Help', 'Please enter a help text that is shown next to the question title.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['introduction']     = array('Introduction', 'Please enter an introduction that is shown at the beginning of a page.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['lower_bound']     = array('Lower bound', 'Please enter the lower bound of the range.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['upper_bound']     = array('Upper bound', 'Please enter the upper bound of the range.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['choices'] = array('Choices', 'Please use the buttons to create, copy, move, or delete choices. If you disabled JavaScript, please save your input before you change the struture of the choices!');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['hidetitle'] = array('Hide question title', 'Do not show the question title during survey execution.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addother'] = array('Add other', 'Add an additional choice (other) with a text field.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addscale'] = array('Add scale', 'Choose a scale from the list of scales and add the scale to the question.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_style'] = array('Answer presentation', 'Please choose an answer presentation.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_style']['vertical'] = 'Vertical aligned choices';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_style']['horizontal'] = 'Horizontal aligned choices';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_style']['select'] = 'Dropdown menu';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['othertitle'] = array('Other title', 'Please enter a title for the additional choice. The text will be shown in front of the text field.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['scale'] = array('Scale', 'Please choose a scale from the list of scales.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['save_add_scale'] = 'Add scale';

/**
 * Question types
 */
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_subtype']     = array('Subtype', 'Please choose an openended question subtype.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_textbefore']     = array('Label in front', 'Please enter a label that is shown in front of the text field.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_textafter']     = array('Label behind', 'Please enter a label that is shown behind the text field.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_textinside']     = array('Placeholder', 'Please enter a placeholder text that is shown in the text field.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_rows']     = array('Rows', 'Please enter the number of rows for the text area.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_cols']     = array('Columns', 'Please enter the number of columns for the text area.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_width']     = array('Width', 'Please enter the width of the text field in characters.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended_maxlen']     = array('Maximum length', 'Please enter the maximum length of the text field in characters.');


/**
* Legends
*/
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['title_legend']    = 'Title and question type';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['question_legend']    = 'Question text';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['obligatory_legend']    = 'Mandatory input';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['specific_legend']    = 'Question specific settings';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['rows_legend']    = 'Matrix rows';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['columns_legend']    = 'Matrix columns';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['bipolar_legend']    = 'Bipolar attributes';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sum_legend']    = 'Sum options';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['new']    = array('New question', 'Create a new question');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['show']   = array('Details', 'Show details of question ID %s');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['edit']   = array('Edit question', 'Edit question ID %s');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['copy']   = array('Duplicate question', 'Duplicate question ID %s');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['cut']   = array('Move question', 'Move question ID %s');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['up']   = array('Move up', 'Move question ID %s up');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['down']   = array('Move down', 'Move question ID %s down');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['delete'] = array('Delete question', 'Delete question ID %s');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['details'] = array('Detailed statistics', 'Show detailed statistics of question ID %s');

/**
 * Misc
 */
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['openended'] = 'Openended question';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['oe_singleline'] = 'Single-line';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['oe_multiline'] = 'Multi-line';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['oe_integer'] = 'Integer';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['oe_float'] = 'Floating point number';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['oe_date'] = 'Date';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['oe_time'] = 'Time';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['multiplechoice'] = 'Multiple choice question';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_singleresponse'] = 'Single response';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_multipleresponse'] = 'Multiple response';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_dichotomous'] = 'Dichotomous (Yes/No)';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrix'] = 'Matrix question';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrix_singleresponse'] = 'One answer per row (single response)';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrix_multipleresponse'] = 'Multiple answers per row (multiple response)';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['constantsum'] = 'Constant sum';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sum'] = array('Sum', 'Enter a sum value.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sumoption'] = array('Calculation', 'Select an option to compare the entered values with the sum value.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sum']['exact'] = 'The sum of the entered values has to be equal to the sum value.';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sum']['max'] = 'The sum of the entered values must not be greater than the sum value.';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['inputfirst'] = array('Show input fields in front', 'Show input fields in front of the answer text (default is behind the answer text).');

$GLOBALS['TL_LANG']['tl_cb_quizquestion']['answered'] = 'Answered';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['skipped'] = 'Skipped';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['most_selected_value'] = 'Most selected value';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['nr_of_selections'] = 'Number of selections';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['median'] = 'Median';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['arithmeticmean'] = 'Arithmetic mean';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['yes'] = 'Yes';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['no'] = 'No';

$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_new'] = "New answer";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_copy'] = "Duplicate answer";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_delete'] = "Delete answer";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixrow_new'] = "New row";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixrow_copy'] = "Duplicate row";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixrow_delete'] = "Delete row";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixcolumn_new'] = "New column";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixcolumn_copy'] = "Duplicate column";
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixcolumn_delete'] = "Delete column";

$GLOBALS['TL_LANG']['tl_cb_quizquestion']['multiplechoice_subtype']     = array('Subtype', 'Please choose a multiple choice question subtype.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrix_subtype']     = array('Subtype', 'Please choose a matrix question subtype.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrixrows'] = array('Rows', 'Please use the buttons to create, copy, move, or delete rows. If you disabled JavaScript, please save your input before you change the structure of the rows!');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrixcolumns'] = array('Columns', 'Please use the buttons to create, copy, move, or delete columns. If you disabled JavaScript, please save your input before you change the structure of the columns!');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addneutralcolumn'] = array('Add neutral column', 'Add a neutral column as last column (undecided, don\'t know, etc.) of the matrix question.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['neutralcolumn'] = array('Neutral column', 'Please enter the text for the neutral column.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addbipolar'] = array('Show bipolar attributes', 'Show bipolar attributes for the matrix question (e.g. good - bad, light - heavy, etc.).');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['adjective1'] = array('Left attribute', 'Please enter the text of the left attribute.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['adjective2'] = array('Right attribute', 'Please enter the text of the right attribute.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['bipolarposition'] = array('Position attributes', 'Please choose the position of the bipolar attributes in the matrix question.');
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['bipolarposition']['top'] = 'Above the column headers';
$GLOBALS['TL_LANG']['tl_cb_quizquestion']['bipolarposition']['aside'] = 'Left and right of the columns';

?>
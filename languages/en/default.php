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
 * Course Elements
 */
$GLOBALS['TL_LANG']['CB']['quiz']			= 'Quiz';
$GLOBALS['TL_LANG']['CB']['lesson']			= 'Lesson';

/**
 * Course status
 */
$GLOBALS['TL_LANG']['CB']['in_progress']	= 'In progress';
$GLOBALS['TL_LANG']['CB']['complete']		= 'Complete';


/**
 * Button Labels
 */
$GLOBALS['TL_LANG']['CB']['buttonnextpage']			= 'Next Page';
$GLOBALS['TL_LANG']['CB']['buttonprevpage']			= 'Previous Page';
$GLOBALS['TL_LANG']['CB']['buttonfinalsegment']		= 'Finish';
$GLOBALS['TL_LANG']['CB']['buttonfirstsegment']		= 'Begin';
$GLOBALS['TL_LANG']['CB']['buttonnextsegment']		= 'Continue to Next Section';
$GLOBALS['TL_LANG']['CB']['buttonretakesegment']	= 'Retake this Section';

/**
 * Other Labels
 */
$GLOBALS['TL_LANG']['CB']['MISC']['resume']				= 'Resume course';
$GLOBALS['TL_LANG']['CB']['MISC']['restart']			= 'Restart course';
$GLOBALS['TL_LANG']['CB']['MISC']['review']				= 'Review course';
$GLOBALS['TL_LANG']['CB']['MISC']['retake']				= 'Retake course';
$GLOBALS['TL_LANG']['CB']['MISC']['begin'] 				= 'Begin course';
$GLOBALS['TL_LANG']['CB']['MISC']['printcert'] 			= 'Print certificate';
$GLOBALS['TL_LANG']['CB']['MISC']['wrong'] 				= 'The following questions were answered incorrectly:';
$GLOBALS['TL_LANG']['CB']['MISC']['question_label']		= 'Question';
$GLOBALS['TL_LANG']['CB']['MISC']['response_label']		= 'Response';
$GLOBALS['TL_LANG']['CB']['MISC']['correct_label']		= 'Correct answer';


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['CB']['ERR']['nocourses'] = 'There are no courses available for you to take.';

/**
 * Misc
 */
$GLOBALS['TL_LANG']['ERR']['sumnotexact'] = 'The sum of the entered values of question "%s" is different from %s.';
$GLOBALS['TL_LANG']['ERR']['sumnotmax'] = 'The sum of the entered values of question "%s" is greater than %s.';
$GLOBALS['TL_LANG']['ERR']['selectoption']   = 'Please select an option.';
$GLOBALS['TL_LANG']['ERR']['mandatory_constantsum'] = 'Please fill in the question "%s" completely.';
$GLOBALS['TL_LANG']['ERR']['mandatory_matrix'] = 'Please check a least one option in every row of question "%s".';
$GLOBALS['TL_LANG']['ERR']['mandatory_mc_sr'] = 'Please check exactly one answer of question "%s".';
$GLOBALS['TL_LANG']['ERR']['mandatory_mc_mr'] = 'Please check at least on answer of question "%s".';
$GLOBALS['TL_LANG']['ERR']['missing_other_value'] = 'You checked the additional answer but you didn\'t enter a text.';
$GLOBALS['TL_LANG']['ERR']['lower_bound'] = 'Value (%s) for "%s" is smaller than allowed (%s).';
$GLOBALS['TL_LANG']['ERR']['upper_bound'] = 'Value (%s) for "%s" is greater than allowed (%s).';

?>
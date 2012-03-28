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
$GLOBALS['TL_LANG']['tl_cb_course']['name']        		= array('Title', 'Please enter the course title.');
$GLOBALS['TL_LANG']['tl_cb_course']['alias']       		= array('Course alias', 'The course alias is a unique reference to the course which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_cb_course']['courseelements']   = array('Course elements', 'Assemble the course elements in the order you would like to use for this course.');
$GLOBALS['TL_LANG']['tl_cb_course']['certSRC']     		= array('Certificate background', 'Select the completed certificate background image you would like to attach to this course. Recommended size is 1580px x 1225px for best quality');
$GLOBALS['TL_LANG']['tl_cb_course']['published']   		= array('Published', 'Make the course publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_cb_course']['start']       		= array('Show from', 'Do not show the course on the website before this day.');
$GLOBALS['TL_LANG']['tl_cb_course']['stop']        		= array('Show until', 'Do not show the course on the website on and after this day.');
$GLOBALS['TL_LANG']['tl_cb_course']['tstamp']     		= array('Revision date', 'Date and time of the latest revision');
$GLOBALS['TL_LANG']['tl_cb_course']['protected']  		= array('Protect course', 'Show the course to certain member groups only.');
$GLOBALS['TL_LANG']['tl_cb_course']['groups']     		= array('Allowed member groups', 'These groups will be able to see the course.');
$GLOBALS['TL_LANG']['tl_cb_course']['coursenavmodule']  = array('Course navigation module', 'Select the course navigation module to use for this course.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_cb_course']['title_legend']   		= 'Title';
$GLOBALS['TL_LANG']['tl_cb_course']['elements_legend']    	= 'Element Details';
$GLOBALS['TL_LANG']['tl_cb_course']['cert_legend']    		= 'Certificate Details';
$GLOBALS['TL_LANG']['tl_cb_course']['protected_legend'] 	= 'Access Rights';
$GLOBALS['TL_LANG']['tl_cb_course']['publish_legend'] 		= 'Publishing';

/**
 * Reference
 */


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_cb_course']['new']        = array('New course', 'Create a new course');
$GLOBALS['TL_LANG']['tl_cb_course']['show']       = array('Course details', 'Show the details of course ID %s');
$GLOBALS['TL_LANG']['tl_cb_course']['edit']       = array('Edit course', 'Edit course ID %s');
$GLOBALS['TL_LANG']['tl_cb_course']['editheader'] = array('Edit course settings', 'Edit the settings of course ID %s');
$GLOBALS['TL_LANG']['tl_cb_course']['copy']       = array('Duplicate course', 'Duplicate course ID %s');
$GLOBALS['TL_LANG']['tl_cb_course']['cut']        = array('Move course', 'Move course ID %s');
$GLOBALS['TL_LANG']['tl_cb_course']['delete']     = array('Delete course', 'Delete course ID %s');
$GLOBALS['TL_LANG']['tl_cb_course']['toggle']     = array('Publish/unpublish course', 'Publish/unpublish course ID %s');
?>
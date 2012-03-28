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
 * Table tl_cb_page
 */
$GLOBALS['TL_DCA']['tl_cb_page'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_cb_quiz',
		'ctable'                      => array('tl_cb_question'),
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'filter'                  => true,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'search,filter,limit',
			'headerFields'            => array('title', 'tstamp', 'description'),
			'child_record_callback'   => array('tl_cb_page', 'compilePreview')
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_page']['edit'],
				'href'                => 'table=tl_cb_question',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_cb_page', 'editPage')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_page']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_cb_page', 'copyPage')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_page']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_cb_page', 'cutPage')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_page']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_cb_page', 'deletePage')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_page']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	
	// Palettes
	'palettes' => array
	(
		'default'               => '{title_legend},title,description;{intro_legend},introduction;{template_legend},page_template',
	),
	
	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_page']['title'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'insertTag'=>true)
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_page']['description'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;')
		),
		'introduction' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_page']['introduction'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte'=>'tinyMCE')
		),
		'page_template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_page']['page_template'],
			'default'                 => 'cb_quizpage_default',
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_page', 'getSurveyTemplates'),
			'eval'                    => array('tl_class'=>'w50')
		)
	)
);


/**
 * Class tl_cb_page
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_cb_page extends Backend
{
	protected $hasData = null;
	
	/**
	 * Return all survey templates as array
	 * @param object
	 * @return array
	 */
	public function getSurveyTemplates(DataContainer $dc)
	{
		if (version_compare(VERSION.BUILD, '2.9.0', '>=')) 
		{
			return $this->getTemplateGroup('cb_quizpage', $dc->activeRecord->pid);
		} 
		else 
		{
			return $this->getTemplateGroup('cb_quizpage');
		}
	}

	
	/**
	 * Return the edit page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editPage($row, $href, $label, $title, $icon, $attributes)
	{

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Return the copy page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPage($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Return the cut page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function cutPage($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Return the delete page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deletePage($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
	
	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param boolean
	 * @return string
	 */
	public function compilePreview($row, $blnWriteToFile=false)
	{
		$objElements = $this->Database->prepare("SELECT * FROM tl_cb_page WHERE (pid=? AND sorting < ?)")
			->execute($row["pid"], $row["sorting"]);
		$position = $objElements->numRows + 1;

		$template = new BackendTemplate('be_cb_page_preview');
		$template->page = $GLOBALS['TL_LANG']['tl_cb_page']['page'];
		$template->position = $position;
		$template->title = specialchars($row['title']);
		$template->description = specialchars($row['description']);
		return $template->parse();
	}
}

?>
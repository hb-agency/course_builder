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
 * Table tl_cb_quizquestion
 */
$GLOBALS['TL_DCA']['tl_cb_quizquestion'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_cb_quizpage',
		'enableVersioning'            => true,
		'onsubmit_callback' => array
		(
			array('tl_cb_quizquestion', 'setCompleteStatus')
		)
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
			'child_record_callback'   => array('tl_cb_quizquestion', 'compilePreview')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'            => array('questiontype','multiplechoice_subtype', 'addother', 'addneutralcolumn', 'addbipolar'),
		'default'                 => '{title_legend},title,questiontype',
		'multiplechoice'         => '{title_legend},title,author,questiontype,multiplechoice_subtype,description,hidetitle,help,language;{question_legend},question;{obligatory_legend},obligatory;{specific_legend},choices',
		'multiplechoicemc_singleresponse'    => '{title_legend},title,author,questiontype,multiplechoice_subtype,description,hidetitle,help,language;{question_legend},question;{obligatory_legend},obligatory;{specific_legend},choices,addother,mc_style',
		'multiplechoicemc_dichotomous'    => '{title_legend},title,author,questiontype,multiplechoice_subtype,description,hidetitle,help,language;{question_legend},question;{obligatory_legend},obligatory;{specific_legend},mc_style',
		'multiplechoicemc_multipleresponse'  => '{title_legend},title,author,questiontype,multiplechoice_subtype,description,hidetitle,help,language;{question_legend},question;{obligatory_legend},obligatory;{specific_legend},choices,addother,mc_style'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addother'                    => 'othertitle',
		'addneutralcolumn'            => 'neutralcolumn',
		'addbipolar'                  => 'adjective1,adjective2,bipolarposition'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['title'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities' => true, 'insertTag'=>true, 'tl_class'=>'w50')
		),
		'help' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['help'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities' => true)
		),
		'questiontype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['questiontype'],
			'default'                 => 'openended',
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_quizquestion', 'getQuestiontypes'),
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50 clr')
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['author'],
			'default'                 => $this->User->id,
			'filter'                  => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('tl_class'=>'w50','decodeEntities' => true)
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['description'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'tl_class'=>'clr','decodeEntities' => true)
		),
		'hidetitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['hidetitle'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'question' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['question'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>false, 'allowHtml'=>true, 'style'=>'height:80px;', 'rte'=>'tinyMCE', 'decodeEntities' => true)
		),
		'introduction' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['introduction'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'style'=>'height:80px;', 'rte'=>'tinyMCE', 'decodeEntities' => true)
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['language'],
			'default'                 => $GLOBALS['TL_LANGUAGE'],
			'inputType'               => 'select',
			'options'                 => $this->getLanguages(),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'obligatory' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['obligatory'],
			'filter'                  => true,
			'inputType'               => 'checkbox'
		),
		'lower_bound' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['lower_bound'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'style' => 'width: 5em;', 'rgxp' => 'digit', 'tl_class'=>'clr w50', 'decodeEntities' => true)
		),
		'upper_bound' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['upper_bound'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'style' => 'width: 5em;', 'rgxp' => 'digit', 'tl_class'=>'w50', 'decodeEntities' => true)
		),
		'lower_bound_date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['lower_bound'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>true, 'tl_class'=>'clr w50 wizard')
		),
		'upper_bound_date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['upper_bound'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'lower_bound_time' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['lower_bound'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'time', 'tl_class'=>'clr w50')
		),
		'upper_bound_time' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['upper_bound'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'rgxp' => 'time', 'tl_class'=>'w50')
		),
		'multiplechoice_subtype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['multiplechoice_subtype'],
			'default'                 => 'mc_singleresponse',
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_quizquestion', 'getMultipleChoiceSubtypes'),
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50')
		),
		'choices' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['choices'],
			'exclude'                 => true,
			'inputType'               => 'choicewizard',
			'eval'                    => array(
				'allowHtml'=>true, 
				'decodeEntities' => true, 
				'buttonTitles' => array(
					'new' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_new'], 
					'copy' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_copy'], 
					'delete' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_delete']
				)
			)
		),
		'sumchoices' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['choices'],
			'exclude'                 => true,
			'inputType'               => 'textwizard',
			'eval'                    => array('allowHtml'=>true, 'decodeEntities' => true)
		),
		'addother' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addother'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'othertitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['othertitle'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>150, 'decodeEntities' => true)
		),
		'matrix_subtype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrix_subtype'],
			'default'                 => 'matrix_singleresponse',
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_quizquestion', 'getMatrixSubtypes'),
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50')
		),
		'matrixrows' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrixrows'],
			'exclude'                 => true,
			'inputType'               => 'textwizard',
			'eval'                    => array(
				'allowHtml'=>true, 
				'decodeEntities' => true, 
				'buttonTitles' => array(
					'new' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixrow_new'], 
					'copy' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixrow_copy'], 
					'delete' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixrow_delete']
				)
			)
		),
		'matrixcolumns' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['matrixcolumns'],
			'exclude'                 => true,
			'inputType'               => 'textwizard',
			'eval'                    => array(
				'allowHtml'=>true, 
				'decodeEntities' => true, 
				'buttonTitles' => array(
					'new' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixcolumn_new'], 
					'copy' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixcolumn_copy'], 
					'delete' => $GLOBALS['TL_LANG']['tl_cb_quizquestion']['buttontitle_matrixcolumn_delete']
				)
			)
		),
		'addneutralcolumn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addneutralcolumn'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'neutralcolumn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['neutralcolumn'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'mandatory' => true, 'decodeEntities' => true)
		),
		'addbipolar' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['addbipolar'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'adjective1' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['adjective1'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'mandatory' => true, 'tl_class'=>'w50', 'decodeEntities' => true)
		),
		'adjective2' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['adjective2'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'mandatory' => true, 'tl_class'=>'w50', 'decodeEntities' => true)
		),
		'bipolarposition' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['bipolarposition'],
			'default'                 => 'top',
			'inputType'               => 'select',
			'options'                 => array('top', 'aside'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['bipolarposition'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'mc_style' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_style'],
			'default'                 => 'vertical',
			'inputType'               => 'select',
			'options_callback'        => array('tl_cb_quizquestion', 'getMCStyleOptions'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_style']
		),
		'inputfirst' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['inputfirst'],
			'inputType'               => 'checkbox'
		),
		'sumoption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sumoption'],
			'default'                 => 'exact',
			'inputType'               => 'select',
			'options'                 => array('exact', 'max'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sum']
		),
		'sum' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cb_quizquestion']['sum'],
			'default'                 => 100,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'mandatory' => true, 'rgxp' => 'digit', 'decodeEntities' => true)
		)
	)
);

/**
 * Class tl_cb_quizquestion
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Helmut Schottmüller 2009
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Controller
 */
class tl_cb_quizquestion extends Backend
{
	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		return sprintf('<div class="list_icon" style="background-image:url(\'system/modules/survey_ce/html/images/question.png\');">%s</div>', $label);
	}
	
	/**
	 * Return all questiontypes as an array
	 * @return array
	 */
	public function getQuestiontypes()
	{
		$qt = array();

		$qt["none"] = " - ";
		$qt["multiplechoice"] = $GLOBALS['TL_LANG']['tl_cb_quizquestion']['multiplechoice'];
		return $qt;
	}

	public function getMultipleChoiceSubtypes()
	{
		$mc = array();
		$mc["mc_singleresponse"] = $GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_singleresponse'];
		$mc["mc_multipleresponse"] = $GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_multipleresponse'];
		$mc["mc_dichotomous"] = $GLOBALS['TL_LANG']['tl_cb_quizquestion']['mc_dichotomous'];
		return $mc;
	}

	public function setCompleteStatus(DataContainer $dc)
	{
		$this->Database->prepare("UPDATE tl_cb_quizquestion SET complete = ?, original = ? WHERE id=?")
			->execute(1, 1, $dc->id);
	}
	
	public function getMCStyleOptions(DataContainer $dc)
	{
		$objQuestion = $this->Database->prepare("SELECT multiplechoice_subtype FROM tl_cb_quizquestion WHERE id=?")
			->limit(1)
			->execute($dc->id);
		if (strcmp($objQuestion->multiplechoice_subtype, 'mc_multipleresponse') == 0)
		{
			return array('vertical', 'horizontal');
		}
		else
		{
			return array('vertical', 'horizontal', 'select');
		}
	}
	
	
	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param boolean
	 * @return string
	 */
	public function compilePreview($row, $blnWriteToFile=false)
	{
		$widget = "";
		$strClass = $GLOBALS['CB_QUESTION'][$row['questiontype']];
		if ($this->classFileExists($strClass))
		{
			$objWidget = new $strClass();
			$objWidget->surveydata = $row;
			$widget = $objWidget->generate();
		}

		$template = new BackendTemplate('be_cb_question_preview');
		$template->hidetitle = $row['hidetitle'];
		$template->help = specialchars($row['help']);
		$template->questionNumber = $this->Database->prepare("SELECT * FROM tl_cb_quizquestion WHERE (pid=? AND sorting <= ?)")
			->execute($row["pid"], $row["sorting"])->numRows;
		$template->title = specialchars($row['title']);
		$template->obligatory = $row['obligatory'];
		$template->question = $row['question'];
		$return = $template->parse();
		$return .= $widget;
		return $return;
	}

}
?>
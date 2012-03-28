-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_cb_course`
-- 

CREATE TABLE `tl_cb_course` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `coursenavmodule` int(10) unsigned NOT NULL default '0',
  `courseelements` blob NULL,
  `certSRC` blob NULL,
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


-- 
-- Table `tl_cb_quiz`
-- 

CREATE TABLE `tl_cb_quiz` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `author` smallint(5) unsigned NOT NULL default '0',
  `passing_score` smallint(5) unsigned NOT NULL default '0',
  `plesson` smallint(5) unsigned NOT NULL default '0',
  `final` char(1) NOT NULL default '',
  `reviewable` char(1) NOT NULL default '',
  `canretake` char(1) NOT NULL default '',
  `retakeuntilpass` char(1) NOT NULL default '',
  `online_start` varchar(32) NOT NULL default '',
  `online_end` varchar(32) NOT NULL default '',
  `description` text NULL,
  `usecookie` char(1) NOT NULL default '',
  `limit_groups` char(1) NOT NULL default '0',
  `show_title` char(1) NOT NULL default '1',
  `show_cancel` char(1) NOT NULL default '1',
  `allowed_groups` blob NULL,
  `introduction` text NOT NULL,
  `success` text NOT NULL,
  `failure` text NOT NULL,
  `allowback` char(1) NOT NULL default '',
  `randomize` char(1) NOT NULL default '',
  `questionsPerPage` int(10) unsigned NOT NULL default '0',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `cssID` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cb_question`
-- 

CREATE TABLE `tl_cb_quizquestion` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `questiontype` varchar(20) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `description` text NULL,
  `author` smallint(5) unsigned NOT NULL default '0',
  `language` varchar(32) NOT NULL default '',
  `question` text NOT NULL,
  `introduction` text NOT NULL,
  `obligatory` char(1) NOT NULL default '',
  `complete` char(1) NOT NULL default '',
  `original` char(1) NOT NULL default '',
  `help` varchar(255) NOT NULL default '',
  `hidetitle` char(1) NOT NULL default '',
  `lower_bound` varchar(32) NOT NULL default '',
  `upper_bound` varchar(32) NOT NULL default '',
  `lower_bound_date` varchar(32) NOT NULL default '',
  `upper_bound_date` varchar(32) NOT NULL default '',
  `lower_bound_time` varchar(32) NOT NULL default '',
  `upper_bound_time` varchar(32) NOT NULL default '',
  `openended_subtype` varchar(32) NOT NULL default '',
  `openended_textbefore` varchar(150) NOT NULL default '',
  `openended_textafter` varchar(150) NOT NULL default '',
  `openended_rows` smallint(5) unsigned NOT NULL default '5',
  `openended_cols` smallint(5) unsigned NOT NULL default '40',
  `openended_width` varchar(4) NOT NULL default '',
  `openended_maxlen` varchar(5) NOT NULL default '',
  `openended_textinside` varchar(150) NOT NULL default '',
  `multiplechoice_subtype` varchar(32) NOT NULL default '',
  `matrix_subtype` varchar(32) NOT NULL default '',
  `mc_style` varchar(32) NOT NULL default '',
  `choices` blob NULL,
  `matrixrows` blob NULL,
  `matrixcolumns` blob NULL,
  `addneutralcolumn` char(1) NOT NULL default '',
  `neutralcolumn` varchar(255) NOT NULL default '',
  `addother` char(1) NOT NULL default '',
  `addbipolar` char(1) NOT NULL default '',
  `adjective1` varchar(255) NOT NULL default '',
  `adjective2` varchar(255) NOT NULL default '',
  `bipolarposition` varchar(32) NOT NULL default '',
  `othertitle` varchar(150) NOT NULL default '',
  `inputfirst` char(1) NOT NULL default '',
  `sumoption` varchar(32) NOT NULL default '',
  `sumchoices` blob NULL,
  `sum` double NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_cb_quizpage`
-- 

CREATE TABLE `tl_cb_quizpage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NULL,
  `language` varchar(32) NOT NULL default '',
  `introduction` text NOT NULL,
  `success` text NOT NULL,
  `failure` text NOT NULL,
  `page_template` varchar(255) NOT NULL default 'cb_questionblock',
  `pagetype` varchar(30) NOT NULL default 'standard',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


-- 
-- Table `tl_cb_lesson`
-- 

CREATE TABLE `tl_cb_lesson` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `author` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `passing_score` smallint(5) unsigned NOT NULL default '0',
  `final` char(1) NOT NULL default '',
  `reviewable` char(1) NOT NULL default '',
  `online_start` varchar(32) NOT NULL default '',
  `online_end` varchar(32) NOT NULL default '',
  `description` text NULL,
  `usecookie` char(1) NOT NULL default '',
  `limit_groups` char(1) NOT NULL default '0',
  `show_title` char(1) NOT NULL default '1',
  `show_cancel` char(1) NOT NULL default '1',
  `allowed_groups` blob NULL,
  `introduction` text NOT NULL,
  `success` text NOT NULL,
  `allowback` char(1) NOT NULL default '',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `cssID` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cb_lessonpage`
-- 

CREATE TABLE `tl_cb_lessonpage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NULL,
  `language` varchar(32) NOT NULL default '',
  `introduction` text NOT NULL,
  `page_template` varchar(255) NOT NULL default 'cb_segmentblock',
  `pagetype` varchar(30) NOT NULL default 'standard',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_cb_lessonsegment`
-- 

CREATE TABLE `tl_cb_lessonsegment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `invisible` char(1) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cb_coursedata`
-- 

CREATE TABLE `tl_cb_coursedata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `session` varchar(64) NOT NULL default '',
  `courseid` int(10) unsigned NOT NULL default '0',
  `status` varchar(64) NOT NULL default '',
  `pass` char(1) NOT NULL default '',
  `uniqid` varchar(255) NOT NULL default '',
  `attempt` int(10) unsigned NOT NULL default '0',
  `settings` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `courseid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cb_quizdata`
-- 

CREATE TABLE `tl_cb_quizdata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `session` varchar(64) NOT NULL default '',
  `quizid` int(10) unsigned NOT NULL default '0',
  `lastpage` int(10) unsigned NOT NULL default '0',
  `maxpage` int(10) unsigned NOT NULL default '0',
  `lastitem` int(10) unsigned NOT NULL default '0',
  `status` varchar(64) NOT NULL default '',
  `score` varchar(64) NOT NULL default '',
  `sequencekey` blob NULL,
  `settings` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `quizid` (`quizid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cb_quizdata_items`
-- 

CREATE TABLE `tl_cb_quizdata_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `question` int(10) unsigned NOT NULL default '0',
  `response` mediumtext NULL,
  `valid` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_cb_lessondata`
-- 

CREATE TABLE `tl_cb_lessondata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `session` varchar(64) NOT NULL default '',
  `lessonid` int(10) unsigned NOT NULL default '0',
  `lastpage` int(10) unsigned NOT NULL default '0',
  `maxpage` int(10) unsigned NOT NULL default '0',
  `lastitem` int(10) unsigned NOT NULL default '0',
  `status` varchar(64) NOT NULL default '',
  `settings` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `lessonid` (`lessonid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cb_lessondata_items`
-- 

CREATE TABLE `tl_cb_lessondata_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `segment` int(10) unsigned NOT NULL default '0',
  `response` mediumtext NULL,
  `valid` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `cb_courselist_layout` varchar(64) NOT NULL default '',
  `cb_coursereader_layout` varchar(64) NOT NULL default '',
  `cb_coursenavigation_layout` varchar(64) NOT NULL default '',
  `cb_courseprogressbar_layout` varchar(64) NOT NULL default '',
  `cb_course_template` varchar(64) NOT NULL default '',
  `cb_quiz_template` varchar(64) NOT NULL default '',
  `cb_lesson_template` varchar(64) NOT NULL default '',
  `cb_success_jumpTo` int(10) unsigned NOT NULL default '0',
  `cb_failed_jumpTo` int(10) unsigned NOT NULL default '0', 
  `cb_reader_jumpTo` int(10) unsigned NOT NULL default '0',
  `cb_productlist_jumpTo` int(10) unsigned NOT NULL default '0',
  `cb_includeMessages` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `courses` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- LESSON SEGMENT START --
CREATE TABLE `tl_cb_lessonsegment` (
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `invisible` char(1) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `text` mediumtext NULL,
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `size` varchar(64) NOT NULL default '',
  `imagemargin` varchar(128) NOT NULL default '',
  `imageUrl` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `html` mediumtext NULL,
  `listtype` varchar(32) NOT NULL default '',
  `listitems` blob NULL,
  `tableitems` mediumblob NULL,
  `summary` varchar(255) NOT NULL default '',
  `thead` char(1) NOT NULL default '',
  `tfoot` char(1) NOT NULL default '',
  `sortable` char(1) NOT NULL default '',
  `sortIndex` smallint(5) unsigned NOT NULL default '0',
  `sortOrder` varchar(32) NOT NULL default '',
  `mooType` varchar(32) NOT NULL default '',
  `mooHeadline` varchar(255) NOT NULL default '',
  `mooStyle` varchar(255) NOT NULL default '',
  `mooClasses` varchar(255) NOT NULL default '',
  `shClass` varchar(255) NOT NULL default '',
  `highlight` varchar(32) NOT NULL default '',
  `code` text NULL,
  `url` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `linkTitle` varchar(255) NOT NULL default '',
  `embed` varchar(255) NOT NULL default '',
  `rel` varchar(64) NOT NULL default '',
  `useImage` char(1) NOT NULL default '',
  `multiSRC` blob NULL,
  `useHomeDir` char(1) NOT NULL default '',
  `perRow` smallint(5) unsigned NOT NULL default '0',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `sortBy` varchar(32) NOT NULL default '',
  `galleryTpl` varchar(64) NOT NULL default '',
  `cteAlias` int(10) unsigned NOT NULL default '0',
  `articleAlias` int(10) unsigned NOT NULL default '0',
  `article` int(10) unsigned NOT NULL default '0',
  `form` int(10) unsigned NOT NULL default '0',
  `module` int(10) unsigned NOT NULL default '0',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `guests` char(1) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `space` varchar(64) NOT NULL default '',
  `com_order` varchar(32) NOT NULL default '',
  `com_perPage` smallint(5) unsigned NOT NULL default '0',
  `com_moderate` char(1) NOT NULL default '',
  `com_bbcode` char(1) NOT NULL default '',
  `com_requireLogin` char(1) NOT NULL default '',
  `com_disableCaptcha` char(1) NOT NULL default '',
  `com_template` varchar(32) NOT NULL default '',
  `iso_reader_jumpTo` int(10) unsigned NOT NULL default '0',
  `iso_list_layout` varchar(64) NOT NULL default '',
  `iso_attribute_set` int(10) unsigned NOT NULL default '0',
  `iso_filters` varchar(255) NOT NULL default '0',
  `iso_bundle` blob NULL,
  `productsAlias` blob NULL,
  `survey` smallint(5) unsigned NOT NULL default '0',
  `surveyTpl` varchar(64) NOT NULL default '',
  `sc_name` varchar(255) NOT NULL default '',
  `sc_type` varchar(14) NOT NULL default '',
  `sc_parent` int(10) unsigned NOT NULL default '0',
  `sc_childs` varchar(255) NOT NULL default '',
  `sc_sortid` int(2) unsigned NOT NULL default '0',
  `sc_container` varchar(255) NOT NULL default '',
  `sc_gap` varchar(255) NOT NULL default '',
  `sc_gapdefault` char(1) NOT NULL default '1',
  `sc_equalize` char(1) NOT NULL default '',
  `tleft` char(1) NOT NULL default '',
  `ce_flash_movie` varchar(255) NOT NULL default '',
  `ce_flash_size` varchar(255) NOT NULL default '0',
  `ce_flash_params` blob NULL,
  `ce_flash_vars` blob NULL,
  `ce_flash_text` text NULL,
  `wcsliderType` varchar(32) NOT NULL default '',
  `wcsliderID` varchar(255) NOT NULL default '',
  `wcsliderTimer` varchar(255) NOT NULL default '',
  `wcsliderOrientation` varchar(255) NOT NULL default '',
  `wcsliderDisabled` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- LESSON SEGMENT STOP --




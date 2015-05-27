<?php

/**
 * ce_section
 *
 * Copyright (C) 2013-2015 Andreas Nölke
 *
 * @package   ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Backend Modules
 */

$GLOBALS['BE_MOD']['design']['themes']['tables'][] = "tl_ce_section";

// Support extension easy_themes
$GLOBALS['TL_EASY_THEMES_MODULES']['ce_section'] = array
(
	'title'         => $GLOBALS['TL_LANG']['tl_theme']['ce_section'][1],
	'label'         => $GLOBALS['TL_LANG']['tl_theme']['ce_section'][0],
	'href_fragment' => 'table=tl_ce_section',
	'icon'          => 'system/modules/ce_section/assets/icon.gif'
);
<?php

/**
 * ce_section
 *
 * Copyright (C) 2013 Andreas Nölke
 *
 * @package   ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke 2013
 */

/**
 * Backend Modules
 */

array_insert($GLOBALS['BE_MOD']['system'], array_search('settings',array_keys($GLOBALS['BE_MOD']['system']))+1, array
(
	'ce_section' => array
	(
		'tables' => array('tl_ce_section', 'tl_ce_section_settings'),
		'icon'   => 'system/modules/svg_contao/assets/projects.gif',
	)
));
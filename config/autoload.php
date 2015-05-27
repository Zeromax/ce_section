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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'CESection',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'CESection\ModuleArticle'             => 'system/modules/ce_section/modules/ModuleArticle.php',
));

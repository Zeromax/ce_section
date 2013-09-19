<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
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

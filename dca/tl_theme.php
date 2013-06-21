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
 * Table tl_theme
 */
//layout
$GLOBALS['TL_DCA']['tl_theme']['config']['ctable'][] = "tl_ce_section";
array_insert($GLOBALS['TL_DCA']['tl_theme']['list']['operations'], array_search('layout',array_keys($GLOBALS['TL_DCA']['tl_theme']['list']['operations']))+1, array
(
	'ce_section' => array
	(
		'label'               => &$GLOBALS['TL_LANG']['tl_theme']['ce_section'],
		'href'                => 'table=tl_ce_section',
		'icon'                => 'system/modules/ce_section/assets/icon.gif',
		'button_callback'     => array('tl_ce_section_theme', 'editSection')
	)
));


/**
 * Class tl_theme
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_ce_section_theme extends tl_theme
{

	/**
	 * Return the edit page layouts button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editSection($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('ce_section', 'themes')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}

?>
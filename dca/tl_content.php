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

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_ce_section', 'loadCallback');

/**
 * Class tl_content_ce_section
 *
 * @package   ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke 2013
 */
class tl_content_ce_section extends tl_content
{
	function loadCallback($objTable)
	{
		$objCte = $this->Database->prepare("SELECT pid FROM tl_content WHERE id=?")
								 ->limit(1)
								 ->executeUncached($objTable->id);
		$objArticle = $this->Database->prepare("SELECT inColumn FROM tl_article WHERE id=?")
									 ->limit(1)
									 ->executeUncached($objCte->pid);
		$objSection = $this->Database->prepare("SELECT * FROM tl_ce_section WHERE section=?")
									 ->limit(1)
									 ->executeUncached($objArticle->inColumn);
		$cte = unserialize($objSection->contentElement);
		if (is_array($cte) && count($cte)>0 && $objSection->invisible != 1)
		{
			foreach ($GLOBALS['TL_CTE'] as $k => $v)
			{
				foreach ($v as $kk => $vv)
				{
					if (!in_array($kk, $cte))
					{
						unset($GLOBALS['TL_CTE'][$k][$kk]);
						unset($GLOBALS['TL_DCA']['tl_content']['palettes'][$kk]);
					}
					if (count($GLOBALS['TL_CTE'][$k])<1)
					{
						unset($GLOBALS['TL_CTE'][$k]);
					}
				}
			}
			$arrFirstElement = reset($GLOBALS['TL_CTE']);
			$arrFlip = array_flip($arrFirstElement);
			$GLOBALS['TL_DCA']['tl_content']['fields']['type']['default'] = reset($arrFlip);
		}
	}
}
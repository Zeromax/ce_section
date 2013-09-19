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
 * Run in a custom namespace, so the class can be replaced
 */
namespace CESection;


/**
 * Class ModuleArticle
 *
 * @package   tl_ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke 2013
 */
class ModuleArticle extends \Contao\ModuleArticle
{
	/**
	 * Check whether the article is published
	 * @param boolean
	 * @return string
	 */
	public function generate($blnNoMarkup=false)
	{
		$objSection = $this->Database->prepare("SELECT articleTpl FROM tl_ce_section WHERE section=?")
									 ->execute($this->inColumn);
		if ($objSection->numRows > 0)
		{
			if($objSection->articleTpl != "")
			{
				$this->strTemplate = $objSection->articleTpl;
			}
		}

		return parent::generate($blnNoMarkup);
	}
}

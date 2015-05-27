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
 * Run in a custom namespace, so the class can be replaced
 */
namespace CESection;


/**
 * Class ModuleArticle
 *
 * @package   tl_ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke
 */
class ModuleArticle extends \Contao\ModuleArticle
{
	/**
	 * Check whether the article is published
	 *
	 * @param $blnNoMarkup boolean
	 *
	 * @return string
	 */
	public function generate($blnNoMarkup=false)
	{
		global $objPage;
		$objSection = $this->Database->prepare("SELECT articleTpl FROM tl_ce_section WHERE section=?"
												. "AND pid=(SELECT pid FROM tl_layout WHERE id=?)"
												. "AND invisible=0")
									 ->execute($this->inColumn, $objPage->layout);

		if ($objSection->numRows > 0 && $objSection->articleTpl != "")
		{
			$this->strTemplate = $objSection->articleTpl;
		}

		return parent::generate($blnNoMarkup);
	}
}

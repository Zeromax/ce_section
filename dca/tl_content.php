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

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_ce_section', 'loadCallback');

/**
 * Class tl_content_ce_section
 *
 * @package   ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke
 */
class tl_content_ce_section extends tl_content
{
	function loadCallback($objTable)
	{
		$objCte = $this->Database->prepare("SELECT pid FROM tl_content WHERE id=?")
								 ->limit(1)
								 ->executeUncached($objTable->id);
		$objArticle = $this->Database->prepare("SELECT inColumn,pid FROM tl_article WHERE id=?")
									 ->limit(1)
									 ->executeUncached($objCte->pid);
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
						->limit(1)
						->execute($objArticle->pid);
		if ($objPage->numRows > 0)
		{
			$objPage->layout = $objPage->includeLayout ? $objPage->layout : false;
			$pid = $objPage->pid;
			$type = $objPage->type;
			do
			{
				$objParentPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
												->limit(1)
												->execute($pid);
				if ($objParentPage->numRows < 1)
				{
					break;
				}

				$pid = $objParentPage->pid;
				$type = $objParentPage->type;
				if (!$objPage->layout && $objParentPage->includeLayout)
				{
					$objPage->layout = $objParentPage->layout;
				}
			}
			while ($pid > 0 && $type != 'root');

			if (version_compare(VERSION, '2.11', '>'))
			{
				$themePid = $this->getThemePidC3($objPage);
			}
			else
			{
				$themePid = $this->getThemePidC2($objPage);
			}
			$objSection = $this->Database->prepare("SELECT * FROM tl_ce_section WHERE section=? AND pid=?")
										 ->limit(1)
										 ->executeUncached($objArticle->inColumn,$themePid);
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

	protected function getThemePidC2($objPage)
	{
		$objLayout = $this->Database->prepare("SELECT l.*, t.templates FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id WHERE l.id=? OR l.fallback=1 ORDER BY l.id=? DESC")
									->limit(1)
									->execute($objPage->layout, $objPage->layout);
		if($objLayout === null)
		{
			return 0;
		}
		else
		{
			return $objLayout->pid;
		}
	}
	/**
	 * Get a page layout and return it as database result object
	 * @param \Model
	 * @return \Model
	 */
	protected function getThemePidC3($objPage)
	{
		$objLayout = \LayoutModel::findByPk($objPage->layout);

		if($objLayout === null)
		{
			return 0;
		}
		else
		{
			return $objLayout->pid;
		}
	}
}
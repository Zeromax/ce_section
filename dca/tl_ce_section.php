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
 * Table tl_ce_section
 */
$GLOBALS['TL_DCA']['tl_ce_section'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_theme',
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('section'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('section')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ce_section']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ce_section']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ce_section']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ce_section']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_ce_section', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ce_section']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{legend_layout},section;{legend_elements},contentElement;{legend_settings},invisible',
	),

	// Fields
	'fields' => array
	(
		'section' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ce_section']['section'],
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ce_section', 'getSections'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_article'],
			'eval'					  => array('chosen'=>true),
			'save_callback'			  => array(array('tl_ce_section', 'checkUniqueSection'))
		),
		'contentElement' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ce_section']['contentElement'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_ce_section', 'getContentElements'),
			'reference'               => &$GLOBALS['TL_LANG']['CTE'],
			'eval'                    => array('multiple'=>true)
		),
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ce_section']['invisible'],
			'filter'                  => true,
			'inputType'               => 'checkbox'
		)
	)
);

/**
 * Class tl_ce_section
 *
 * @package   tl_ce_section
 * @author    Andreas Nölke
 * @copyright Andreas Nölke 2013
 */
class tl_ce_section extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		\System::loadLanguageFile('tl_article');
	}

	/**
	 * Check if Section is already in use
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 */
	public function checkUniqueSection($varValue, DataContainer $dc)
	{
		$objSection = $this->Database->prepare("SELECT id FROM tl_ce_section WHERE section=? AND pid=?")
								   ->execute($varValue, $dc->activeRecord->pid);

		// Check whether the page alias exists
		if ($objSection->numRows > 1)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['sectionExists'], $varValue));
		}

		return $varValue;
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$tid = "";
		$state = "";
		$id = "";
		if (version_compare(VERSION, '2.11', '>'))
		{
			$tid = Input::get('tid');
			$state = Input::get('state');
			$id = Input::get('id');
		}
		else
		{
			$tid = $this->Input->get('tid');
			$state = $this->Input->get('state');
			$id = $this->Input->get('id');
		}
		if (strlen($tid))
		{
			$this->toggleVisibility($tid, ($state == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_ce_section::invisible', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;id='.$id.'&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}
		$image = "";
		if (version_compare(VERSION, '3.1', '>='))
		{
			$image = Image::getHtml($icon, $label);
		}
		else
		{
			$image = $this->generateImage($icon, $label);
		}
		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$image.'</a> ';
	}


	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		if (version_compare(VERSION, '2.11', '>'))
		{
			Input::setGet('id', $intId);
			Input::setGet('act', 'toggle');
		}
		else
		{
			$this->Input->setGet('id', $intId);
			$this->Input->setGet('act', 'toggle');
		}

		// The onload_callbacks vary depending on the dynamic parent table (see #4894)
		if (is_array($GLOBALS['TL_DCA']['tl_ce_section']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ce_section']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
			}
		}

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_ce_section::invisible', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide content element ID "'.$intId.'"', 'tl_ce_section toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if (version_compare(VERSION, '3.1', '>='))
		{
			$objVersions = new Versions('tl_ce_section', $intId);
			$objVersions->initialize();
		}
		else
		{
			$this->createInitialVersion('tl_ce_section', $intId);
		}

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_ce_section']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ce_section']['fields']['invisible']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_ce_section SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		if (version_compare(VERSION, '3.1', '>='))
		{
			$objVersions->create();
		}
		else
		{
			$this->createNewVersion('tl_content', $intId);
		}

		$this->log('A new version of record "tl_ce_section.id='.$intId.'" has been created', 'tl_ce_section toggleVisibility()', TL_GENERAL);
	}

	/**
	 * Create Section Array
	 * @return array
	 */
	public function getSections()
	{
		$section = trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']);
		$arrSection['cesectioncore'] = array('header', 'left', 'right', 'main', 'footer');
		$arrSection['cesectioncustom'] = $section;
		return $arrSection;
	}

	/**
	 * Return all content elements as array
	 * @return array
	 */
	public function getContentElements()
	{
		$groups = array();

		foreach ($GLOBALS['TL_CTE'] as $k=>$v)
		{
			foreach (array_keys($v) as $kk)
			{
				$groups[$k][] = $kk;
			}
		}

		return $groups;
	}
}
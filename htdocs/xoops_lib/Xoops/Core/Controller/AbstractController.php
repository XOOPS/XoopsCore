<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Controller;

/**
 * XOOPS AbstractController
 *
 * See the enclosed file license.txt for licensing information. If you did not
 * receive this file, get it at GNU http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @author          Alain091
 */

use Xmf\Request;
use Xoops\Core\FixedGroups;

abstract class AbstractController
{
	protected $xoops;
	protected $xoopsBC;
	protected $xoops_url;
	
	protected $startEvent = '';
	protected $endEvent = '';
	
	protected $language = '';
	protected $template = '';
	
	public function __construct()
	{
		$this->xoops = \Xoops::getInstance();
		$this->xoops_url = \XoopsBaseConfig::get('url');
	}
	
	public function run()
	{
		if (!empty($this->startEvent))
			$this->xoops->events()->triggerEvent($this->startEvent);
		$this->doInit();
		if (!$this->doAuth()) {
			//$this->xoops->redirect($this->xoops_url, 1, \XoopsLocale::E_NO_ACCESS_PERMISSION, false);
			exit;
		}
		$this->doHeader();
		$this->doContent();
		$this->doFooter();
	}
	
	public function doInit()
	{
		if (!empty($this->language))
			$this->xoops->loadLanguage($this->language);
	}
	
	public function doAuth()
	{
		return true;
	}
	
	public function doHeader()
	{
	}
	
	public function doContent()
	{
	}

	public function doFooter()
	{
	}
}

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Controller\FrontController;
use Xoops\Core\FixedGroups;

/**
 * XOOPS global entry
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

$mainfile = __DIR__ . '/mainfile.php';
if (file_exists($mainfile)) {
    include $mainfile;
} elseif (file_exists(__DIR__ . '/install/index.php')) {
    header('Location: install/index.php');
    exit;
}
unset($mainfile);

class indexController extends FrontController
{
	public function doInit()
	{
		parent::doInit();
		
		$this->startEvent = 'core.index.start';
	
		//check if start page is defined
		$startpage = $this->xoops->getConfig('startpage');
		if ($this->xoops->isActiveModule($startpage)) {
			// Temporary solution for start page redirection
			define('XOOPS_STARTPAGE_REDIRECTED', 1);
			$module_handler = $this->xoops->getHandlerModule();
			$this->xoops->module = $this->xoops->getModuleByDirname($startpage);
			if (!$this->xoops->isModule() || !$this->xoops->module->getVar('isactive')) {
				$this->xoops->header();
				echo "<h4>" . XoopsLocale::E_NO_MODULE . "</h4>";
				$this->xoops->footer();
				exit;
			}
			$moduleperm_handler = $this->xoops->getHandlerGroupPermission();
			$mid = $this->xoops->module->getVar('mid');
			if ($this->xoops->isUser()) {
				if (!$moduleperm_handler->checkRight('module_read', $mid, $this->xoops->user->getGroups())) {
					$this->xoops->redirect($this->xoops_url, 1, XoopsLocale::E_NO_ACCESS_PERMISSION, false);
				}
				$this->xoops->userIsAdmin = $this->xoops->user->isAdmin($mid);
			} else {
				if (!$moduleperm_handler->checkRight('module_read', $mid, FixedGroups::ANONYMOUS)) {
					$this->xoops->redirect($this->xoops_url . "/user.php", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
				}
			}
			if ($this->xoops->module->getVar('hasconfig') == 1
				|| $this->xoops->module->getVar('hascomments') == 1
				|| $this->xoops->module->getVar('hasnotification') == 1
			) {
				$this->xoops->moduleConfig = $this->xoops->getModuleConfigs();
			}

			chdir('modules/' . $startpage . '/');
			$this->xoops->loadLanguage('main', $this->xoops->module->getVar('dirname', 'n'));
			$parsed = parse_url($this->xoops_url);
			$url = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : 'http://';
			if (isset($parsed['host'])) {
				$url .= $parsed['host'];
				if (isset($parsed['port'])) {
					$url .= ':' . $parsed['port'];
				}
			} else {
				$url .= $_SERVER['HTTP_HOST'];
			}

			$_SERVER['REQUEST_URI'] =
				substr($this->xoops_url, strlen($url)) . '/modules/' . $startpage . '/index.php';
			include $this->xoops->path('modules/' . $startpage . '/index.php');
			exit;
		}
	}
	
	public function doHeader()
	{
		$this->xoops->setOption('show_cblock', 1);
		$this->template = "module:system/system_homepage.tpl";
		parent::doHeader();
	}
}

$controller = new indexController();
$controller->run();

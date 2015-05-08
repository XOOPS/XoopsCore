<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

/*
 * Xoops\Module\Helper class allows you to access methods of Xoops\Module\Helper\HelperAbstract
 * for any given module, as long as the module is active.
 *
 * You will be able to access the module object, get configs, handlers, forms, load languages, etc
 *
 * If the module has a class extending Xoops\Module\Helper\HelperAbstract in the file class/helper.php
 * the Xoops\Module\Helper will load that class. If not, it will load the Xoops\Module\Helper\Dummy
 */

/**
 * Using Xoops, the verbose way
 */
if ($xoops->isActiveModule('search')) {
    $config = $xoops->getModuleConfig('keyword_min', 'search');
    $xoops->loadLanguage('main', 'search');
    $url = $xoops->url('modules/search/index.php');
    $obj = $xoops->getModuleByDirname('search');
    //etc
}
/**
 * Using the Helper
 */
if ($helper = Xoops\Module\Helper::getHelper('search')) {
    $config = $helper->getConfig('keyword_min');
    $helper->loadLanguage('main');
    $url = $helper->url('index.php');
    $obj = $helper->getModule();
    //etc
}

//Some examples
if ($helper = Xoops\Module\Helper::getHelper('codex')) {
    Xoops_Utils::dumpVar($helper->getModule()->getVar('name'));
    Xoops_Utils::dumpVar($helper->url('index.php'));
}

if ($helper = Xoops\Module\Helper::getHelper('search')) {
    Xoops_Utils::dumpVar($helper->getConfigs());
}

if ($helper = Xoops\Module\Helper::getHelper('nosuchmodule')) {
    Xoops_Utils::dumpVar($helper->getModule()->getVar('name'));
}

Xoops_Utils::dumpFile(__FILE__);
$xoops->footer();

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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

/*
 * Xoops_Module_Helper class allows you to access methods of Xoops_Module_Helper_Abstract
 * for any given module, as long as the module is active.
 *
 * You will be able to access the module object, get configs, handlers, forms, load languages, etc
 *
 * If the module has a class extending Xoops_Module_Helper_Abstract in the file class/helper.php
 * the Xoops_Module_Helper will load that class. If not, it will load the Xoops_Module_Helper_Dummy
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
if ($helper = Xoops_Module_Helper::getHelper('search')) {
    $config = $helper->getConfig('keyword_min');
    $helper->loadLanguage('main');
    $url = $helper->url('index.php');
    $obj = $helper->getModule();
    //etc
}

//Some examples
if($helper = Xoops_Module_Helper::getHelper('publisher')) {
    Xoops_Utils::dumpVar($helper->getModule()->getVar('name'));
}

if($helper = Xoops_Module_Helper::getHelper('search')) {
    Xoops_Utils::dumpVar($helper->getModule()->getVar('name'));
}

if($helper = Xoops_Module_Helper::getHelper('notifications')) {
    Xoops_Utils::dumpVar($helper->getModule()->getVar('name'));
}

if($helper = Xoops_Module_Helper::getHelper('menus')) {
    Xoops_Utils::dumpVar($helper->getModule()->getVar('name'));
}

Xoops_Utils::dumpFile(__FILE__ );
$xoops->footer();
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

$locales = Xoops_Locale::getUserLocales();
Xoops_Utils::dumpVar($locales);

//translations are lazy loaded, they won't be included if a constant not called
Xoops_Locale::loadLocale();
Xoops_Locale::loadLocale('system');
//or
$xoops->loadLocale();
$xoops->loadLocale('system');

echo XoopsLocale::A_DELETE; echo '</br>';
echo XoopsLocale::A_POST; echo '</br>';
echo SystemLocale::RECOMMEND_US; echo '</br>';

//Translate using wrapper, no auto completion but offers support
echo Xoops_Locale::translate('A_EDIT'); echo '</br>'; //get from XoopsLocale by default
echo Xoops_Locale::translate('ADMINISTRATION_MENU'); echo '</br>'; //Not in XoopsLocale, the key is echoed
echo Xoops_Locale::translate('ADMINISTRATION_MENU', 'system'); echo '</br>';//Displays translation
echo Xoops_Locale::translate('_CHARSET'); echo '</br>';//Translations not present on classes are loaded from defines()
//or
echo $xoops->translate('ACTIVE'); echo '</br>';

//Example of template
$tpl = new XoopsTpl();
$tpl->display(__DIR__ . '/templates/language.tpl');

Xoops_Utils::dumpFile(__FILE__);

echo "Template file:"; echo '</br>';
Xoops_Utils::dumpFile(__DIR__ . '/templates/language.tpl');
$xoops->footer();

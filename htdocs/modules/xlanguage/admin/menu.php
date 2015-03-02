<?php
/**
 * Xlanguage extension module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

$cpt = 0;
$adminmenu[$cpt]['title'] = _MI_XLANGUAGE_INDEX;
$adminmenu[$cpt]['link'] = 'admin/index.php';
$adminmenu[$cpt]['icon'] = 'home.png';

++$cpt;
$adminmenu[$cpt]['title'] = _MI_XLANGUAGE_ADD_LANG;
$adminmenu[$cpt]['link'] = 'admin/index.php?op=add';
$adminmenu[$cpt]['icon'] = 'add.png';

++$cpt;
$adminmenu[$cpt]['title'] = _MI_XLANGUAGE_CREATE_CONFIG;
$adminmenu[$cpt]['link'] = 'admin/index.php?op=createconfig';
$adminmenu[$cpt]['icon'] = 'administration.png';

++$cpt;
$adminmenu[$cpt]['title'] = _MI_XLANGUAGE_ABOUT;
$adminmenu[$cpt]['link'] = 'admin/about.php';
$adminmenu[$cpt]['icon'] = 'about.png';

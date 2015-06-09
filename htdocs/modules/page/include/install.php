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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

function xoops_module_install_page() {

    /*$namemodule = 'page';

    //Create dir '.$namemodule.'/
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule;
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Create file '.$namemodule.'/images/
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images';
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Copy index.html
    $indexFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/include/index.html';
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/index.html');

    //Copy blank.gif
    $blankFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/images/blank.gif';*/
    return true;
}

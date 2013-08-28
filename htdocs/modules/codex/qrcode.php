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
 * @author          Laurent JEN - aka DuGris
 * @version         $Id$
 */

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

if ($xoops->isActiveModule('qrcode')) {
    echo '<img src="' . $xoops->url('/modules/qrcode/include/qrcode.php') . '?url=http://www.xoops.org" title="http://www.xoops.org">';
} else {
    echo 'Oops, Please install qrcode module!';
}
Xoops_Utils::dumpFile(__FILE__);
echo '<hr>';
Xoops_Utils::dumpFile($xoops->path('/modules/qrcode/include/qrcode.php'));
$xoops->footer();
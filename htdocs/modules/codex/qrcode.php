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
 * @author          Laurent JEN - aka DuGris
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

// get a full img tag to show a QR code of a URL
echo $xoops->service('qrcode')
    ->getImgTag('http://www.xoops.org/', array('alt' => 'QR code', 'title'=>'Xoops.org'))
    ->getValue();

if (!$xoops->service('qrcode')->isAvailable()) {
    echo 'Please install a qrcode provider to view this demonstration.';
}

Xoops_Utils::dumpFile(__FILE__);

$xoops->footer();

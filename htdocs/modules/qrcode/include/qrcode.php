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

include dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getinstance();
if ($xoops->isActiveModule('qrcode') && $_GET['url']) {
    $qrcode = new Xoops_qrcode();
    $qrcode->render( $_GET['url'] );
} else {
    $contents = '';
    $size = getimagesize($xoops->url('/images/blank.gif'));
    $handle = fopen($xoops->url('/images/blank.gif'), 'rb');
    while (!feof($handle)) {
        $contents .= fread($handle, 1024);
    }
    fclose($handle);
    echo $contents;
}
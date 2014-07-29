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

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

$image = 'modules/codex/images/sample.jpeg';

$img = $xoops->service('thumbnail')->getImgTag($image, 300, 300)->getValue();
echo $img;

$img = $xoops->service('thumbnail')->getImgTag($image)->getValue();
echo $img;

if (!$xoops->service('thumbnail')->isAvailable()) {
    echo 'Please install a thumbnail provider to view this demonstration.';
}

Xoops_Utils::dumpFile(__FILE__);

$xoops->footer();

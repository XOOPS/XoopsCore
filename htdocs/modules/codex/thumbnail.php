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
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

$image = 'modules/codex/images/sample.jpeg';

// fit in a 300 pixel box
$img = $xoops->service('thumbnail')->getImgTag($image, 300, 300, array('alt' => 'cow elk image'))->getValue();
echo $img;

// use default max pixel sizes
$img = $xoops->service('thumbnail')->getImgTag($image)->getValue();
echo $img;

if (!$xoops->service('thumbnail')->isAvailable()) {
    echo 'Please install a thumbnail provider to view this demonstration.';
}

Xoops_Utils::dumpFile(__FILE__);

$xoops->footer();

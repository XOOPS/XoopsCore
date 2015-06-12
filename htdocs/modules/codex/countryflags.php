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

// demonstrate the CountryFlags service

$img = $xoops->service('countryflag')->getImgTag('US')->getValue();
echo $img;

$img = $xoops->service('countryflag')->getImgTag('US', null, 'medium')->getValue();
echo $img;

$img = $xoops->service('countryflag')->getImgTag('US', null, 'small')->getValue();
echo $img . '<br /><br />';

// instead of a full image tag, we can get just the URL
$url = $xoops->service('countryflag')->getImgUrl('FR')->getValue();
echo '<img src="' . $url . '" />' . '<br /><br />';

// we can add any HTML attributes to the img tag
$img = $xoops->service('countryflag')->getImgTag('SS', array('title' => 'South Sudan was formed in 2011'))->getValue();
echo $img . '<br /><br />';

$img = $xoops->service('countryflag')->getImgTag('XX', array('title' => 'No county XX'))->getValue();
echo $img . '<br /><br />';

if (!$xoops->service('countryflag')->isAvailable()) {
    echo 'Please install a countryflag provider to view this demonstration.';
}

Xoops_Utils::dumpFile(__FILE__);

$xoops->footer();

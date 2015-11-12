<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Request;
use \Punic\Territory;

/**
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

$country = Request::getString('country', 'US');

$form = new Xoops\Form\ThemeForm('Show Flag for a Country', 'form_flag', '', 'post', false, 'horizontal');

$ccode = new Xoops\Form\SelectCountry('Country', 'country', $country);
$form->addElement($ccode, false);
$button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($button);
$form->display();

// demonstrate the CountryFlags service

$img = $xoops->service('countryflag')->getImgTag($country)->getValue();
echo $img;

// we can specify a size
$img = $xoops->service('countryflag')->getImgTag($country, null, 'medium')->getValue();
echo $img;

$img = $xoops->service('countryflag')->getImgTag($country, null, 'small')->getValue();
echo $img;

echo '<br /><br />';

// we can add any HTML attributes to the img tag
$attributes = ['class' => 'img-polaroid', 'title' => Territory::getName($country)];
$img = $xoops->service('countryflag')->getImgTag($country, $attributes)->getValue();
echo $img . '<br /><br />';

if (!$xoops->service('countryflag')->isAvailable()) {
    echo 'Please install a countryflag provider to view this demonstration.';
}

\Xoops\Utils::dumpFile(__FILE__);

$xoops->footer();

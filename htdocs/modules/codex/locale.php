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
use Xoops\Core\Locale\Time;

/**
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

$default = Time::cleanTime();
$dateOnly = Request::getDateTime('date', $default);
$dateAndTime = Request::getDateTime('date_time', $default);

// Date demo form
$form = new Xoops\Form\ThemeForm('Date and Time', 'form_localedates', '', 'post');

$date = new Xoops\Form\DateSelect('Date', 'date', $dateOnly);
$date->setDescription(\XoopsLocale::formatTimestamp($dateOnly, 'custom'));
$form->addElement($date, true);

$date_time = new Xoops\Form\DateTime('Date time', 'date_time', $dateAndTime);
$date_time->setDescription(Time::describeRelativeInterval($dateAndTime));
$form->addElement($date_time, true);

$buttonSubmit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($buttonSubmit);

$form->display();


// Locale selection form
$localePicker = new Xoops\Form\ThemeForm('Change Locale', 'form_locale', '', 'get');
$localeSelect = new Xoops\Form\SelectLocale('Locale', 'lang', Request::getString('lang', 'en_US'));
$localePicker->addElement($localeSelect);
$buttonSubmit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$localePicker->addElement($buttonSubmit);
$localePicker->display();

\Xoops\Utils::dumpFile(__FILE__);

$xoops->footer();

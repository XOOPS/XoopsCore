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
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();
$files = \Xoops\Core\Lists\File::getList(__DIR__);
foreach ($files as $file) {
    if (!in_array($file, array('xoops_version.php', 'index.php'))) {
        $fileName = ucfirst(str_replace('.php', '', $file));
        echo "<a href=\"{$file}\">{$fileName}</a><br/>";
    }
}

$xoops->footer();

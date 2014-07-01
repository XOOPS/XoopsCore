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
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();
if (isset($_GET['delete'])) {
    Xoops_Cache::delete('mykey');
    Xoops_Cache::delete('mykey2', 'myfilesystem');
    Xoops_Cache::delete('mykey3', 'mymemcache');
}

$content = time();

//using 'default' cache file system
if (!$ret = Xoops_Cache::read('mykey')) {
    Xoops_Cache::write('mykey', $content);
    $ret = $content;
}
echo $ret . '<br />';

//setting new cache file system
Xoops_Cache::config('myfilesystem', array('engine' => 'file', 'duration' => 10));
if (!$ret = Xoops_Cache::read('mykey2', 'myfilesystem')) {
    $ret = Xoops_Cache::write('mykey2', $content, 'myfilesystem');
    $ret = $content;
}
echo $ret . '<br />';

//setting new cache memcache system
Xoops_Cache::config('mymemcache', array('engine' => 'memcache', 'duration' => 5));
if (!$ret = Xoops_Cache::read('mykey3', 'mymemcache')) {
    if(Xoops_Cache::write('mykey3', $content, 'mymemcache')) {
        $ret = $content;
    } else {
        $ret = 'No Memcache, no bear';
    }
}
echo $ret . '<br />';

echo '<a href="?nodelete">Refresh</a> - <a href="?delete">Delete caches</a>';

Xoops_Utils::dumpFile(__FILE__ );
$xoops->footer();

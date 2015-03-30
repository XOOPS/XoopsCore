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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/admin_header.php';
$xoops = Xoops::getInstance();
if (isset($_POST["op"]) && ($_POST["op"] == "delfileok")) {
    $dir = PublisherUtils::getUploadDir(true, 'content');
    @unlink($dir . '/' . $_POST["address"]);
    $xoops->redirect($_POST['backto'], 2, _AM_PUBLISHER_FDELETED);
} else {
    $xoops->header();
    echo $xoops->confirm(array('backto' => $_POST['backto'], 'address' => $_POST["address"], 'op' => 'delfileok'), 'pw_delete_file.php', _AM_PUBLISHER_RUSUREDELF, XoopsLocale::YES);
    $xoops->footer();
}

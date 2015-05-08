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
PublisherUtils::cpHeader();
//publisher_adminMenu(-1, _AM_PUBLISHER_ITEMS . " > " . _AM_PUBLISHER_PAGEWRAP);

PublisherUtils::openCollapsableBar('pagewraptable', 'pagewrapicon', _AM_PUBLISHER_PAGEWRAP, _AM_PUBLISHER_PAGEWRAPDSC);

$dir = PublisherUtils::getUploadDir(true, 'content');

if (!preg_match('/777/i', decoct(fileperms($dir)))) {
    echo "<font color='FF0000'><h4>" . _AM_PUBLISHER_PERMERROR . "</h4></font>";
}

// Upload File
echo "<form name='form_name2' id='form_name2' action='pw_upload_file.php' method='post' enctype='multipart/form-data'>";
echo "<table cellspacing='1' width='100%' class='outer'>";
echo "<tr><th colspan='2'>" . _AM_PUBLISHER_UPLOAD_FILE . "</th></tr>";
echo "<tr valign='top' align='left'><td class='head'>" . _AM_PUBLISHER_SEARCH . "</td><td class='even'><input type='file' name='fileupload' id='fileupload' size='30' /></td></tr>";
echo "<tr valign='top' align='left'><td class='head'><input type='hidden' name='MAX_FILE_SIZE' id='op' value='500000' /></td><td class='even'><input type='submit' name='submit' value='" . _AM_PUBLISHER_UPLOAD . "' /></td></tr>";
echo "</table>";
echo "</form>";

// Delete File
$form = new Xoops\Form\ThemeForm(_CO_PUBLISHER_DELETEFILE, "form_name", "pw_delete_file.php");

$pWrap_select = new Xoops\Form\Select(PublisherUtils::getUploadDir(true, 'content'), "address");
$folder = dir($dir);
while ($file = $folder->read()) {
    if ($file != "." && $file != "..") {
        $pWrap_select->addOption($file, $file);
    }
}
$folder->close();
$form->addElement($pWrap_select);

$delfile = "delfile";
$form->addElement(new Xoops\Form\Hidden('op', $delfile));
$submit = new Xoops\Form\Button("", "submit", _AM_PUBLISHER_BUTTON_DELETE, "submit");
$form->addElement($submit);
$form->display();

PublisherUtils::closeCollapsableBar('pagewraptable', 'pagewrapicon');
$xoops->footer();

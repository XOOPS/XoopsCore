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

include_once __DIR__ . "/admin_header.php";
$xoops = Xoops::getInstance();

$op = 'none';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {

    case "importExecute":

        $importfile = (isset($_POST['importfile'])) ? $_POST['importfile'] : 'nonselected';
        $importfile_path = \XoopsBaseConfig::get('root-path') . "/modules/" . $publisher->getModule()->dirname() . "/admin/import/" . $importfile . ".php";
        include_once $importfile_path;
        break;

    case "default":
    default:

        $importfile = 'none';

        PublisherUtils::cpHeader();
        //publisher_adminMenu(-1, _AM_PUBLISHER_IMPORT);

        PublisherUtils::openCollapsableBar('import', 'importicon', _AM_PUBLISHER_IMPORT_TITLE, _AM_PUBLISHER_IMPORT_INFO);

        $module_handler = $xoops->getHandlerModule();

        // WF-Section
        /*$wfs_version = 0;
        $moduleObj = $module_handler->getByDirname('wfsection');
        if ($moduleObj) {
        $from_module_version = round($moduleObj->getVar('version') / 100, 2);
        if (($from_module_version == 1.5) || $from_module_version == 1.04 || $from_module_version == 1.01 || $from_module_version == 2.07 || $from_module_version == 2.06) {
        $importfile_select_array["wfsection"] = "WF-Section " . $from_module_version;
        $wfs_version = $from_module_version;
        }
        } */

        // News
        $news_version = 0;
        $moduleObj = $xoops->getModuleByDirname('news');
        if ($moduleObj) {
            $from_module_version = round($moduleObj->getVar('version') / 100, 2);
            if (($from_module_version >= 1.1)) {
                $importfile_select_array["news"] = "News " . $from_module_version;
                $news_version = $from_module_version;
            }
        }

        // Smartsection
        $smartsection_version = 0;
        $moduleObj = $xoops->getModuleByDirname('smartsection');
        if ($moduleObj) {
            $from_module_version = round($moduleObj->getVar('version') / 100, 2);
            if (($from_module_version >= 1.1)) {
                $importfile_select_array["smartsection"] = "Smartsection " . $from_module_version;
                $smartsection_version = $from_module_version;
            }
        }

        //  XF-Section
        /*$xfs_version = 0;
        $moduleObj = $module_handler->getByDirname('xfsection');
        If ($moduleObj) {
        $from_module_version = round($moduleObj->getVar('version') / 100, 2);
        if ($from_module_version > 1.00) {
        $importfile_select_array["xfsection"] = "XF-Section " . $from_module_version;
        $xfs_version = $from_module_version;
        }
        } */


        if (isset($importfile_select_array) && count($importfile_select_array) > 0) {

            $sform = new Xoops\Form\ThemeForm(_AM_PUBLISHER_IMPORT_SELECTION, "op", xoops_getenv('PHP_SELF'));
            $sform->setExtra('enctype="multipart/form-data"');

            // Partners to import
            $importfile_select = new Xoops\Form\Select('', 'importfile', $importfile);
            $importfile_select->addOptionArray($importfile_select_array);
            $importfile_tray = new Xoops\Form\ElementTray(_AM_PUBLISHER_IMPORT_SELECT_FILE, '&nbsp;');
            $importfile_tray->addElement($importfile_select);
            $importfile_tray->setDescription(_AM_PUBLISHER_IMPORT_SELECT_FILE_DSC);
            $sform->addElement($importfile_tray);

            // Buttons
            $button_tray = new Xoops\Form\ElementTray('', '');
            $hidden = new Xoops\Form\Hidden('op', 'importExecute');
            $button_tray->addElement($hidden);

            $butt_import = new Xoops\Form\Button('', '', _AM_PUBLISHER_IMPORT, 'submit');
            $butt_import->setExtra('onclick="this.form.elements.op.value=\'importExecute\'"');
            $button_tray->addElement($butt_import);

            $butt_cancel = new Xoops\Form\Button('', '', _AM_PUBLISHER_CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $sform->addElement($button_tray);
            /*$sform->addElement(new Xoops\Form\Hidden('xfs_version', $xfs_version));
             $sform->addElement(new Xoops\Form\Hidden('wfs_version', $wfs_version));*/
            $sform->addElement(new Xoops\Form\Hidden('news_version', $news_version));
            $sform->addElement(new Xoops\Form\Hidden('smartsection_version', $smartsection_version));
            $sform->display();
            unset($hidden);
        } else {
            echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-weight: bold; font-size: small; display: block; \">" . _AM_PUBLISHER_IMPORT_NO_MODULE . "</span>";
        }

        // End of collapsable bar

        PublisherUtils::closeCollapsableBar('import', 'importicon');

        break;
}

$xoops->footer();

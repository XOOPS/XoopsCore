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
 * banners module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id: $
 */

/**
 * banner module install suplement
 *
 * @param XoopsModule &$module module being installed
 *
 * @return boolean true if no error
 */
function xoops_module_install_banners(&$module)
{
    $xoops = Xoops::getInstance();
    //$xoops->db();
    //global $xoopsDB;
    XoopsLoad::addMap(array('banners' => dirname(__DIR__) . '/class/helper.php'));
    $helper = Banners::getInstance();
    // Get handler
    $banner_Handler = $helper->getHandlerBanner();
    $client_Handler = $helper->getHandlerBannerclient();
    // update client
    /*$sql = "SHOW COLUMNS FROM " . $xoopsDB->prefix("bannerclient");
    $result = $xoopsDB->queryF($sql);
    if (($rows = $xoopsDB->getRowsNum($result)) == 7) {
        $sql = "SELECT * FROM " . $xoopsDB->prefix("bannerclient");
        $result = $xoopsDB->query($sql);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $extrainfo = $myrow['contact'] . ' - ' . $myrow['email'] . ' - ' . $myrow['login'] . ' - ' . $myrow['passwd'] . ' - ' . $myrow['extrainfo'];
            $sql = "UPDATE `" . $xoopsDB->prefix("bannerclient") . "` SET `extrainfo` = '" .  $extrainfo . "' WHERE `cid` = " . $myrow['cid'];
            $xoopsDB->queryF($sql);
        }
        $sql = "ALTER TABLE " . $xoopsDB->prefix("bannerclient") . " DROP contact, DROP email, DROP login, DROP passwd";
        $xoopsDB->queryF($sql);
        $sql = "ALTER TABLE " . $xoopDB->prefix("bannerclient") . " ADD `uid` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `cid`";
        $xoopsDB->queryF($sql);
    }*/
    // update banner
    /*$sql = "SHOW COLUMNS FROM " . $xoopsDB->prefix("banner");
    $result = $xoopsDB->queryF($sql);
    if (($rows = $xoopsDB->getRowsNum($result)) == 10) {
        $sql = "ALTER TABLE " . $xoopsDB->prefix("banner") . " CHANGE `date` `datestart` INT( 10 ) NOT NULL DEFAULT '0'";
        $xoopsDB->queryF($sql);
        $sql = "ALTER TABLE " . $xoopsDB->prefix("banner") . " ADD `dateend` INT( 10 ) NOT NULL DEFAULT '0' AFTER `datestart`";
        $xoopsDB->queryF($sql);
        $sql = "ALTER TABLE " . $xoopsDB->prefix("banner") . " ADD `status` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `htmlcode`";
        $xoopsDB->queryF($sql);
    }*/
    // update bannerfinish
    /*$sql = "SHOW COLUMNS FROM " . $xoopsDB->prefix("bannerfinish");
    $result = $xoopsDB->queryF($sql);
    if (($rows = $xoopsDB->getRowsNum($result)) == 6) {
        $sql = "SELECT * FROM " . $xoopsDB->prefix("bannerfinish");
        $result = $xoopsDB->query($sql);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $sql = "INSERT INTO `" . $xoopsDB->prefix("banner") . "` (`cid`, `imptotal`, `impmade`, `clicks`, `imageurl`, `clickurl`, `datestart`, `dateend`, `htmlbanner`, `htmlcode`, `status`) VALUES (" . $myrow['cid'] . ", 0, " . $myrow['impressions'] . ", " . $myrow['clicks'] . ", 0, '', " . $myrow['datestart'] . ", " . $myrow['dateend'] . ", 0, '', 0)";
            $xoopsDB->queryF($sql);
        }
    }*/

/* this should be in system upgrade, not module install
    TODO: Add to upgrade script and remove from here
    // delete banners and my_ip
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'banners'";
    $xoopsDB->queryF($sql);
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'my_ip'";
    $xoopsDB->queryF($sql);
*/
	$xoops_root_path = \XoopsBaseConfig::get('root-path');
	$xoops_upload_url = \XoopsBaseConfig::get('uploads-url');
	
    // create folder "banners"
    $dir = $xoops_root_path . "/uploads/banners";
    if (!is_dir($dir)) {
        mkdir($dir, 0777);
        chmod($dir, 0777);
    }
    //Copy index.html
    $file = $xoops_root_path . "/uploads/banners/index.html";
    if (!is_file($file)) {
        copy($xoops_root_path . "/modules/banners/images/index.html", $file);
    }
    //Copy blank.gif
    $file = $xoops_root_path . "/uploads/banners/blank.gif";
    if (!is_file($file)) {
        copy($xoops_root_path . "/uploads/blank.gif", $file);
    }
    //Copy .htaccess
    $file = $xoops_root_path . "/uploads/banners/.htaccess";
    if (!is_file($file)) {
        copy($xoops_root_path . "/uploads/.htaccess", $file);
    }

/* this should be in system upgrade, not module install
    TODO: Add to upgrade script and remove from here
    // Copy banner to banners_banner
    $dbManager = new XoopsDatabaseManager();
    $map = array(
        'bid'        => 'banner_bid',
        'cid'        => 'banner_cid',
        'imptotal'   => 'banner_imptotal',
        'impmade'    => 'banner_impmade',
        'clicks'     => 'banner_clicks',
        'imageurl'   => 'banner_imageurl',
        'clickurl'   => 'banner_clickurl',
        'date'       => 'banner_datestart',
        'htmlbanner' => 'banner_htmlbanner',
        'htmlcode'   => 'banner_htmlcode',
    );
    $dbManager->copyFields($map, 'banner', 'banners_banner', false);

    // Copy bannerclient to banners_bannerclient
    $dbManager = new XoopsDatabaseManager();
    $map = array(
        'cid'       => 'bannerclient_cid',
        'name'      => 'bannerclient_name',
        'extrainfo' => 'bannerclient_extrainfo',
    );
    $dbManager->copyFields($map, 'bannerclient', 'banners_bannerclient', false);

    // Modification of imported banners below xoops 2.6
    $banner_arr = $banner_Handler->getall();
    foreach (array_keys($banner_arr) as $i) {
        $namefile = substr_replace($banner_arr[$i]->getVar('banner_imageurl'),'',0,strlen(\XoopsBaseConfig::get('url') . '/images/banners/'));
        $pathfile_image =  $xoops_root_path . '/images/banners/' . $namefile;
        $pathfile_upload =  $xoops_root_path . '/uploads/banners/' . $namefile;
        $obj = $banner_Handler->get($banner_arr[$i]->getVar('banner_bid'));
        if (is_file($pathfile_image)){
            copy($pathfile_image, $pathfile_upload);
            unlink($pathfile_image);
            $obj->setVar("banner_imageurl",  \XoopsBaseConfig::get('uploads-url') . '/banners/' . $namefile);
        }
        $obj->setVar("banner_status", 1);
        $banner_Handler->insert($obj);
    }
*/

    // create XOOPS client
    $client_name = 'XOOPS';
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('bannerclient_name', $client_name));
    $criteria->setLimit(1);
    $client_arr = $client_Handler->getAll($criteria);
    if (count($client_arr) == 0) {
        $obj = $client_Handler->create();
        $obj->setVar("bannerclient_uid", 0);
        $obj->setVar("bannerclient_name", $client_name);
        $obj->setVar("bannerclient_extrainfo", 'XOOPS Dev Team');
        $newclient_id = $client_Handler->insert($obj);
    } else {
        foreach (array_keys($client_arr) as $i) {
            $newclient_id = $client_arr[$i]->getVar("bannerclient_cid");
        }
    }

    // create banner in XOOPS client
    $banners = array(
        "xoops_flashbanner2.swf" => "http://www.xoops.org/",
        "xoops_banner_2.gif" => "http://www.xoops.org/",
        "banner.swf" => "http://www.xoops.org/"
    );
    foreach ($banners as $k => $v) {
        //Copy banner
        $file = $xoops_root_path . "/uploads/banners/" . $k;
        $copy_file = $xoops_root_path . "/modules/banners/images/" . $k;
        if (!is_file($file) && is_file($copy_file)) {
            copy($copy_file, $file);
        }
        $obj = $banner_Handler->create();
        $obj->setVar("banner_cid", $newclient_id);
        $obj->setVar("banner_clickurl", $v);
        $obj->setVar("banner_imageurl", $xoops_upload_url . '/banners/' . $k);
        $obj->setVar("banner_datestart", time());
        $obj->setVar("banner_dateend", 0);
        $obj->setVar("banner_status", 1);
        $obj->setVar("banner_imptotal", 0);
        $obj->setVar("banner_htmlbanner", 0);
        $obj->setVar("banner_htmlcode", '');
        $banner_Handler->insert($obj);
    }
    return true;
}

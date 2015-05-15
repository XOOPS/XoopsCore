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
 * avatars module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */

/**
 * xoops_module_install_avatars - install supplement for avatars module
 *
 * @param object &$module module object
 *
 * @return boolean true on success
 */
function xoops_module_install_avatars(&$module)
{
    /* This is all upgrade related, not install.
       TODO: Add to upgrade script and remove from here

    global $xoopsDB;
    // delete avatar_allow_upload, avatar_width, avatar_height, avatar_maxsize and avatar_minposts
    $xoops = Xoops::getInstance();
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` IN "
    . " ('avatar_allow_upload', 'avatar_width', 'avatar_height', 'avatar_maxsize', 'avatar_minposts') ";
    $xoopsDB->queryF($sql);

    $dbManager = new XoopsDatabaseManager();
    $map = array(
        'avatar_id'       => 'avatar_id',
        'avatar_file'     => 'avatar_file',
        'avatar_name'     => 'avatar_name',
        'avatar_mimetype' => 'avatar_mimetype',
        'avatar_created'  => 'avatar_created',
        'avatar_display'  => 'avatar_display',
        'avatar_weight'   => 'avatar_weight',
        'avatar_type'     => 'avatar_type',
    );
    $dbManager->copyFields($map, 'avatar', 'avatars_avatar', false);

    $map = array(
        'avatar_id' => 'avatar_id',
        'user_id'   => 'user_id',
    );
    $dbManager->copyFields($map, 'avatar_user_link', 'avatars_user_link', false);
    */

	$xoops_root_path = \XoopsBaseConfig::get('root-path');
	
    // create folder "avatars"
    $dir = $xoops_root_path . "/uploads/avatars";
    if (!is_dir($dir)) {
        mkdir($dir, 0777);
        chmod($dir, 0777);
    }
    //Copy index.html
    $file = $xoops_root_path . "/uploads/avatars/index.html";
    if (!is_file($file)) {
        copy($xoops_root_path . "/modules/avatars/images/index.html", $file);
    }
    //Copy blank.gif
    $file = $xoops_root_path . "/uploads/avatars/blank.gif";
    if (!is_file($file)) {
        copy($xoops_root_path . "/modules/avatars/images/blank.gif", $file);
    }
    //Copy .htaccess
    $file = $xoops_root_path . "/uploads/avatars/.htaccess";
    if (!is_file($file)) {
        copy($xoops_root_path . "/uploads/.htaccess", $file);
    }
    return true;
}

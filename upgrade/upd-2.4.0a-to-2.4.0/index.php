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
 * Upgrader from to 2.4.0 alpha to 2.4.0 final
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.4.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      trabis <lusopoemas@gmail.com>
 * @version     $Id$
 */

class upgrade_240a extends xoopsUpgrade
{
    public $tasks = array('config', 'configoption');

    /**
     * Check if cpanel config already exists

     */
    public function check_config()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = "SELECT COUNT(*) FROM `" . $db->prefix('config') . "` WHERE `conf_name` IN ('systemkey', 'soap_xoops_username', 'soap_xoops_password', 'soap_soapclient', 'soap_wdsl', 'soap_keepclient', 'soap_filterperson', 'soap_proxyhost', 'soap_proxyport', 'soap_proxyusername', 'soap_proxypassword', 'soap_timeout', 'soap_responsetimeout', 'soap_fieldmapping', 'soap_provisionning', 'soap_provisionning_group')";
        if (!$result = $db->queryF($sql)) {
            return false;
        }
        list($count) = $db->fetchRow($result);
        return ($count > 0) ? false : true;
    }

    public function check_configoption()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = "SELECT COUNT(*) FROM `" . $db->prefix('configoption') . "` WHERE `confop_name` IN ('_MD_AM_AUTH_CONFOPTION_SOAP')";
        if (!$result = $db->queryF($sql)) {
            return false;
        }
        list($count) = $db->fetchRow($result);
        return ($count == 1) ? false : true;
    }

    public function apply_config()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $configs = array(
            'systemkey', 'soap_xoops_username', 'soap_xoops_password', 'soap_soapclient', 'soap_wdsl',
            'soap_keepclient', 'soap_filterperson', 'soap_proxyhost', 'soap_proxyport', 'soap_proxyusername',
            'soap_proxypassword', 'soap_timeout', 'soap_responsetimeout', 'soap_fieldmapping', 'soap_provisionning',
            'soap_provisionning_group'
        );
        foreach ($configs as $config) {
            $config_installed = false;
            $sql = "SELECT COUNT(*) FROM " . $db->prefix('config') . " WHERE `conf_name` = '{$config}' AND `conf_modid` = 0";
            if ($result = $db->queryF($sql)) {
                list($count) = $db->fetchRow($result);
                if ($count == 1) {
                    $config_installed = true;
                }
            }
            if ($config_installed) {
                $sql = "DELETE FROM " . $db->prefix('config') . " WHERE `conf_name` = '{$config}' AND `conf_modid` = 0";
                if (!$db->queryF($sql)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function apply_configoption()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $configoption_installed = false;
        $sql = "SELECT COUNT(*) FROM `" . $db->prefix('configoption') . "`" . " WHERE `confop_name` = '_MD_AM_AUTH_CONFOPTION_SOAP' AND `confop_value` = 'soap'";
        if ($result = $db->queryF($sql)) {
            list($count) = $db->fetchRow($result);
            if ($count == 1) {
                $configoption_installed = true;
            }
        }

        if ($configoption_installed) {
            $sql = "DELETE FROM " . $db->prefix('configoption') . " WHERE `confop_name` = '_MD_AM_AUTH_CONFOPTION_SOAP' AND `confop_value` = 'soap'";
            if (!$db->queryF($sql)) {
                return false;
            }
        }

        return true;
    }

    public function __construct()
    {
        xoopsUpgrade::__construct(basename(__DIR__));
    }
}

$upg = new upgrade_240a();
return $upg;

<?php
/**
 * Functions handling module configs
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      art
 */

if (!defined("FRAMEWORKS_ART_FUNCTIONS_CONFIG")):
    define("FRAMEWORKS_ART_FUNCTIONS_CONFIG", true);

    /**
     * Load configs of a module
     *
     * @param    string    $dirname    module dirname
     * @return    array
     */
    function mod_loadConfig($dirname = "")
    {
        $xoops = Xoops::getInstance();
        if (empty($dirname) && !$xoops->isModule()) {
            return null;
        }
        $dirname = !empty($dirname) ? $dirname : $xoops->module->getVar("dirname");

        if ($xoops->isModule() && $xoops->module->getVar("dirname", "n") == $dirname) {
            if (!empty($xoops->moduleConfig)) {
                $moduleConfig = $xoops->moduleConfig;
            } else {
                return null;
            }
        } else {

            if (!$moduleConfig = \Xoops\Cache::read("{$dirname}_config")) {
                $moduleConfig = mod_fetchConfig($dirname);
                \Xoops\Cache::write("{$dirname}_config", $moduleConfig);
            }
        }
        if ($customConfig = @include \XoopsBaseConfig::get('root-path') . "/modules/{$dirname}/include/plugin.php") {
            $moduleConfig = array_merge($moduleConfig, $customConfig);
        }
        return $moduleConfig;
    }

    /**
     * @param string $dirname
     * @return array
     */
    function mod_loadConfg($dirname = "")
    {
        return mod_loadConfig($dirname);
    }

    /**
     * Fetch configs of a module from database
     *
     * @param    string    $dirname    module dirname
     * @return    array
     */
    function mod_fetchConfig($dirname = "")
    {
        if (empty($dirname)) {
            return null;
        }

        $xoops = Xoops::getInstance();
        if (!$module = $xoops->getModuleByDirname($dirname)) {
            trigger_error("Module '{$dirname}' does not exist", E_USER_WARNING);
            return null;
        }
        $config_handler = $xoops->getHandlerConfig();
        $criteria = new CriteriaCompo(new Criteria('conf_modid', $module->getVar('mid')));
        $configs = $config_handler->getConfigs($criteria);
        $moduleConfig = array();
        foreach (array_keys($configs) as $i) {
            $moduleConfig[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
        }
        unset($module, $configs);

        return $moduleConfig;
    }

    function mod_fetchConfg($dirname = "")
    {
        return mod_fetchConfig($dirname);
    }

    /**
     * clear config cache of a module
     *
     *
     * @param    string    $dirname    module dirname
     * @return    bool
     */
    function mod_clearConfig($dirname = "")
    {
        if (empty($dirname)) {
            return false;
        }


        return \Xoops\Cache::delete("{$dirname}_config");
    }

    function mod_clearConfg($dirname = "")
    {
        return mod_clearConfig($dirname);
    }

endif;

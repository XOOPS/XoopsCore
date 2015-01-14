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
 * XXX
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id: $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

function xoops_module_update_search(XoopsModule &$module)
{
    $xoops = Xoops::getInstance();
    // Copy old configs in new configs and delete old configs
    $config_handler = $xoops->getHandlerConfig();
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('conf_modid', 0));
    $criteria->add(new Criteria('conf_catid', 5));
    $configs = $config_handler->getConfigs($criteria);
    $confcount = count($configs);
    if ($confcount > 0) {
        for ($i = 0; $i < $confcount; $i++) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('conf_modid', $module->getVar('mid')));
            $criteria->add(new Criteria('conf_name', $configs[$i]->getvar('conf_name')));
            $new_configs = $config_handler->getConfigs($criteria);
            $new_confcount = count($new_configs);
            if ($new_confcount > 0) {
                for ($j = 0; $j < $new_confcount; $j++) {
                    $obj = $config_handler->getConfig($new_configs[$j]->getvar('conf_id'));
                }
                $obj->setVar("conf_value", $configs[$i]->getvar('conf_value'));
                $config_handler->insertConfig($obj);
                $config_handler->deleteConfig($configs[$i]);
            }

        }

    }
    return true;

}

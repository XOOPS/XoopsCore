<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * System update functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * xoops_module_update_system
 *
 * @param XoopsModule &$module
 *
 * @return bool
 */
function xoops_module_update_system(XoopsModule &$module)
{
    $xoops = Xoops::getInstance();
    if ($module->getVar('version') == 100) {
        $qb = $xoops->db()->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $sql = $qb->select('t1.tpl_id')
            ->fromPrefix('tplfile', 't1')
            ->fromPrefix('tplfile', 't2')
            ->where($eb->eq('t1.tpl_module', 't2.tpl_module '))
            ->andWhere($eb->eq('t1.tpl_tplset', 't2.tpl_tplset'))
            ->andWhere($eb->eq('t1.tpl_file', 't2.tpl_file'))
            ->andWhere($eb->eq('t1.tpl_id', 't2.tpl_id'));
        $result = $sql->execute();
        $tplids = array();
        while (list($tplid) = $result->fetch(PDO::FETCH_NUM)) {
            $tplids[] = $tplid;
        }
        if (count($tplids) > 0) {
            $tplfile_handler = $xoops->getHandlerTplfile();
            $duplicate_files = $tplfile_handler->getTplObjects(
                new Criteria('tpl_id', "(".implode(',', $tplids).")", "IN")
            );

            if (count($duplicate_files) > 0) {
                foreach (array_keys($duplicate_files) as $i) {
                    $tplfile_handler->deleteTpl($duplicate_files[$i]);
                }
            }
        }
    }
    // Copy old configs in new configs and delete old configs
    // Not for conf_catid =5 (Update in search extension)
    // Not for conf_catid =6 (Update in mail user extension)
    $config_handler = $xoops->getHandlerConfig();
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('conf_modid', 0));
    $criteria->add(new Criteria('conf_catid', 5, '!='));
    $criteria->add(new Criteria('conf_catid', 6, '!='));
    $configs = $config_handler->getConfigs($criteria);
    $confcount = count($configs);
    if ($confcount > 0) {
        for ($i = 0; $i < $confcount; $i++) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('conf_modid', 1));
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

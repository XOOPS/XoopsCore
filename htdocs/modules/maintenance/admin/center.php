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
 * maintenance extensions
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         maintenance
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

include __DIR__ . '/header.php';
// Get main instance
$system = System::getInstance();
$xoops = Xoops::getInstance();

// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'list', 'string');

// Call Header
$xoops->header('admin:maintenance/maintenance_center.tpl');

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('center.php');

switch ($op) {

    case 'list':
    default:
        // Define tips
        $admin_page->addTips(_AM_MAINTENANCE_CENTER_TIPS);
        $admin_page->renderTips();
        $form = $xoops->getModuleForm(null, 'maintenance');
        $form->getMaintenance();
        $form->display();
        break;

    case 'maintenance_save':
        // Check security
        if (!$xoops->security()->check()) {
            $xoops->redirect('center.php', 3, implode('<br />', $xoops->security()->getErrors()));
        }
        $admin_page->addItemButton(_AM_MAINTENANCE_CENTER_RETURN, 'center.php', 'application-view-detail');
        $admin_page->renderButton();

        $session = $system->cleanVars($_REQUEST, 'session', 1, 'int');
        $cache = $system->cleanVars($_REQUEST, 'cache', array(), 'array');
        $tables = $system->cleanVars($_REQUEST, 'tables', array(), 'array');
        $tables_op = $system->cleanVars($_REQUEST, 'maintenance', array(), 'array');
        $xoops->db();
        global $xoopsDB;
        $db = $xoopsDB;
        //Cache
        $res_cache = $system->CleanCache($cache);
        if (!empty($cache)) {
            for ($i = 0; $i < count($cache); $i++) {
                switch ($cache[$i]) {
                    case 1:
                        $xoops->tpl()->assign('smarty_cache', true);
                        $xoops->tpl()->assign('result_smarty_cache', sprintf(_AM_MAINTENANCE_CENTER_RESULT_SMARTY_CACHE, $res_cache['smarty_cache']));
                        break;

                    case 2:
                        $xoops->tpl()->assign('smarty_compile', true);
                        $xoops->tpl()->assign('result_smarty_compile', sprintf(_AM_MAINTENANCE_CENTER_RESULT_SMARTY_COMPILE, $res_cache['smarty_compile']));
                        break;

                    case 3:
                        $xoops->tpl()->assign('xoops_cache', true);
                        $xoops->tpl()->assign('result_xoops_cache', sprintf(_AM_MAINTENANCE_CENTER_RESULT_XOOPS_CACHE, $res_cache['xoops_cache']));
                        break;
                }
            }
        }
        //Session
        if ($session == 1) {
            $result = $db->queryF('TRUNCATE TABLE ' . $db->prefix('session'));
            $result ? $result_session = true : $result_session = false;
            $xoops->tpl()->assign('result_session', $result_session);
            $xoops->tpl()->assign('session', true);
        }
        //Maintenance tables
        if (!empty($tables) && !empty($tables_op)) {
            $tab = array();
            for ($i = 0; $i < 4; $i++) {
                $tab[$i] = $i + 1;
            }
            $tab1 = array();
            for ($i = 0; $i < 4; $i++) {
                if (in_array($tab[$i], $tables_op)) {
                    $tab1[$i] = $tab[$i];
                } else {
                    $tab1[$i] = '0';
                }
            }
            unset($tab);
            for ($i = 0; $i < count($tables); $i++) {
                $result_arr['table'] = $db->prefix . $tables[$i];
                for ($j = 0; $j < 4; $j++) {
                    switch ($tab1[$j]) {
                        case 1:
                            //Optimize
                            $result = $db->queryF('OPTIMIZE TABLE ' . $db->prefix . $tables[$i]);
                            $result ? $result_arr['optimize'] = true : $result_arr['optimize'] = false;
                            break;

                        case 2:
                            //Tables
                            $result = $db->queryF('CHECK TABLE ' . $db->prefix . $tables[$i]);
                            $result ? $result_arr['check'] = true : $result_arr['check'] = false;
                            break;

                        case 3:
                            //Repair
                            $result = $db->queryF('REPAIR TABLE ' . $db->prefix . $tables[$i]);
                            $result ? $result_arr['repair'] = true : $result_arr['repair'] = false;
                            break;

                        case 4:
                            //Analyze
                            $result = $db->queryF('ANALYZE TABLE ' . $db->prefix . $tables[$i]);
                            $result ? $result_arr['analyse'] = true : $result_arr['analyse'] = false;
                            break;
                    }
                }
                $xoops->tpl()->appendByRef('result_arr', $result_arr);
                unset($result_arr);
            }
            $xoops->tpl()->assign('maintenance', true);
        }
        break;
}
$xoops->footer();

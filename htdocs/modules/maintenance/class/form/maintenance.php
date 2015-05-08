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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MaintenanceMaintenanceForm extends Xoops\Form\ThemeForm
{
    /**
     * @param null $obj
     */
    public function __construct($obj = null)
    {
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function getMaintenance()
    {
        $maintenance = new Maintenance();
        parent::__construct('', "form_maintenance", "center.php", 'post', true);

        $cache = new Xoops\Form\Select(_AM_MAINTENANCE_CENTER_CACHE, "cache", '', 3, true);
        $cache->setDescription(XOOPS_VAR_PATH . "/cache/smarty_cache/<br />" . XOOPS_VAR_PATH . "/cache/smarty_compile/<br />" . XOOPS_VAR_PATH . "/cache/xoops_cache/");
        $cache_arr = array(1 => _AM_MAINTENANCE_CENTER_SMARTY_CACHE, 2 => _AM_MAINTENANCE_CENTER_SMARTY_COMPILE, 3 => _AM_MAINTENANCE_CENTER_XOOPS_CACHE);
        $cache->addOptionArray($cache_arr);
        $this->addElement($cache);

        $this->addElement(new Xoops\Form\RadioYesNo(_AM_MAINTENANCE_CENTER_SESSION, 'session', ''));

        $tables_tray = new Xoops\Form\ElementTray(_AM_MAINTENANCE_CENTER_TABLES, ' ');
        $tables_tray->setDescription(_AM_MAINTENANCE_CENTER_TABLES_DESC);
        $select_tables = new Xoops\Form\Select('', "tables", '', 7, true);
        $select_tables->addOptionArray($maintenance->displayTables(true));
        $tables_tray->addElement($select_tables, false);
        $choice = new Xoops\Form\Select('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . _AM_MAINTENANCE_AND . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', "maintenance", '', 4, true);
        $options = array(
            '1' => _AM_MAINTENANCE_CENTER_CHOICE1, '2' => _AM_MAINTENANCE_CENTER_CHOICE2,
            '3' => _AM_MAINTENANCE_CENTER_CHOICE3, '4' => _AM_MAINTENANCE_CENTER_CHOICE4
        );
        $choice->addOptionArray($options);
        $tables_tray->addElement($choice, false);
        $this->addElement($tables_tray);

        $this->addElement(new Xoops\Form\Hidden("op", "maintenance_save"));
        $this->addElement(new Xoops\Form\Button("", "maintenance_save", XoopsLocale::A_SUBMIT, "submit"));
    }

    /**
     * @return void
     */
    public function getDump()
    {
        $xoops = Xoops::getInstance();
        $maintenance = new Maintenance();
        parent::__construct('', "form_dump", "dump.php", 'post', true);

        $dump_tray = new Xoops\Form\ElementTray(_AM_MAINTENANCE_DUMP_TABLES_OR_MODULES, '');
        $select_tables1 = new Xoops\Form\Select('', "dump_tables", '', 7, true);
        $select_tables1->addOptionArray($maintenance->displayTables(true));
        $dump_tray->addElement($select_tables1, false);
        $ele = new Xoops\Form\Select('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . _AM_MAINTENANCE_OR . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'dump_modules', '', 7, true);
        $module_list = XoopsLists::getModulesList();
        $module_handler = $xoops->getHandlerModule();
        foreach ($module_list as $file) {
            if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/' . $file . '/xoops_version.php')) {
                clearstatcache();
                $file = trim($file);
                $module = $module_handler->create();
                $module->loadInfo($file);
                if ($module->getInfo('tables') && $xoops->isActiveModule($file)) {
                    $ele->addOption($module->getInfo('dirname'), $module->getInfo('name'));
                }
                unset($module);
            }
        }
        $dump_tray->addElement($ele);
        $this->addElement($dump_tray);

        $this->addElement(new Xoops\Form\RadioYesNo(_AM_MAINTENANCE_DUMP_DROP, 'drop', 1));

        $this->addElement(new Xoops\Form\Hidden("op", "dump_save"));
        $this->addElement(new Xoops\Form\Button("", "dump_save", XoopsLocale::A_SUBMIT, "submit"));
    }
}

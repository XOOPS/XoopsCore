<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Module\Plugin;

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
 * Facilitates adding configuration requirements for a plugin to the configuration array for
 * a consuming module.
 *
 * This is used for the argument for the event 'system.module.update.configs', and the listeners,
 * presumably plugin providers, can access module details and add to the configuration as needed.
 *
 * @copyright   2013-2015 XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Richard Griffith <richard@geekwright.com>
 */
class ConfigCollector
{
    public $module;

    public $configs;

    /**
     * __construct
     *
     * @param XoopsModule $module  consuming module being installed
     * @param array       $configs configuration array for the consuming module
     */
    public function __construct(XoopsModule $module, &$configs)
    {
        $this->module = $module;
        $this->configs = &$configs;
    }

    public function add($pluginConfigs)
    {
        if (is_array($pluginConfigs) && !empty($pluginConfigs)) {
            foreach ($pluginConfigs as $config) {
                $this->configs[] = $config;
            }
        }
    }

    public function module()
    {
        return $this->module;
    }
}

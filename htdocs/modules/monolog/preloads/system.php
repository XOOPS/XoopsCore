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
 * MonologLogger system preloads
 *
 * @category  MonologLogger
 * @package   MonologLogger
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class MonologSystemPreload extends XoopsPreloadItem
{

    /**
     * eventSystemPreferencesSave
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventSystemPreferencesSave($args)
    {
        $configs = array();
        $cache_key = 'module_monolog_configs';

        if (isset($_REQUEST['monolog_enable'])) {
            \Xoops_Cache::delete($cache_key);

            $helper = \Xoops::getInstance()->getModuleHelper('monolog');
            $configs['monolog_enable'] = (bool) $helper->getConfig('monolog_enable');
            $configs['include_blocks'] = (bool) $helper->getConfig('include_blocks');
            $configs['include_deprecated'] = (bool) $helper->getConfig('include_deprecated');
            $configs['include_extra'] = (bool) $helper->getConfig('include_extra');
            $configs['include_queries'] = (bool) $helper->getConfig('include_queries');
            $configs['include_timers'] = (bool) $helper->getConfig('include_timers');
            $configs['logging_threshold'] = $helper->getConfig('logging_threshold');
            $configs['log_file_path'] = $helper->getConfig('log_file_path');

            $monolog_configs=serialize($configs);
            \Xoops_Cache::write($cache_key, $monolog_configs);
        }

    }
}

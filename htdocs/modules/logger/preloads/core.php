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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         logger
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

/**
 * LegacyLogger core preloads
 *
 * @category  LegacyLogger
 * @package   LegacyLogger
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class LoggerCorePreload extends XoopsPreloadItem
{
    private static $query_start_time = 0;
    private static $query_stop_time = 0;

    /**
     * getConfigs
     *
     * @return array of config options
     */
    private static function getConfigs()
    {
        static $configs = null;

        if (is_null($configs)) {
            $xoops = Xoops::getInstance();
            $user_groups = $xoops->isUser() ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
            $moduleperm_handler = $xoops->getHandlerGroupperm();
            $helper = $xoops->getModuleHelper('logger');
            $mid = $helper->getModule()->getVar('mid');
            if ($moduleperm_handler->checkRight('use_logger', 0, $user_groups, $mid)) {
                // get default settings
                $configs['logger_enable'] = $helper->getConfig('logger_enable');
                $configs['logger_popup'] = $helper->getConfig('logger_popup');
                $configs['debug_smarty_enable'] = $helper->getConfig('debug_smarty_enable');
                // override with settings
                $uchelper = $xoops->getModuleHelper('userconfigs');
                if ($xoops->isUser() && $uchelper) {
                    $config_handler = $uchelper->getHandlerConfig();
                    $user_configs =
                        $config_handler->getConfigsByUser($xoops->user->getVar('uid'), $mid);
                    if (array_key_exists('logger_enable', $user_configs)) {
                        $configs['logger_enable'] = $user_configs['logger_enable'];
                    }
                    if (array_key_exists('logger_popup', $user_configs)) {
                        $configs['logger_popup'] = $user_configs['logger_popup'];
                    }
                    if (array_key_exists('debug_smarty_enable', $user_configs)) {
                        $configs['debug_smarty_enable'] = $user_configs['debug_smarty_enable'];
                    }
                }
            } else {
                // user has no permissions, turn everything off
                $configs['logger_enable'] = 0;
                $configs['logger_popup'] = 0;
                $configs['debug_smarty_enable'] = 0;
            }
        }

        return $configs;
    }

    /**
     * eventCoreException
     *
     * @param Exception $e an exception
     *
     * @return void
     */
    public static function eventCoreException($e)
    {
        LegacyLogger::getInstance()->addException($e);
    }

    /**
     * eventCoreIncludeCommonStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreIncludeCommonStart($args)
    {
        //Load may fail is cache was erased
        XoopsLoad::addMap(array('legacylogger' => dirname(dirname(__FILE__)) . '/class/legacylogger.php'));
        LegacyLogger::getInstance()->enable();//until we get a db connection debug is enabled
        LegacyLogger::getInstance()->startTime();
        LegacyLogger::getInstance()->startTime('XOOPS Boot');
    }

    /**
     * core.database.noconn
     *
     * @param array $args arguments
     *
     * @return void
     */
    public static function eventCoreDatabaseNoconn($args)
    {
        if (!class_exists('LegacyLogger')) {
            return;
        }
        /* @var $db XoopsConnection */
        $db = $args[0];
        LegacyLogger::getInstance()->addQuery('', $db->error(), $db->errno());
    }

    /**
     * eventCoreDatabaseNodb
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDatabaseNodb($args)
    {
        if (!class_exists('LegacyLogger')) {
            return;
        }
        /* @var $db XoopsConnection */
        $db = $args[0];
        LegacyLogger::getInstance()->addQuery('', $db->error(), $db->errno());
    }

    /**
     * eventCoreDatabaseQueryStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDatabaseQueryStart($args)
    {
        self::$query_start_time = microtime(true);
    }

    /**
     * eventCoreDatabaseQueryEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDatabaseQueryEnd($args)
    {
        self::$query_stop_time = microtime(true);
    }

    /**
     * eventCoreDatabaseQuerySuccess
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDatabaseQuerySuccess($args)
    {
        $sql = $args[0];
        LegacyLogger::getInstance()
            ->addQuery($sql, null, null, self::$query_stop_time - self::$query_start_time);
    }

    /**
     * eventCoreDatabaseQueryFailure
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDatabaseQueryFailure($args)
    {
        /* @var $db XoopsConnection */
        $sql = $args[0];
        $db = $args[1];
        if (method_exists($db, 'error')) {
            LegacyLogger::getInstance()
                ->addQuery($sql, $db->error(), $db->errno(), self::$query_stop_time - self::$query_start_time);
        } else {
            LegacyLogger::getInstance()
                ->addQuery($sql, $db->errorInfo(), $db->errorCode(), self::$query_stop_time - self::$query_start_time);
        }
    }

    /**
     * eventCoreIncludeCommonConfigsSuccess
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreIncludeCommonConfigsSuccess($args)
    {
        /*
        $xoops = Xoops::getInstance();
        $logger = LegacyLogger::getInstance();
        $configs = self::getConfigs();
        if ($configs['logger_enable']) {
            $xoops->loadLocale();
            $xoops->loadLanguage('main', 'logger');
            $logger->enable();
        } else {
            $xoops->disableErrorReporting();
        }
        */
    }

    /**
     * eventCoreIncludeCommonAuthSuccess
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreIncludeCommonAuthSuccess($args)
    {
        $xoops = Xoops::getInstance();
        $logger = LegacyLogger::getInstance();
        $configs = self::getConfigs();
        if ($configs['logger_enable']) {
            $xoops->loadLocale();
            $xoops->loadLanguage('main', 'logger');
            $logger->setConfigs($configs);
            $logger->enable();
        } else {
            $logger->disable();
        }
    }

    /**
     * eventCoreIncludeCommonEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        XoopsLoad::addMap(array('logger' => dirname(dirname(__FILE__)) . '/class/logger.php'));

        $logger = LegacyLogger::getInstance();
        $logger->stopTime('XOOPS Boot');
        $logger->startTime('Module init');
    }

    /**
     * eventCoreTemplateConstructStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreTemplateConstructStart($args)
    {
        $tpl = $args[0];
        $configs = self::getConfigs();
        if ($configs['logger_enable']) {
            $tpl->debugging_ctrl = 'URL';
        }
        if ($configs['debug_smarty_enable']) {
                $tpl->debugging = true;
        }
    }

    /**
     * eventCoreThemeRenderStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreThemeRenderStart($args)
    {
        LegacyLogger::getInstance()->startTime('Page rendering');
    }

    /**
     * eventCoreThemeRenderEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreThemeRenderEnd($args)
    {
        LegacyLogger::getInstance()->stopTime('Page rendering');
    }

    /**
     * eventCoreThemeCheckcacheSuccess
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreThemeCheckcacheSuccess($args)
    {
        $template = $args[0];
        $theme = $args[1];
        LegacyLogger::getInstance()
            ->addExtra($template, sprintf('Cached (regenerates every %d seconds)', $theme->contentCacheLifetime));
    }

    /**
     * eventCoreThemeblocksBuildblockStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreThemeblocksBuildblockStart($args)
    {
        /* @var $block XoopsBlock */
        $block = $args[0];
        $isCached= $args[1];
        LegacyLogger::getInstance()->addBlock($block->getVar('name'), $isCached, $block->getVar('bcachetime'));
    }

    /**
     * eventCoreDeprecated
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDeprecated($args)
    {
        $message = $args[0];
        LegacyLogger::getInstance()->addDeprecated($message);
    }

    /**
     * eventCoreDisableerrorreporting
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreDisableerrorreporting($args)
    {
        LegacyLogger::getInstance()->disable();
    }

    /**
     * eventCoreHeaderStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreHeaderStart($args)
    {
        $logger = LegacyLogger::getInstance();
        $logger->stopTime('Module init');
        $logger->startTime('XOOPS output init');
    }

    /**
     * eventCoreHeaderEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreHeaderEnd($args)
    {
        $logger = LegacyLogger::getInstance();
        $logger->stopTime('XOOPS output init');
        $logger->startTime('Module display');
    }

    /**
     * eventCoreFooterStart
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreFooterStart($args)
    {
        $logger = LegacyLogger::getInstance();
        $logger->stopTime('Module display');
    }

    /**
     * eventCoreFooterEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreFooterEnd($args)
    {
        $logger = LegacyLogger::getInstance();
        $logger->stopTime();
    }

    /**
     * eventCoreIncludeFunctionsRedirectheaderEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreIncludeFunctionsRedirectheaderEnd($args)
    {
        $xoops = Xoops::getInstance();
        $logger = LegacyLogger::getInstance();
        $debug_mode = $xoops->getModuleConfig('debug_mode', 'logger');
        if ($debug_mode == 2) {
            //Should we give extra time ?
            //$xoops->tpl()->assign('time', 300);
            $xoops->tpl()->assign('xoops_logdump', $logger->dump());
        }
    }

    /**
     * eventCoreSecurityValidatetokenEnd
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreSecurityValidatetokenEnd($args)
    {
        $logger = LegacyLogger::getInstance();
        $logs = $args[0];
        foreach ($logs as $log) {
            $logger->addExtra($log[0], $log[1]);
        }
    }

    /**
     * eventCoreModuleAddlog
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreModuleAddlog($args)
    {
        LegacyLogger::getInstance()->addExtra($args[0], $args[1]);
    }
}

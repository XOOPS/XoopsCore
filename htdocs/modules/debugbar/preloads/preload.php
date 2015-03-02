<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Psr\Log\LogLevel;
use Xoops\Core\PreloadItem;

/**
 * Debugbar module preloads
 *
 * @category  DebugbarLogger
 * @package   DebugbarLogger
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class DebugbarPreload extends PreloadItem
{
    private static $registry = array();

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
            $helper = $xoops->getModuleHelper('debugbar');
            $mid = $helper->getModule()->getVar('mid');
            if ($moduleperm_handler->checkRight('use_debugbar', 0, $user_groups, $mid)) {
                // get default settings
                $configs['debugbar_enable'] = $helper->getConfig('debugbar_enable');
                $configs['debug_smarty_enable'] = $helper->getConfig('debug_smarty_enable');
                // override with settings
                $uchelper = $xoops->getModuleHelper('userconfigs');
                if ($xoops->isUser() && $uchelper) {
                    $config_handler = $uchelper->getHandlerConfig();
                    $user_configs =
                        $config_handler->getConfigsByUser($xoops->user->getVar('uid'), $mid);
                    if (array_key_exists('debugbar_enable', $user_configs)) {
                        $configs['debugbar_enable'] = $user_configs['debugbar_enable'];
                    }
                    if (array_key_exists('debug_smarty_enable', $user_configs)) {
                        $configs['debug_smarty_enable'] = $user_configs['debug_smarty_enable'];
                    }
                }
            } else {
                // user has no permissions, turn everything off
                $configs['debugbar_enable'] = 0;
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
        DebugbarLogger::getInstance()->addException($e);
    }

    /**
     * add any module specific class map entries
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreIncludeCommonClassmaps($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap(array(
            'debugbarlogger' => $path . '/class/debugbarlogger.php',
        ));
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
        $logger = DebugbarLogger::getInstance();

        $logger->enable();//until we get a db connection debug is enabled
        //if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
        //    $logger->getDebugbar()['time']->addMeasure('Loading', $_SERVER['REQUEST_TIME_FLOAT'], microtime(true));
        //}
        $logger->startTime();
        $logger->startTime('XOOPS Boot');
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
        if (class_exists('DebugbarLogger')) {
            /* @var $db Xoops\Core\Database\Connection */
            $db = $args[0];
            DebugbarLogger::getInstance()->log(LogLevel::ALERT, $db->error(), array('errno' => $db->errno()));
        }
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
        if (class_exists('DebugbarLogger')) {
            /* @var $db Xoops\Core\Database\Connection */
            $db = $args[0];
            DebugbarLogger::getInstance()->log(LogLevel::ALERT, $db->error(), array('errno' => $db->errno()));
        }
    }

    /**
     * eventCoreIncludeCommonAuthSuccess
     *
     * @return void
     */
    public static function eventCoreIncludeCommonAuthSuccess()
    {
        $xoops = Xoops::getInstance();
        $logger = DebugbarLogger::getInstance();
        $configs = self::getConfigs();
        if ($configs['debugbar_enable']) {
            $xoops->loadLocale();
            $xoops->loadLanguage('main', 'debugbar');
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
        $logger = DebugbarLogger::getInstance();
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
        if ($configs['debugbar_enable']) {
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
        DebugbarLogger::getInstance()->startTime('Page rendering');
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
        DebugbarLogger::getInstance()->stopTime('Page rendering');
        DebugbarLogger::getInstance()->addSmarty();
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
        DebugbarLogger::getInstance()->addExtra(
            $template,
            sprintf('Cached (regenerates every %d seconds)', $theme->contentCacheLifetime)
        );
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
        //Logger::getInstance()->addBlock($block->getVar('name'), $isCached, $block->getVar('bcachetime'));
        $context = array('channel'=>'Blocks', 'cached'=>$isCached, 'cachetime'=>$block->getVar('bcachetime'));
        DebugbarLogger::getInstance()->log(LogLevel::INFO, $block->getVar('name'), $context);

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
        DebugbarLogger::getInstance()->log(LogLevel::WARNING, $message, array('channel'=>'Deprecated'));
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
        DebugbarLogger::getInstance()->disable();
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
        $logger = DebugbarLogger::getInstance();
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
        $logger = DebugbarLogger::getInstance();
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
        $logger = DebugbarLogger::getInstance();
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
        $logger = DebugbarLogger::getInstance();
        $logger->stopTime();
    }

    /**
     * eventCoreIncludeFunctionsRedirectheader
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventCoreIncludeFunctionsRedirectheaderStart($args)
    {
        DebugbarLogger::getInstance()->stackData();
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
        $logger = DebugbarLogger::getInstance();
        $logs = $args[0];
        foreach ($logs as $log) {
            $context = array('channel'=>'Extra', 'name'=>$log[0]);
            $logger->log(LogLevel::INFO, $log[1], $context);
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
        $context = array('channel'=>'Extra', 'name'=>$args[0]);
        DebugbarLogger::getInstance()->log(LogLevel::DEBUG, $args[1], $context);

    }

    /**
     * eventDebugLog - dump to DebugLog
     *
     * @param mixed $args argument supplied to triggerEvent
     *
     * @return void
     */
    public static function eventDebugLog($args)
    {
        DebugbarLogger::getInstance()->dump($args);
    }

    /**
     * eventDebugTimerStart - start a timer
     *
     * @param array $args array of name and label for timer
     *
     * @return void
     */
    public static function eventDebugTimerStart($args)
    {
        DebugbarLogger::getInstance()->startTime($args[0], $args[1]);
    }

    /**
     * eventDebugTimerStop - start a timer
     *
     * @param string $args name of timer
     *
     * @return void
     */
    public static function eventDebugTimerStop($args)
    {
        DebugbarLogger::getInstance()->stopTime($args);
    }
}

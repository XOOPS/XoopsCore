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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Logger core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class LoggerCorePreload extends XoopsPreloadItem
{
    static $registry = array();

    static function eventCoreIncludeCommonStart($args)
    {
        //Load may fail is cache was erased
        XoopsLoad::addMap(array('logger' => dirname(dirname(__FILE__)) . '/class/logger.php'));
        Logger::getInstance()->enable();//until we get a db connection debug is enabled
        Logger::getInstance()->startTime();
        Logger::getInstance()->startTime('XOOPS Boot');
    }


    /**
     * @static
     *
     * @param $args
     */
    static function eventCoreDatabaseNoconn($args)
    {
        if (!class_exists('logger')) return;
        /* @var $db XoopsConnection */
        $db = $args[0];
        Logger::getInstance()->addQuery('', $db->error(), $db->errno());
    }

    static function eventCoreDatabaseNodb($args)
    {
        if (!class_exists('logger')) return;
        /* @var $db XoopsConnection */
        $db = $args[0];
        Logger::getInstance()->addQuery('', $db->error(), $db->errno());
    }

    static function eventCoreDatabaseQueryStart($args)
    {
        Logger::getInstance()->startTime('query_time');
    }

    static function eventCoreDatabaseQueryEnd($args)
    {
        Logger::getInstance()->stopTime('query_time');
        self::$registry['query_time'] = Logger::getInstance()->dumpTime('query_time', true);
    }

    static function eventCoreDatabaseQuerySuccess($args)
    {
        $sql = $args[0];
        Logger::getInstance()->addQuery($sql, null, null, self::$registry['query_time']);
    }

    static function eventCoreDatabaseQueryFailure($args)
    {
        /* @var $db XoopsConnection */
        $sql = $args[0];
        $db = $args[1];
        if(method_exists($db, 'error')) {
            Logger::getInstance()->addQuery($sql, $db->error(), $db->errno(), self::$registry['query_time']);
        } else {
                Logger::getInstance()->addQuery($sql, $db->errorInfo(), $db->errorCode(), self::$registry['query_time']);        
        }
    }

    static function eventCoreIncludeCommonConfigsSuccess($args)
    {
        $xoops = Xoops::getInstance();
        $logger = Logger::getInstance();
        $debug_mode = $xoops->getModuleConfig('debug_mode', 'logger');

        if ($debug_mode == 1 || $debug_mode == 2) {
            $xoops->loadLocale();
            $xoops->loadLanguage('main', 'logger');
            $logger->enable();
        } else {
            $xoops->disableErrorReporting();
        }
    }

    static function eventCoreIncludeCommonAuthSuccess($args)
    {
        $xoops = Xoops::getInstance();
        $logger = Logger::getInstance();
        if ($logger->isEnable()) {
            $level = $xoops->getModuleConfig('debug_level','logger');
            if (($level == 2 && !$xoops->userIsAdmin) || ($level == 1 && !$xoops->isUser())) {
                $xoops->disableErrorReporting();
            }
        }
    }

    static function eventCoreIncludeCommonEnd($args)
    {
        XoopsLoad::addMap(array('logger' => dirname(dirname(__FILE__)) . '/class/logger.php'));

        $logger = Logger::getInstance();
        $logger->stopTime('XOOPS Boot');
        $logger->startTime('Module init');
    }

    static function eventCoreTemplateConstructStart($args)
    {
        $xoops = Xoops::getInstance();
        $tpl = $args[0];
        $debug_mode = $xoops->getModuleConfig('debug_mode', 'logger');
        if ($debug_mode) {
            $tpl->debugging_ctrl = 'URL';
            if ($debug_mode == 3) {
                $tpl->debugging = true;
            }
        }
    }

    static function eventCoreThemeRenderStart($args)
    {
        Logger::getInstance()->startTime('Page rendering');
    }

    static function eventCoreThemeRenderEnd($args)
    {
        Logger::getInstance()->stopTime('Page rendering');
    }

    static function eventCoreThemeCheckcacheSuccess($args)
    {
        $template = $args[0];
        $theme = $args[1];
        Logger::getInstance()->addExtra($template, sprintf('Cached (regenerates every %d seconds)', $theme->contentCacheLifetime));
    }

    static function eventCoreThemeblocksBuildblockStart($args)
    {
        /* @var $block XoopsBlock */
        $block = $args[0];
        $isCached= $args[1];
        Logger::getInstance()->addBlock($block->getVar('name'), $isCached, $block->getVar('bcachetime'));
    }

    static function eventCoreDeprecated($args)
    {
        $message = $args[0];
        Logger::getInstance()->addDeprecated($message);
    }

    static function eventCoreDisableerrorreporting($args)
    {
        Logger::getInstance()->disable();
    }

    static function eventCoreHeaderStart($args)
    {
        $logger = Logger::getInstance();
        $logger->stopTime('Module init');
        $logger->startTime('XOOPS output init');
    }

    static function eventCoreHeaderEnd($args)
    {
        $logger = Logger::getInstance();
        $logger->stopTime('XOOPS output init');
        $logger->startTime('Module display');
    }

    static function eventCoreFooterStart($args)
    {
        $logger = Logger::getInstance();
        $logger->stopTime('Module display');
    }

    static function eventCoreFooterEnd($args)
    {
        $logger = Logger::getInstance();
        $logger->stopTime();
    }

    static function eventCoreIncludeFunctionsRedirectheaderEnd($args)
    {
        $xoops = Xoops::getInstance();
        $logger = Logger::getInstance();
        $debug_mode = $xoops->getModuleConfig('debug_mode', 'logger');
        if ($debug_mode == 2) {
            //Should we give extra time ?
            //$xoops->tpl()->assign('time', 300);
            $xoops->tpl()->assign('xoops_logdump', $logger->dump());
        }
    }

    static function eventCoreSecurityValidatetokenEnd($args)
    {
        $logger = Logger::getInstance();
        $logs = $args[0];
        foreach ($logs as $log) {
            $logger->addExtra($log[0], $log[1]);
        }
    }

    static function eventCoreModuleAddlog($args)
    {
        Logger::getInstance()->addExtra($args[0], $args[1]);
    }
}
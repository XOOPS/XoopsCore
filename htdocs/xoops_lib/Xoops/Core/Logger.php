<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * Xoops\Core\Logger - dispatch log requests to any registered loggers.
 *
 * No logging is done in this class, but any logger, implemented as a
 * module or extension, can register as a logger using the addLogger()
 * method. Multiple loggers can be registered, and each will be
 * invoked in turn for each log() call.
 *
 * Such loggers are expected to implement the PSR-3 LoggerInterface.
 * In addition, any logger that generates output as part of the XOOPS
 * delivered page should implement the quiet() method, to disable output.
 *
 * Loggers are managed this way so that any routine may easily add a
 * log entry without needing to know any details of the implemention.
 *
 * Not all events are published through this mechanism, only specifc requests
 * to log() or related methods. Individual loggers may connect to preload
 * events or other sources and gain access to detailed debugging style
 * information.
 *
 * @category  Xoops\Core\Logger
 * @package   Logger
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 2.6.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class Logger implements LoggerInterface
{
    /**
     * @var array chain of PSR-3 compatible loggers to call
     */
    private $loggers = array();

    /**
     * @var boolean do we have active loggers?
     */
    private $logging_active = false;

    /**
     * @var boolean just to prevent fatal legacy errors. Does nothing. Stop it!
     */
    //public $activated = false;

    /**
     * Get the Xoops\Core\Logger instance
     *
     * @return Xoops\Core\Logger object
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
            // Always catch errors, for security reasons
            set_error_handler(array($instance, 'handleError'));
            // grab any uncaught exception
            set_exception_handler(array($instance, 'handleException'));
        }

        return $instance;
    }

    /**
     * Error handling callback.
     *
     * This will
     *
     * @param integer $errno   error number
     * @param string  $errstr  error message
     * @param string  $errfile file
     * @param integer $errline line number
     *
     * @return void
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        if ($this->logging_active && ($errno & error_reporting())) {

            // if an error occurs before a locale is established,
            // we still need messages, so check and deal with it

            $msg = ': ' . sprintf(
                (class_exists('\XoopsLocale', false) ? \XoopsLocale::EF_LOGGER_FILELINE : "%s in file %s line %s"),
                $this->sanitizePath($errstr),
                $this->sanitizePath($errfile),
                $errline
            );

            switch ($errno) {
                case E_USER_NOTICE:
                    $msg = (class_exists('\XoopsLocale', false) ? \XoopsLocale::E_LOGGER_ERROR : '*Error') . $msg;
                    $this->log(LogLevel::NOTICE, $msg);
                    break;
                case E_NOTICE:
                    $msg = (class_exists('\XoopsLocale', false) ? \XoopsLocale::E_LOGGER_NOTICE : '*Notice') . $msg;
                    $this->log(LogLevel::NOTICE, $msg);
                    break;
                case E_WARNING:
                    $msg = (class_exists('\XoopsLocale', false) ? \XoopsLocale::E_LOGGER_WARNING : '*Warning') . $msg;
                    $this->log(LogLevel::WARNING, $msg);
                    break;
                case E_STRICT:
                    $msg = (class_exists('\XoopsLocale', false) ? \XoopsLocale::E_LOGGER_STRICT : '*Strict') . $msg;
                    $this->log(LogLevel::WARNING, $msg);
                    break;
                case E_USER_ERROR:
                    $msg = (class_exists('\XoopsLocale', false) ? \XoopsLocale::E_LOGGER_ERROR : '*Error') . $msg;
                    @$this->log(LogLevel::CRITICAL, $msg);
                    break;
                default:
                    $msg = (class_exists('\XoopsLocale', false) ? \XoopsLocale::E_LOGGER_UNKNOWN : '*Unknown') . $msg;
                    $this->log(LogLevel::ERROR, $msg);
                    break;
            }
        }

        if ($errno == E_USER_ERROR) {
            $trace = true;
            if (substr($errstr, 0, '8') == 'notrace:') {
                $trace = false;
                $errstr = substr($errstr, 8);
            }
            $this->reportFatalError($errstr);
            if ($trace) {
                echo "<div style='color:#f0f0f0;background-color:#f0f0f0'>" . _XOOPS_FATAL_BACKTRACE . ":<br />";
                $trace = debug_backtrace();
                array_shift($trace);
                foreach ($trace as $step) {
                    if (isset($step['file'])) {
                        echo $this->sanitizePath($step['file']);
                        echo ' (' . $step['line'] . ")\n<br />";
                    }
                }
                echo '</div>';
            }
            exit();
        }
    }

    /**
     * Exception handling callback.
     *
     * This will
     *
     * @param Exception $e uncaught exception
     *
     * @return never
     */
    public function handleException($e)
    {
        $msg = $e->__toString();
        $this->reportFatalError($msg);
    }

    private function reportFatalError($msg)
    {
        $msg=$this->sanitizePath($msg);
        echo sprintf(_XOOPS_FATAL_MESSAGE, XOOPS_URL, $msg);
        @$this->log(LogLevel::CRITICAL, $msg);
    }

    /**
     * clean a path to remove sensitive details
     *
     * @param string $path path to sanitize
     *
     * @return string sanitized path
     */
    protected function sanitizePath($path)
    {
        $path = str_replace(
            array(
                '\\',
                XOOPS_VAR_PATH,
                str_replace('\\', '/', realpath(XOOPS_VAR_PATH)),
                XOOPS_PATH,
                str_replace('\\', '/', realpath(XOOPS_PATH)),
                XOOPS_ROOT_PATH,
                str_replace('\\', '/', realpath(XOOPS_ROOT_PATH)),
            ),
            array(
                '/',
                'VAR',
                'VAR',
                'PATH',
                'PATH',
                'ROOT',
                'ROOT',
            ),
            $path
        );

        return $path;
    }

    /**
     * add a PSR-3 compatible logger to the chain
     *
     * @param object $logger a PSR-3 compatible logger object
     *
     * @return void
     */
    public function addLogger($logger)
    {
        if (is_object($logger) && method_exists($logger, 'log')) {
                $this->loggers[] = $logger;
                $this->logging_active = true;
        }
    }

    /**
     * System is unusable.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level   PSR-3 LogLevel constant
     * @param string $message message
     * @param array  $context array of context data for this log entry
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        if (!empty($this->loggers)) {
            foreach ($this->loggers as $logger) {
                if (is_object($logger)) {
                    try {
                        $logger->log($level, $message, $context);
                    } catch (\Exception $e) {
                        // just ignore, as we can't do anything, not even log it.
                    }
                }
            }
        }
    }

    /**
     * quiet - turn off output if output is rendered in XOOPS page output.
     * This is intended to assist ajax code that may fail with any extra
     * content the logger may introduce.
     *
     * It should have no effect on loggers using other methods, such a write
     * to file.
     *
     * @return void
     */
    public function quiet()
    {
        if (!empty($this->loggers)) {
            foreach ($this->loggers as $logger) {
                if (is_object($logger) && method_exists($logger, 'quiet')) {
                    try {
                        $logger->quiet();
                    } catch (\Exception $e) {
                        // just ignore, as we can't do anything, not even log it.
                    }
                }
            }
        }
    }

    // Deprecated uses

    /**
     * Keep deprecated calls from failing
     *
     * @param string $var property
     * @param string $val value
     *
     * @return void
     *
     * @deprecated
     */
    public function __set($var, $val)
    {
        $this->deprecatedMessage();
        // legacy compatibility: turn off logger display for $xoopsLogger->activated = false; usage
        if ($var=='activated' && !$val) {
            $this->quiet();
        }

    }

    /**
     * Keep deprecated calls from failing
     *
     * @param string $var property
     *
     * @return void
     *
     * @deprecated
     */
    public function __get($var)
    {
        $this->deprecatedMessage();
    }

    /**
     * Keep deprecated calls from failing
     *
     * @param string $method method
     * @param string $args   arguments
     *
     * @return void
     *
     * @deprecated
    */
    public function __call($method, $args)
    {
        $this->deprecatedMessage();
    }

    /**
     * issue a deprecated warning
     *
     * @return void
     */
    private function deprecatedMessage()
    {
        $xoops = \Xoops::getInstance();
        $xoops->deprecated('This use of XoopsLogger is deprecated since 2.6.0.');
    }
}

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
 * Logger factory class
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 */
class Logger
{
    /**
     * Get a reference to the only instance of this class
     *
     * @return  object LoggerAbstract  reference to the only instance
     */
    public static function getInstance()
    {
        static $instance;

        if (!isset($instance)) {
            $xoops = Xoops::getInstance();
            if (!$logger_plugin = Xoops_Cache::read('module_logger_plugin')) {
                $logger_plugin = $xoops->getModuleConfig('debug_plugin', 'logger');
                if (!$logger_plugin) {
                    $tempLogger = new LoggerAbstract();
                    return $tempLogger;
                }
                Xoops_Cache::write('module_logger_plugin', $logger_plugin);
            }
            if (XoopsLoad::fileExists($file = dirname(dirname(__FILE__)) . "/plugins/{$logger_plugin}/logger.php")) {
                include_once $file;
            }
            $className = 'Logger' . ucfirst($logger_plugin);
            if (!class_exists($className)) {
                die("Could not load  {$className}");
            }
            $instance = new $className();
            // Always catch errors, for security reasons
            // Always catch errors, for security reasons
            set_error_handler(array($instance, 'handleError'));
        }
        return $instance;
    }
}

/**
 * Abstract class that should be extended by other loggers
 *
 * @package kernel
 */
class LoggerAbstract
{
    /**
     * *#@+
     * @var array
     */
    var $queries = array();

    var $blocks = array();

    var $extra = array();

    var $logstart = array();

    var $logend = array();

    var $errors = array();

    public $deprecated = array();

    /**
     * *@access protected
     */
    var $renderingEnabled = false;

    var $activated = true;


       /**
     * @return void
     */
    public function disable()
    {
        error_reporting(0);
        $this->activated = false;
    }

    /**
     * Enable logger output rendering
     */
    public function enable()
    {
        error_reporting(E_ALL | E_STRICT);
        $this->activated = true;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->activated;
    }


    /**
     * Returns the current microtime in seconds.
     *
     * @return float
     */
    public function microtime()
    {
        $now = explode(' ', microtime());
        return (float)$now[0] + (float)$now[1];
    }

    /**
     * @access protected
     */
    public function sanitizePath($path)
    {
        $path = str_replace(array('\\', XOOPS_ROOT_PATH, str_replace('\\', '/', realpath(XOOPS_ROOT_PATH))), array(
            '/', '', ''
        ), $path);
        return $path;
    }

    /**
     * Enable logger output rendering
     * When output rendering is enabled, the logger will insert its output within the page content.
     * If the string <!--{xo-logger-output}--> is found in the page content, the logger output will
     * replace it, otherwise it will be inserted after all the page output.
     */
    public function enableRendering()
    {
    }

    /**
     * Start a timer
     *
     * @param   string  $name   name of the timer
     */
    public function startTime($name = 'XOOPS')
    {
        $this->logstart[$name] = $this->microtime();
    }

    /**
     * Stop a timer
     *
     * @param   string  $name   name of the timer
     */
    public function stopTime($name = 'XOOPS')
    {
        $this->logend[$name] = $this->microtime();
    }

    /**
     * Log a database query
     *
     * @param   string  $sql    SQL string
     * @param   string  $error  error message (if any)
     * @param   int     $errno  error number (if any)
     */
    public function addQuery($sql, $error = null, $errno = null)
    {
    }

    /**
     * Log display of a block
     *
     * @param   string  $name       name of the block
     * @param   bool    $cached     was the block cached?
     * @param   int     $cachetime  cachetime of the block
     */
    public function addBlock($name, $cached = false, $cachetime = 0)
    {
    }

    /**
     * Log extra information
     *
     * @param   string  $name       name for the entry
     * @param   int     $msg        text message for the entry
     */
    public function addExtra($name, $msg)
    {
    }

    /**
     * Log messages for deprecated functions
     *
     * @param string $msg name for the entry
     */
    public function addDeprecated($msg)
    {
    }

    /**
     * Output buffering callback inserting logger dump in page output
     */
    public function render($output)
    {
    }

    /**
     * @param string $mode
     */
    public function dump($mode = '')
    {
    }

    /**
     * get the current execution time of a timer
     *
     * @param string $name  name of the counter
     * @param bool   $unset removes counter from global log
     *
     * @return float current execution time of the counter
     */
    public function dumpTime($name = 'XOOPS', $unset = false)
    {
        if (!$this->activated) {
            return null;
        }

        if (!isset($this->logstart[$name])) {
            return 0;
        }
        $stop = isset($this->logend[$name]) ? $this->logend[$name] : $this->microtime();
        $start = $this->logstart[$name];

        if ($unset) {
            unset($this->logstart[$name]);
        }

        return $stop - $start;
    }

    /**#@+
     * @deprecated
     */
    public function dumpAll()
    {
    }

    public function dumpBlocks()
    {
    }

    public function dumpExtra()
    {
    }

    public function dumpQueries()
    {
    }

    /**
     * Error handling callback (called by the zend engine)
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     *
     * @return void
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        if ($this->activated && ($errno & error_reporting())) {
            // NOTE: we only store relative pathnames
            $this->errors[] = compact('errno', 'errstr', 'errfile', 'errline');
        }
        if ($errno == E_USER_ERROR) {
            $trace = true;
            if (substr($errstr, 0, '8') == 'notrace:') {
                $trace = false;
                $errstr = substr($errstr, 8);
            }
            echo sprintf(_XOOPS_FATAL_MESSAGE, $errstr);
            if ($trace && function_exists('debug_backtrace')) {
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
}

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
 * Collects information for a page request
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 *
 * @package kernel
 */
class LoggerLegacy extends LoggerAbstract
{
    /**
     * @var bool
     */
    var $usePopup = false;

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
     * When output rendering is enabled, the logger will insert its output within the page content.
     * If the string <!--{xo-logger-output}--> is found in the page content, the logger output will
     * replace it, otherwise it will be inserted after all the page output.
     */
    public function enable()
    {
        error_reporting(E_ALL | E_STRICT);
        $xoops = Xoops::getInstance();
        if ($xoops->getModuleConfig('debug_mode', 'logger') == 2) {
            $this->usePopup = true;
        }
        $this->activated = true;
        $this->enableRendering();
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
     * Start a timer
     *
     * @param string $name name of the timer
     */
    public function startTime($name = 'XOOPS')
    {
        if ($this->activated) {
            $this->logstart[$name] = $this->microtime();
        }
    }

    /**
     * Stop a timer
     *
     * @param string $name name of the timer
     */
    public function stopTime($name = 'XOOPS')
    {
        if ($this->activated) {
            $this->logend[$name] = $this->microtime();
        }
    }

    /**
     * Log a database query
     *
     * @param string $sql
     * @param string $error
     * @param int    $errno
     * @param float  $query_time
     *
     * @return void
     */
    public function addQuery($sql, $error = null, $errno = null, $query_time = null)
    {
        if ($this->activated) {
            $this->queries[] = array(
                'sql' => $sql, 'error' => $error, 'errno' => $errno, 'query_time' => $query_time
            );
        }
    }

    /**
     * Log display of a block
     *
     * @param string $name      name of the block
     * @param bool   $cached    was the block cached?
     * @param int    $cachetime cachetime of the block
     */
    public function addBlock($name, $cached = false, $cachetime = 0)
    {
        if ($this->activated) {
            $this->blocks[] = array('name' => $name, 'cached' => $cached, 'cachetime' => $cachetime);
        }
    }

    /**
     * Log extra information
     *
     * @param string $name name for the entry
     * @param string $msg  text message for the entry
     */
    public function addExtra($name, $msg)
    {
        if ($this->activated) {
            $this->extra[] = array('name' => $name, 'msg' => $msg);
        }
    }

    /**
     * Log messages for deprecated functions
     *
     * @param string $msg name for the entry
     */
    public function addDeprecated($msg)
    {
        if ($this->activated) {
            $this->deprecated[] = $msg;
        }
    }

    /**
     * Enable logger output rendering
     * When output rendering is enabled, the logger will insert its output within the page content.
     * If the string <!--{xo-logger-output}--> is found in the page content, the logger output will
     * replace it, otherwise it will be inserted after all the page output.
     */
    function enableRendering()
    {
        if (!$this->renderingEnabled) {
            ob_start(array(&$this, 'render'));
            $this->renderingEnabled = true;
        }
    }

    /**
     * Output buffering callback inserting logger dump in page output
     */
    function render($output)
    {
        if (!$this->activated) {
            return $output;
        }

        $log = $this->dump($this->usePopup ? 'popup' : '');
        $this->renderingEnabled = $this->activated = false;

        $pattern = '<!--{xo-logger-output}-->';
        $pos = strpos($output, $pattern);
        if ($pos !== false) {
            return substr($output, 0, $pos) . $log . substr($output, $pos + strlen($pattern));
        } else {
            return $output . $log;
        }
    }

    /**#@+
     * @protected
     */
    function dump($mode = '')
    {
        $ret = '';
        include dirname(__FILE__) . '/render.php';
        return $ret;
    }

    /**#@-*/
    /**#@+
     * @deprecated
     */
    function dumpAll()
    {
        return $this->dump('');
    }

    function dumpBlocks()
    {
        return $this->dump('blocks');
    }

    function dumpExtra()
    {
        return $this->dump('extra');
    }

    function dumpQueries()
    {
        return $this->dump('queries');
    }
}
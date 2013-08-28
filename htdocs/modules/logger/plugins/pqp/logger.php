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
class LoggerPqp extends LoggerAbstract
{
    /**
     * @var XoopsTpl
     */
    var $template;

    var $db;

    var $startTime = 0;

    var $queryStart = 0;

    var $queryCount = 0;

    /**
     * @var array
     */
    var $queries = array();

    var $output = array();

    var $count = array();

    var $items = array();

    var $last = array();

    function __destruct()
    {
        if ($this->activated) {
            $this->display();
        }
    }

    function __construct()
    {
        if ($this->activated) {
            $this->startTime = $this->microtime();

            $this->count['log'] = 0;
            $this->count['memory'] = 0;
            $this->count['speed'] = 0;
            $this->count['error'] = 0;
            $this->count['query'] = 0;
            $this->last['speed'] = 'Start';
        }
    }

    /**
     * Start a timer
     *
     * @param   string  $name   name of the timer
     */
    function startTime($name = 'XOOPS')
    {
        parent::startTime($name);
        if ($name == 'query_time') {
            return;
        }

        $this->log("Starting: {$name}");
        $this->logSpeed("Time between: " . $this->last['speed'] . " and Starting: {$name}", $name);
        $this->logMemory(null, "Memory when starting : " . $name);
    }

    /**
     * Stop a timer
     *
     * @param   string  $name   name of the timer
     */
    function stopTime($name = 'XOOPS')
    {
        parent::stopTime($name);
        if ($name == 'query_time') {
            return;
        }
        $this->log("Stoping: {$name}");
        $this->logSpeed("Time between: " . $this->last['speed'] . " and Stopping: {$name}", $name);
        $this->logMemory(null, "Memory when Ending : " . $name);
    }

    /**
     * Log a database query
     *
     * @param   string  $sql    SQL string
     * @param   string  $error  error message (if any)
     * @param   int     $errno  error number (if any)
     * @param   int   $query_time
     */
    function addQuery($sql, $error = null, $errno = null, $query_time = null)
    {
        if ($this->activated) {
            $this->logQuery($sql, $error, $errno, $query_time);
        }
    }

    /**
     * Log display of a block
     *
     * @param   string  $name       name of the block
     * @param   bool    $cached     was the block cached?
     * @param   int     $cachetime  cachetime of the block
     */
    function addBlock($name, $cached = false, $cachetime = 0)
    {
        if ($this->activated) {

            $this->logSpeed("Time between: " . $this->last['speed'] . " and BLOCK: {$name}", $name);
            if ($cached) {
                $this->log("Block name : {$name} - Cachetime:{$cachetime}");
            } else {
                //$this->log("Block name : {$name}");
            }
            //$this->logMemory($this, 'RenderClass : Line '.__LINE__);
            //$this->logSpeed('Time taken to get to line '.__LINE__);
        }
    }

    /**
     * Log extra information
     *
     * @param   string  $name       name for the entry
     * @param   int     $msg        text message for the entry
     */
    function addExtra($name, $msg)
    {
        if ($this->activated) {
            $this->log("{$name} : {$msg}");
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
            $this->log("Deprecated : {$msg}");
        }
    }

    function log($data)
    {
        $logItem = array(
            "data" => $data, "type" => 'log'
        );
        $this->items[] = $logItem;
        $this->count['log'] += 1;
    }

    function logMemory($object = null, $name = 'PHP')
    {
        $memory = memory_get_usage();
        if ($object) {
            $memory = strlen(serialize($object));
        }

        $logItem = array(
            "data" => $memory, "type" => 'memory', "name" => $name, "dataType" => $object ? gettype($object) : ''
        );

        $this->items[] = $logItem;
        $this->count['memory'] += 1;
    }

    function logError($exception)
    {
        $logItem = array(
            "data" => $exception['errstr'], "type" => 'error', "file" => $exception['errfile'], /*->getFile()*/
            //, compact( 'errno', 'errstr', 'errfile', 'errline' );
            "line" => $exception['errline'] /*->getLine() */
        );

        $this->items[] = $logItem;
        $this->count['error'] += 1;
    }

    function logSpeed($name = 'Point in Time', $realname = 'Not set')
    {
        $logItem = array(
            "data" => $this->microtime(), "type" => 'speed', "name" => $name
        );

        $this->items[] = $logItem;
        $this->last['speed'] = $realname;
        $this->count['speed'] += 1;
    }

    function logQuery($sql, $error = null, $errno = null, $query_time = null)
    {
        $query = array(
            'sql' => $sql, 'time' => $query_time * 1000, 'error' => $error, 'errno' => $errno
        );
        array_push($this->queries, $query);
        $this->count['query'] += 1;
    }

    function gatherConsoleData()
    {
        $logs = $this->items;
        $totalSpeed = 0;
        foreach ($logs as $key => $log) {
            if ($log['type'] == 'log') {
                $logs[$key]['data'] = print_r($log['data'], true);
            } elseif ($log['type'] == 'memory') {
                $logs[$key]['data'] = $this->getReadableFileSize($log['data']);
            } elseif ($log['type'] == 'speed') {
                $timeTaken = ($log['data'] - $this->startTime) * 1000;
                $logs[$key]['data'] = $this->getReadableTime($timeTaken);
                $logs[$key]['speed'] = $this->getReadableTime($timeTaken - $totalSpeed);
                $totalSpeed = $timeTaken;
            }
        }

        $this->template->assign('logs', $logs);
        $this->template->assign('count', $this->count);
    }

    function gatherFileData()
    {
        $files = get_included_files();
        $fileList = array();
        $fileTotals = array(
            "count" => count($files), "size" => 0, "largest" => 0,
        );

        foreach ($files as $key => $file) {
            $size = filesize($file);
            $fileList[] = array(
                'name' => $file, 'size' => $this->getReadableFileSize($size)
            );
            $fileTotals['size'] += $size;
            if ($size > $fileTotals['largest']) {
                $fileTotals['largest'] = $size;
            }
        }

        $fileTotals['size'] = $this->getReadableFileSize($fileTotals['size']);
        $fileTotals['largest'] = $this->getReadableFileSize($fileTotals['largest']);
        $this->template->assign('files', $fileList);
        $this->template->assign('fileTotals', $fileTotals);
    }

    function gatherMemoryData()
    {
        $memoryTotals = array();
        $memoryTotals['used'] = $this->getReadableFileSize(memory_get_peak_usage());
        $memoryTotals['total'] = ini_get("memory_limit");
        $this->template->assign('memoryTotals', $memoryTotals);
    }

    function gatherQueryData()
    {
        $queryTotals = array();
        $queryTotals['time'] = 0;
        $queries = array();

        //$queryTotals['count'] += $this->queryCount;
        foreach ($this->queries as $key => $query) {
            $query['explain'] = ' ';
            $query = $this->attemptToExplainQuery($query);
            $queryTotals['time'] += $query['time'];
            $query['time'] = $this->getReadableTime($query['time']);
            $queries[] = $query;
        }

        $queryTotals['time'] = $this->getReadableTime($queryTotals['time']);
        $this->template->assign('queries', $queries);
        $this->template->assign('queryTotals', $queryTotals);
    }

    function attemptToExplainQuery($query)
    {
        global $xoopsDB;
        $this->db = $xoopsDB;
        //require_once XOOPS_ROOT_PATH . '/class/database/databasefactory.php';
        //$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        try {
            $sql = 'EXPLAIN ' . $query['sql'];
            $rs = mysql_query($sql, $this->db->conn);
        } catch (Exception $e) {
        }
        if ($rs) {
            $row = mysql_fetch_array($rs, MYSQL_ASSOC);
            $query['explain'] = $row;
        }
        return $query;
    }

    function gatherSpeedData()
    {
        $speedTotals = array();
        $speedTotals['total'] = $this->getReadableTime(($this->microtime() - $this->startTime) * 1000);
        $speedTotals['allowed'] = ini_get("max_execution_time");
        $this->template->assign('speedTotals', $speedTotals);
    }

    function getReadableFileSize($size, $retstring = null)
    {
        // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
        $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        if ($retstring === null) {
            $retstring = '%01.2f %s';
        }
        $lastsizestring = end($sizes);

        foreach ($sizes as $sizestring) {
            if ($size < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $size /= 1024;
            }
        }
        if ($sizestring == $sizes[0]) {
            $retstring = '%01d %s';
        } // Bytes aren't normally fractional

        return sprintf($retstring, $size, $sizestring);
    }

    function getReadableTime($time)
    {
        $ret = $time;
        $formatter = 0;
        $formats = array('ms', 's', 'm');
        if ($time >= 1000 && $time < 60000) {
            $formatter = 1;
            $ret = ($time / 1000);
        }
        if ($time >= 60000) {
            $formatter = 2;
            $ret = ($time / 1000) / 60;
        }
        $ret = number_format($ret, 3, '.', '') . ' ' . $formats[$formatter];
        return $ret;
    }

    function display()
    {
        $this->template = new XoopsTpl();
        $this->gatherConsoleData();
        $this->gatherFileData();
        $this->gatherMemoryData();
        $this->gatherQueryData();
        $this->gatherSpeedData();
        $this->template->display(dirname(__FILE__) . '/templates/pqp.html');
    }
}
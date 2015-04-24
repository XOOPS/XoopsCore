<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf;

/**
 * Debugging tools for developers
 *
 * @category  Xmf\Module\Debug
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2015 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Debug
{
    /**
     * associative array of timers
     *
     * @var float[]
     */

    private static $times = array();

    /**
     * indexed array of timer data in form
     * array('label' => string, 'start' => float, 'elapsed' => float)
     *
     * @var array
     */

    private static $timerQueue = array();

    /**
     * associative array of timer labels
     *
     * @var string[]
     */

    private static $timerLabels = array();


    /**
     * Dump a variable
     *
     * @param mixed $var    variable which will be dumped
     * @param bool  $inline force inline display if true, otherwise will attempt to
     *                      use debug.log event
     *
     * @return void
     */
    public static function dump($var, $inline = false)
    {
        $events = \Xoops::getInstance()->events();
        $eventName = 'debug.log';
        if (!$inline && $events->hasListeners($eventName)) {
            $events->triggerEvent($eventName, $var);
            //\Kint::dump(func_get_arg(0));
        } else {
            $config = array(
                'skin' => array('selected' => 'modern'),
                'css'  => array('url' => XOOPS_URL . '/modules/xmf/css/krumo/'),
                'display' => array(
                    'show_version' => false,
                    'show_call_info' => false,
                    'sort_arrays' => false,
                    ),
                );
            \krumo::setConfig($config);
            $msg = \krumo::dump($var);
            echo $msg;
        }
    }

    /**
     * Display debug backtrace
     *
     * @param boolean $inline force inline display if true, otherwise will attempt to
     *                        use debug.log event
     *
     * @return mixed|string
     */
    public static function backtrace($inline = false)
    {
        $events = \Xoops::getInstance()->events();
        $eventName = 'debug.log';
        if (!$inline && $events->hasListeners($eventName)) {
            $events->triggerEvent($eventName, debug_backtrace());
        } else {
            return self::dump(debug_backtrace(), $inline);
        }
    }

    /**
     * Start a timer
     *
     * @param string      $name  unique name for timer
     * @param string|null $label optional label for this timer
     *
     * @return void
     */
    public static function startTimer($name, $label = null)
    {
        $events = \Xoops::getInstance()->events();
        $var = array($name);
        $var[] = empty($label) ? $name : $label;
        $eventName = 'debug.timer.start';
        if ($events->hasListeners($eventName)) {
            $events->triggerEvent($eventName, $var);
        } else {
            self::$times[$name] = microtime(true);
        }
    }

    /**
     * Stop a timer
     *
     * @param string $name unique name for timer
     *
     * @return void
     */
    public static function stopTimer($name)
    {
        $events = \Xoops::getInstance()->events();
        $eventName = 'debug.timer.stop';
        if ($events->hasListeners($eventName)) {
            $events->triggerEvent($eventName, $name);
        } else {
            echo $name . ' - ' . intval(microtime(true) - self::$times[$name]) . " \n";
        }
    }

    /**
     * Start a queued timer. Queued timers are stored and only dumped by request.
     * This makes them useful in recording timing when immediate output is not
     * possible practical, such as early system startup activities. Timers can be
     * queued at any point once the Xmf\Debug class can be loaded then dumped
     * when system facilities are available.
     *
     * @param string      $name  unique name for timer
     * @param string|null $label optional label for this timer
     *
     * @return void
     */
    public static function startQueuedTimer($name, $label = null)
    {
        self::$times[$name] = microtime(true);
        self::$timerLabels[$name] = empty($label) ? $name : $label;
    }

    /**
     * Stop a queued timer
     *
     * @param string $name unique name for timer
     *
     * @return void
     */
    public static function stopQueuedTimer($name)
    {
        if (isset(self::$timerLabels[$name]) && isset(self::$times[$name])) {
            $queueItem = array(
                'label' => self::$timerLabels[$name],
                'start' => self::$times[$name],
                'elapsed' => microtime(true) - self::$times[$name],
                );
            self::$timerQueue[] = $queueItem;
        }
    }

    /**
     * dump and queued timer data and reset the queue
     *
     * Note: The DebugBar logger will add any unprocessed queue data to its
     * timeline automatically, if you use queued timers and don't call this.
     *
     * @param boolean $returnOnly if true do not dump queue, only return it
     *
     * @return array of time data see \Xmf\Debug::$timerQueue
     */
    public static function dumpQueuedTimers($returnOnly = false)
    {
        $queue = self::$timerQueue;
        self::$timerQueue = array();
        if (!$returnOnly) {
            self::dump($queue);
        }

        return $queue;
    }

    /**
     * start_trace - turn on xdebug trace
     *
     * Requires xdebug extension
     *
     * @param type $tracefile      file name for trace file
     * @param type $collect_params argument for ini_set('xdebug.collect_params',?)
     *                             Controls display of parameters in trace output
     * @param type $collect_return argument for ini_set('xdebug.collect_return',?)
     *                             Controls display of function return value in trace
     *
     * @return void
     */
    public static function startTrace($tracefile = '', $collect_params = '3', $collect_return = 'On')
    {
        if (function_exists('xdebug_start_trace')) {
            ini_set('xdebug.collect_params', $collect_params);
            ini_set('xdebug.collect_return', $collect_return);
            if ($tracefile == '') {
                $tracefile = XOOPS_VAR_PATH . '/logs/php_trace';
            }
            xdebug_start_trace($tracefile);
        }
    }

    /**
     * stop_trace - turn off xdebug trace
     *
     * Requires xdebug extension
     *
     * @return void
     */
    public static function stopTrace()
    {
        if (function_exists('xdebug_stop_trace')) {
            xdebug_stop_trace();
        }
    }
}

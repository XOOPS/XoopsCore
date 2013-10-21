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

/**
 * Xoops Event processing, including preload mechanism
 *
 * @category  Xoops\Core\Events
 * @package   Xoops\Core
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Events
{
    /**
     * @var array $preloadList array containing information about the event observers
     */
    protected $preloadList = array();

    /**
     * @var array $eventListeners - $eventListeners['eventname'][]=Closure
     * key is event name, value is array of callables
     */
    protected $eventListeners = array();

    /**
     * @var bool in case of cache miss, try get to get active modules again
     */
    protected $checkAgain = false;

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->setPreloads();
        $this->setEvents();
    }

    /**
     * Allow one instance only!
     *
     * @return Xoops\Core\Events instance
     */
    public static function getInstance()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new \Xoops\Core\Events();
        }

        return $instance;
    }

    /**
     * Get available preloads information and set them to go!
     *
     * @return void
     */
    protected function setPreloads()
    {
        $this->checkAgain = false;
        $modules_list = \Xoops_Cache::read('system_modules_active');
        if (!$modules_list) {
            $modules_list = array ('system');
            $this->checkAgain = true;
        }
        $i = 0;
        foreach ($modules_list as $module) {
            if (is_dir($dir = XOOPS_ROOT_PATH . "/modules/{$module}/preloads/")) {
                $file_list = \XoopsLists::getFileListAsArray($dir);
                foreach ($file_list as $file) {
                    if (preg_match('/(\.php)$/i', $file)) {
                        $file = substr($file, 0, -4);
                        $this->preloadList[$i]['module'] = $module;
                        $this->preloadList[$i]['file'] = $file;
                        $i++;
                    }
                }
            }
        }
    }

    /**
     * Add all preload declared listeners to eventListeners
     *
     * @return void
     */
    protected function setEvents()
    {
        foreach ($this->preloadList as $preload) {
            include_once XOOPS_ROOT_PATH . '/modules/' . $preload['module'] . '/preloads/' . $preload['file']. '.php';
            $class_name = ucfirst($preload['module']) . ucfirst($preload['file']) . 'Preload';
            if (!class_exists($class_name)) {
                continue;
            }
            if (class_exists($class_name) && method_exists($class_name, '__autoload')) {
                call_user_func(array($class_name, '__autoload'));
            }
            $class_methods = get_class_methods($class_name);
            foreach ($class_methods as $method) {
                if (strpos($method, 'event') === 0) {
                    $event_name = strtolower(str_replace('event', '', $method));
                    $event= array($class_name, $method);
                    $this->eventListeners[$event_name][] = $event;
                }
            }
        }
    }

    /**
     * Triggers a specific event
     *
     * @param string $event_name Name of the event to trigger
     * @param array  $args       Method arguments
     *
     * @return void
     */
    public function triggerEvent($event_name, $args = array())
    {
        if ($this->checkAgain) {
            $this->__construct();
        }
        $event_name = $this->internalEventName($event_name);
        if (isset($this->eventListeners[$event_name])) {
            foreach ($this->eventListeners[$event_name] as $event) {
                call_user_func($event, $args);
            }
        }
    }

    /**
     * internalEventName - convert event name to internal form
     * i.e. core.include.common.end becomes coreincludecommonend
     * 
     * @param string $event_name the event name
     * 
     * @return string converted name
     */
    protected function internalEventName($event_name)
    {
        return strtolower(str_replace('.', '', $event_name));
    }

    /**
     * addListener - add a listener, providing a callback for a specific event.
     *  
     * @param string   $event_name the event name
     * @param callable $callback   any callable acceptable for call_user_func
     *  
     * @return void
     */
    public function addListener($event_name, $callback)
    {
        $event_name = $this->internalEventName($event_name);
        $this->eventListeners[$event_name][]=$callback;
    }

    /**
     * getEvents - for debugging only, return list of event listeners
     *
     * @return array of events and listeners
     */
    public function getEvents()
    {
        return $this->eventListeners;
    }
}

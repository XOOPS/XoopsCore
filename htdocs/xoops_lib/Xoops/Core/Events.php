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
 * @copyright 2013 XOOPS Project (http://xoops.org)
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
     * @type bool $eventsEnabled
     */
    protected $eventsEnabled = true;

    /**
     * Constructor
     */
    protected function __construct()
    {
    }

    /**
     * Allow one instance only!
     *
     * @return Events instance
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
     * initializePreloads - Initialize listeners with preload mapped events.
     *
     * We supress event processing during establishing listener map. A a cache miss (on
     * system_modules_active, for example) triggers regeneration, which may trigger events
     * that listeners are not prepared to handle. In such circumstances, module level class
     * mapping will not have been done.
     *
     * @return void
     */
    public function initializeListeners()
    {
        $this->eventsEnabled = false;
        $this->setPreloads();
        $this->setEvents();
        $this->eventsEnabled = true;
    }

    /**
     * Get list of all available preload files
     *
     * @return void
     */
    protected function setPreloads()
    {
        $cache = \Xoops::getInstance()->cache();
        $key = 'system/modules/preloads';
        if (!$this->preloadList = $cache->read($key)) {
            // get active modules from the xoops instance
            $modules_list = \Xoops::getInstance()->getActiveModules();
            if (empty($modules_list)) {
                // this should only happen if an exception was thrown in setActiveModules()
                $modules_list = array ('system');
            }
            $this->preloadList =array();
            $i = 0;
            foreach ($modules_list as $module) {
                if (is_dir($dir = \XoopsBaseConfig::get('root-path') . "/modules/{$module}/preloads/")) {
                    $file_list = \XoopsLists::getFileListAsArray($dir);
                    foreach ($file_list as $file) {
                        if (preg_match('/(\.php)$/i', $file)) {
                            $file = substr($file, 0, -4);
                            $this->preloadList[$i]['module'] = $module;
                            $this->preloadList[$i]['file'] = $file;
                            ++$i;
                        }
                    }
                }
            }
            $cache->write($key, $this->preloadList);
        }
    }

    /**
     * Load all preload files and add all listener methods to eventListeners
     *
     * Preload classes contain methods based on event names. We extract those method
     * names and store to compare against when an event is triggered.
     *
     * Example:
     * An event is triggered as 'core.include.common.end'
     * A PreloadItem class can listen for this event by declaring a static method
     * 'eventCoreIncludeCommonEnd()'
     *
     * PreloadItem class files can be named for the specific source of the
     * events, such as core.php, system.php, etc. In such case the class name is
     * built from the concatenation of the module name, the source and the literal
     * 'Preload'. This mechanism is now considered deprecated. As an example,
     * a module named 'Example' can listen for 'core' events with a file named
     * preloads/core.php, containing a class ExampleCorePreload
     *
     * The prefered preload definition is the unified preloads/preload.php file
     * containing a single PreloadItem class name concatenating the module name and
     * the literal 'Preload'. This class can listen for events from any source.
     *
     * @return void
     */
    protected function setEvents()
    {
        foreach ($this->preloadList as $preload) {
            include_once \XoopsBaseConfig::get('root-path') . '/modules/' . $preload['module'] . '/preloads/' . $preload['file']. '.php';
            $class_name = ucfirst($preload['module'])
                . ($preload['file'] == 'preload' ? '' : ucfirst($preload['file']) )
                . 'Preload';
            if (!class_exists($class_name)) {
                continue;
            }
            $class_methods = get_class_methods($class_name);
            foreach ($class_methods as $method) {
                if (strpos($method, 'event') === 0) {
                    $eventName = strtolower(str_replace('event', '', $method));
                    $event = array($class_name, $method);
                    $this->eventListeners[$eventName][] = $event;
                }
            }
        }
    }

    /**
     * Trigger a specific event
     *
     * @param string $eventName Name of the event to trigger
     * @param mixed  $args      Method arguments
     *
     * @return void
     */
    public function triggerEvent($eventName, $args = array())
    {
        if ($this->eventsEnabled) {
            $eventName = $this->toInternalEventName($eventName);
            if (isset($this->eventListeners[$eventName])) {
                foreach ($this->eventListeners[$eventName] as $event) {
                    if (is_callable($event)) {
                        call_user_func($event, $args);
                    }
                }
            }
        }
    }

    /**
     * toInternalEventName - convert event name to internal form
     * i.e. core.include.common.end becomes coreincludecommonend
     *
     * @param string $eventName the event name
     *
     * @return string converted name
     */
    protected function toInternalEventName($eventName)
    {
        return strtolower(str_replace('.', '', $eventName));
    }

    /**
     * addListener - add a listener, providing a callback for a specific event.
     *
     * @param string   $eventName the event name
     * @param callable $callback  any callable acceptable for call_user_func
     *
     * @return void
     */
    public function addListener($eventName, $callback)
    {
        $eventName = $this->toInternalEventName($eventName);
        $this->eventListeners[$eventName][]=$callback;
    }
    
    /**
     * removeListener - remove a listener.
     *
     * @param string   $eventName the event name
     *
     * @return void
     */
    public function removeListener($eventName)
    {
        $eventName = $this->toInternalEventName($eventName);
        unset($this->eventListeners[$eventName]);
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
    
    /**
     * getPreloads - for debugging only, return list of preloads
     *
     * @return array of preloads
     */
    public function getPreloads()
    {
        return $this->preloadList;
    }

    /**
     * hasListeners - for debugging only, return list of event listeners
     * @param type $eventName event name
     *
     * @return boolean true if one or more listeners are registered for the event
     */
    public function hasListeners($eventName)
    {
        $eventName = $this->toInternalEventName($eventName);
        return array_key_exists($eventName, $this->eventListeners);
    }
}

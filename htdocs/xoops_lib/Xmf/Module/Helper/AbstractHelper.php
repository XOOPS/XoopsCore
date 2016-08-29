<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Module\Helper;

/**
 * Xmf\Module\Helper\AbstractHelper defines the basis for various
 * helpers that simplify routine module tasks.
 *
 * @category  Xmf\Module\Helper\AbstractHelper
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class AbstractHelper
{
    /**
     * @var XoopsModule
     */
    protected $module;

    /**
     * @var bool true if debug is enabled
     */
    protected $debug;

    /**
     * Instantiate a XoopsModule object for the helper to use.
     * The module is determined as follows:
     * - if null is passed, use the current module
     * - if a string is passed, use as dirname to load
     *
     * @param string|null $dirname dirname
     */
    public function __construct($dirname = null)
    {
        $this->module = null;
        if (class_exists('Xoops', false)) {
            $xoops = \Xoops::getInstance();
        }
        if (empty($dirname)) {
            // nothing specified, use current module
            // check if we are running in 2.6
            if (isset($xoops)) {
                if ($xoops->isModule()) {
                    $this->module = $xoops->module;
                }
            } else {
                $this->module = $GLOBALS['xoopsModule'];
            }
        } else {
            // assume dirname specified, try to get a module object
            if (isset($xoops)) {
                $moduleHandler = $xoops->getHandlerModule();
            } else {
                $moduleHandler = xoops_getHandler('module');
            }
            $this->module = $moduleHandler->getByDirname($dirname);
        }
        if (is_object($this->module)) {
            $this->init();
        }
    }

    /**
     * init() is called once/if __construct has a module object.
     * $this->module will have a module object that any further
     * initialization can use.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Set debug option on or off
     *
     * @param bool $bool true to turn on debug logging, false for off
     *
     * @return void
     */
    public function setDebug($bool = true)
    {
        $this->debug = (bool) $bool;
    }

    /**
     * Add a message to the module log
     *
     * @param string $log log message
     *
     * @return void
     */
    public function addLog($log)
    {
        if ($this->debug) {
            \Xoops::getInstance()->logger()->debug($log, array('channel'=>'Extra'));
        }
    }
}

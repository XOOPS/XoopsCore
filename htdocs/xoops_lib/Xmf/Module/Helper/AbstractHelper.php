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

use Xmf\Module\Helper;

/**
 * Xmf\Module\Helper\AbstractHelper defines the basis for various
 * helpers that simplfy routine module tasks.
 * uses.
 *
 * @category  Xmf\Module\Helper\AbstractHelper
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
abstract class AbstractHelper
{
    /**
     * @var XoopsModule
     */
    protected $module;

    /**
     * Instantiate a XoopsModule object for the helper to use.
     * This occurs in one of three ways
     * - if null is passed, use the current module
     * - if a string is passed, use as dirname for a module
     * - if an object is passed, use it as the module object
     *
     * @param mixed $module string dirname | object XoopsModule
     */
    public function __construct($module = null)
    {
        $this->module = null;

        if (empty($module)) {
            // nothing specified, use current module
            // check if we are running in 2.6
            if (class_exists('Xoops', false)) {
                $xoops=\Xoops::getInstance();
                if ($xoops->isModule()) {
                    $this->module = $xoops->module;
                }
            } else {
                $this->module = $GLOBALS['xoopsModule'];
            }
        } elseif (is_scalar($module)) {
            // dirname specified, get a module object
            $helper = Helper::getHelper($module);
            if ($helper) {
                $this->$module = $helper->getModule();
            }
        } else {
            // assume a passed object is appropriate
            if (is_object($module)) {
                $this->module = $module;
            }
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
            if (is_object($GLOBALS['xoopsLogger'])) {
                $GLOBALS['xoopsLogger']->addExtra(get_called_class(), $log);
            }
        }
    }
}

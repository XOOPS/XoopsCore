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
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

define('_MI_LOGGER_NAME', 'Logger');
define('_MI_LOGGER_DSC', 'Error reporting and performance analysis');

define('_MI_LOGGER_DEBUGMODE' , "Debug Mode");
define('_MI_LOGGER_DEBUGMODE0', "Off");
define('_MI_LOGGER_DEBUGMODE1', "Enable debug (inline mode)");
define('_MI_LOGGER_DEBUGMODE2', "Enable debug (popup mode)");
define('_MI_LOGGER_DEBUGMODE3', "Smarty Templates Debug");

define('_MI_LOGGER_DEBUGLEVEL' , "Debug Level");
define('_MI_LOGGER_DEBUGLEVEL0', "Anonymous");
define('_MI_LOGGER_DEBUGLEVEL1', "Registered Users");
define('_MI_LOGGER_DEBUGLEVEL2', "Administrators");

define('_MI_LOGGER_DEBUGPLUGIN' , "Debug Plugin");
define('_MI_LOGGER_DEBUGPLUGIN_LEGACY' , "Legacy");
define('_MI_LOGGER_DEBUGPLUGIN_PQP', "PHP Quick Profiler");
define('_MI_LOGGER_DEBUGPLUGIN_FIREPHP', "FirePHP");
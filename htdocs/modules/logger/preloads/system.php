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
 * LegacyLogger system preloads
 *
 * @category  LegacyLogger
 * @package   LegacyLogger
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class LoggerSystemPreload extends XoopsPreloadItem
{
    /**
     * eventSystemPreferencesSave
     *
     * @param mixed $args arguments supplied to triggerEvent
     *
     * @return void
     */
    public static function eventSystemPreferencesSave($args)
    {
        XoopsLoad::addMap(array('legacylogger' => dirname(dirname(__FILE__)) . '/class/legacylogger.php'));
    }
}

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
 * Debugging toos for developers
 *
 * @category  Xmf\Module\Debug
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Debug
{
    /**
     * configuration ini for krumo
     *
     * @var string
     * 
     * @todo implement resource asset for css
     */
    private static $config = array(
        // 'skin' => array('selected' => 'schablon.com'),
        'skin' => array('selected' => 'modern'),
        'css'  => array('url' => '/modules/xmf/css/krumo/'),
        'display' => array('show_version' => false, 'show_call_info' => false)
        );

    /**
     * Dump a variable
     *
     * @param mixed $var  variable which will be dumped
     * @param bool  $echo echo
     * @param bool  $html dump as html
     * @param bool  $exit exit after dump if true
     *
     * @return mixed|string
     */
    public static function dump($var, $echo = true, $html = true, $exit = false)
    {
        if ($html && $echo && class_exists("\\Kint")) {
            \Kint::dump(func_get_arg(0));
        } else {
            self::$config['css'] = array('url' => XOOPS_URL . '/modules/xmf/css/krumo/');
            if (!$html) {
                $msg = var_export($var, true);
            } else {
                \krumo::setConfig(self::$config);
                $msg = \krumo::dump($var);
            }
            if (!$echo) {
                return $msg;
            }
            echo $msg;
        }
        if ($exit) {
            die();
        }

        return false;
    }

    /**
     * Display debug backtrace
     *
     * @param bool $echo echo
     * @param bool $html dump as html
     * @param bool $exit exit after dump if true
     *
     * @return mixed|string
     */
    public static function backtrace($echo = true, $html = true, $exit = false)
    {
        if ($html && class_exists("\\Kint")) {
            \Kint::trace(debug_backtrace());
            if ($exit) {
                die();
            }
        } else {
            return self::dump(debug_backtrace(), $echo, $html, $exit);
        }
    }
}

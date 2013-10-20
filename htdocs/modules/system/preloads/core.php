<?php
/**
 * System Preloads
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Cointin Maxime (AKA Kraven30)
 * @author          Andricq Nicolas (AKA MusS)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class SystemCorePreload extends XoopsPreloadItem
{
    public static function initialize()
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
			'cookie' => $path . '/class/cookie.php',
			'systemextension' => $path . '/class/extension.php',
			'cookie' => $path . '/class/cookie.php',
			'systemmenuhandler' => $path . '/class/menu.php',
			'systemmodule' => $path . '/class/module.php',
			'system' => $path . '/class/system.php',
			'systembreadcrumb' => $path . '/class/systembreadcrumb.php',
			'systemusers' => $path . '/class/users.php',
			'systemusershandler' => $path . '/class/users.php',
			));
    }
	
    static function eventCoreIncludeFunctionsRedirectheader($args)
    {
        $xoops = Xoops::getInstance();
        $url = $args[0];
        if (preg_match("/[\\0-\\31]|about:|script:/i", $url)) {
            if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
                $url = XOOPS_URL;
            }
        }
        if (!headers_sent() && $xoops->getConfig('redirect_message_ajax') && $xoops->getConfig('redirect_message_ajax')) {
            $_SESSION['redirect_message'] = $args[2];
            header("Location: " . preg_replace("/[&]amp;/i", '&', $url));
            exit();
        }
    }

    static function eventCoreHeaderCheckcache($args)
    {
        if (!empty($_SESSION['redirect_message'])) {
            $xoops = Xoops::getInstance();
            $xoops->theme()->contentCacheLifetime = 0;
            unset($_SESSION['redirect_message']);
        }
    }

    static function eventCoreHeaderAddmeta($args)
    {
        if (!empty($_SESSION['redirect_message'])) {
            $xoops = Xoops::getInstance();
            $xoops->theme()->addStylesheet('xoops.css');
            $xoops->theme()->addScript('media/jquery/jquery.js');
            $xoops->theme()->addScript('media/jquery/plugins/jquery.jgrowl.js');
            $xoops->theme()->addScript('', array('type' => 'text/javascript'), '
            (function($){
                $(document).ready(function(){
                $.jGrowl("' . $_SESSION['redirect_message'] . '", {  life:3000 , position: "center", speed: "slow" });
            });
            })(jQuery);
            ');
        }
    }

    static function eventSystemClassGuiHeader($args)
    {
        if (!empty($_SESSION['redirect_message'])) {
            $xoops = Xoops::getInstance();
            $xoops->theme()->addStylesheet('xoops.css');
            $xoops->theme()->addScript('media/jquery/jquery.js');
            $xoops->theme()->addScript('media/jquery/plugins/jquery.jgrowl.js');
            $xoops->theme()->addScript('', array('type' => 'text/javascript'), '
            (function($){
            $(document).ready(function(){
                $.jGrowl("' . $_SESSION['redirect_message'] . '", {  life:3000 , position: "center", speed: "slow" });
            });
            })(jQuery);
            ');
            unset($_SESSION['redirect_message']);
        }
    }
}
SystemCorePreload::initialize();
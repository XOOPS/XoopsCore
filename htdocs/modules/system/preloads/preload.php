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
 * @copyright       2010-2014 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Cointin Maxime (AKA Kraven30)
 * @author          Andricq Nicolas (AKA MusS)
 */

use Xoops\Core\PreloadItem;
use Xoops\Core\Service\Provider;

class SystemPreload extends PreloadItem
{
    public static function eventCoreIncludeFunctionsRedirectheader($args)
    {
        $xoops = Xoops::getInstance();
        $url = $args[0];
        if (preg_match("/[\\0-\\31]|about:|script:/i", $url)) {
            if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
                $url = \XoopsBaseConfig::get('url');
            }
        }
        if (!headers_sent() && $xoops->getConfig('redirect_message_ajax')
            && $xoops->getConfig('redirect_message_ajax')
        ) {
            $_SESSION['redirect_message'] = $args[2];
            header("Location: " . preg_replace("/[&]amp;/i", '&', $url));
            exit();
        }
    }

    public static function eventCoreHeaderCheckcache($args)
    {
        if (!empty($_SESSION['redirect_message'])) {
            $xoops = Xoops::getInstance();
            $xoops->theme()->contentCacheLifetime = 0;
            unset($_SESSION['redirect_message']);
        }
    }

    public static function eventCoreHeaderAddmeta($args)
    {
        if (!empty($_SESSION['redirect_message'])) {
            $xoops = Xoops::getInstance();
            $xoops->theme()->addBaseStylesheetAssets('xoops.css');
            $xoops->theme()->addBaseScriptAssets('@jquery');
            $xoops->theme()->addBaseScriptAssets('@jgrowl');
            $xoops->theme()->addScript('', array('type' => 'text/javascript'), '
            (function($){
                $(document).ready(function(){
                $.jGrowl("' . $_SESSION['redirect_message'] . '", {  life:3000 , position: "center", speed: "slow" });
            });
            })(jQuery);
            ');
        }
    }

    public static function eventSystemClassGuiHeader($args)
    {
        if (!empty($_SESSION['redirect_message'])) {
            $xoops = Xoops::getInstance();
            $xoops->theme()->addBaseStylesheetAssets('xoops.css');
            $xoops->theme()->addBaseScriptAssets('@jquery');
            $xoops->theme()->addBaseScriptAssets('@jgrowl');
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

    /**
     * listen for core.service.locate.countryflag event
     *
     * @param Provider $provider - provider object for requested service
     *
     * @return void
     */
    public static function eventCoreServiceLocateCountryflag(Provider $provider)
    {
        if (is_a($provider, '\Xoops\Core\Service\Provider')) {
            $path = dirname(__DIR__) . '/class/CountryFlagProvider.php';
            require $path;
            $object = new CountryFlagProvider();
            $provider->register($object);
        }
    }
}

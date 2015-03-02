<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\PreloadItem;
use Xoops\Core\Service\Provider;

/**
 * Gravatars preloads
 *
 * @category  preloads
 * @package   GravatarsPreload
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class GravatarsPreload extends PreloadItem
{
    /**
     * listen for core.service.locate.avatar event
     *
     * @param Provider $provider - provider object for requested service
     *
     * @return void - string in arg[1] will be avatar image url if avaiable
     */
    public static function eventCoreServiceLocateAvatar(Provider $provider)
    {
        if (is_a($provider, '\Xoops\Core\Service\Provider')) {
            $path = dirname(__DIR__) . '/class/GravatarsProvider.php';
            require $path;
            $object = new GravatarsProvider();
            $provider->register($object);
//        echo "<br /><br /><br />"; \Kint::dump($provider, $path);
        }
    }
}

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
 * Avatars module preloads
 *
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class AvatarsPreload extends PreloadItem
{
    /**
     * listen for core.include.common.classmaps
     * add any module specific class map entries
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreIncludeCommonClassmaps($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap(array(
            'avatars' => $path . '/class/helper.php',
        ));
    }

    /**
     * listen for core.service.locate.avatar event
     *
     * @param Provider $provider - provider object for requested service
     *
     * @return void
     */
    public static function eventCoreServiceLocateAvatar(Provider $provider)
    {
        if (is_a($provider, '\Xoops\Core\Service\Provider')) {
            $path = dirname(__DIR__) . '/class/AvatarsProvider.php';
            require $path;
            $object = new AvatarsProvider();
            $provider->register($object);
        }
    }
}

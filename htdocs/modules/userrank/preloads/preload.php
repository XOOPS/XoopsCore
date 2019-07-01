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
 * Userrank preloads
 *
 * @category  preloads
 * @package   UserrankPreload
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class UserrankPreload extends PreloadItem
{
    /**
     * listen for core.service.locate.userrank event
     *
     * @param Provider $provider - provider object for requested service
     */
    public static function eventCoreServiceLocateUserrank(Provider $provider)
    {
        require dirname(__DIR__) . '/class/UserRankProvider.php';
        $object = new UserRankProvider();
        $provider->register($object);
    }
}

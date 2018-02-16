<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Contract;

use Xoops\Core\Kernel\Handlers\XoopsUser;

/**
 * Avatar service interface
 *
 * @category  Xoops\Core\Service\Contract\AvatarInterface
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
interface AvatarInterface
{
    const MODE = \Xoops\Core\Service\Manager::MODE_EXCLUSIVE;

    /**
     * getAvatarUrl - given user info return absolute URL to avatar image
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param mixed    $userinfo XoopsUser object for user or
     *                           array     user info, 'uid', 'uname' and 'email' required
     *                           int       user uid
     *
     * @return void - response->value set to absolute URL to avatar image
     */
    public function getAvatarUrl($response, $userinfo);

    /**
     * getAvatarEditUrl - given user info return absolute URL to edit avatar data
     *
     * @param Response  $response \Xoops\Core\Service\Response object
     * @param XoopsUser $userinfo XoopsUser object for user
     *
     * @return void - response->value set to absolute URL to editing function for avatar data
     */
    public function getAvatarEditUrl($response, XoopsUser $userinfo);
}

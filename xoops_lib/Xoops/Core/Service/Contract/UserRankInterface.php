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

use Xoops\Core\Service\Manager;
use Xoops\Core\Service\Response;

/**
 * UserRank service interface
 *
 * @category  Xoops\Core\Service\Contract
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright  2000-2020 XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      https://xoops.org
 * @since     2.6.0
 */
interface UserRankInterface
{
    const MODE = Manager::MODE_EXCLUSIVE;

    /**
     * getUserRank - given user info return array of rank information for the user
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param mixed    $userinfo Xoops\Core\Kernel\Handlers\XoopsUser object for user (preferred) or
     *                            array of user info,
     *                               'uid'   => (int) id of system user
     *                               'posts' => (int) contribution count associated with the user
     *                               'rank'  => (int) id of manually assigned rank, 0 if none assigned
     */
    public function getUserRank(Response $response, $userinfo);

    /**
     * getAssignableUserRankList - return a list of ranks that can be assigned
     *
     * @param Response $response \Xoops\Core\Service\Response object
     */
    public function getAssignableUserRankList(Response $response);
}

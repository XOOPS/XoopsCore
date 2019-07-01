<?php
/*
 You may not change or alter any portion of this comment or credits of supporting
 developers from this source code or any supporting source code which is considered
 copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Contract;

use Xoops\Core\Service\Data\Email;
use Xoops\Core\Service\Manager;
use Xoops\Core\Service\Response;

/**
 * Email service interface
 *
 * A User Message is a message between two users (i.e. pm)
 *
 * @category  Xoops\Core\Service\Contract
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 The XOOPS Project https://xoops.org
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
interface EmailInterface
{
    const MODE = Manager::MODE_EXCLUSIVE;

    /**
     * sendEmail - send an email
     *
     * @param Response $response response object
     * @param Email    $email    email message to be sent
     */
    public function sendEmail(Response $response, Email $email);
}

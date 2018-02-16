<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Session;

use Xoops\Core\AttributeInterface;

/**
 * Session management
 *
 * @category  Xoops\Core\Session
 * @package   FingerprintInterface
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
interface FingerprintInterface
{
    /**
     * This method manages a fingerprint
     *
     * Check current client Fingerprint against the values saved in the AttributeInterface object.
     * Save the current Fingerprint to the AttributeInterface object
     * Rate the fingerprint match pass/fail based on any changes
     * On fail, clear the AttributeInterface object, leaving only the new client fingerprint
     *
     * @param AttributeInterface $session session manager object or another
     *                                    AttributeInterface implementing object
     *
     * @return bool true if matched, false if not
     */
    public function checkSessionPrint(AttributeInterface $session);
}

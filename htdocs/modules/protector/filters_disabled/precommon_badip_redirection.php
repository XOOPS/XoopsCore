<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Protector
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

// define it as you like :-)

define('PROTECTOR_BADIP_REDIRECTION_URI', 'http://yahoo.com/');

class protector_precommon_badip_redirection extends ProtectorFilterAbstract
{
    public function execute()
    {
        header('Location: ' . PROTECTOR_BADIP_REDIRECTION_URI);
        exit;
    }
}

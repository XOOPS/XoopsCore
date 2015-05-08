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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class protector_precommon_badip_message extends ProtectorFilterAbstract
{
    public function execute()
    {
        echo _MD_PROTECTOR_YOUAREBADIP;
        $protector = Protector::getInstance();
        if ($protector->ip_matched_info) {
            printf(_MD_PROTECTOR_FMT_JAILINFO, date(_MD_PROTECTOR_FMT_JAILTIME, $protector->ip_matched_info));
        }
        exit;
    }
}

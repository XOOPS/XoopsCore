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

define('PROTECTOR_POSTCOMMON_POST_REGISTER_MORATORIUM', 60); // minutes

class protector_postcommon_post_register_moratorium extends ProtectorFilterAbstract
{
    function execute()
    {
        $xoops = Xoops::getInstance();

        if (!$xoops->isUser()) {
            return true;
        }

        $moratorium_result = intval(($xoops->user->getVar('user_regdate') + PROTECTOR_POSTCOMMON_POST_REGISTER_MORATORIUM * 60 - time()) / 60);
        if ($moratorium_result > 0) {
            if (preg_match('#(https?\:|\[\/url\]|www\.)#', serialize($_POST))) {
                printf(_MD_PROTECTOR_FMT_REGISTER_MORATORIUM, $moratorium_result);
                exit;
            }
        }
        return true;
    }
}

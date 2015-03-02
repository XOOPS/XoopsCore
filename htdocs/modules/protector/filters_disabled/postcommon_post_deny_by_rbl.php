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

class protector_postcommon_post_deny_by_rbl extends ProtectorFilterAbstract
{
    function execute()
    {
        $xoops = Xoops::getInstance();
        // RBL servers (don't enable too many servers)
        $rbls = array(
            'sbl-xbl.spamhaus.org',
            #           'niku.2ch.net' ,
            #           'list.dsbl.org' ,
            #           'bl.spamcop.net' ,
            #           'all.rbl.jp' ,
            #           'opm.blitzed.org' ,
            #           'bsb.empty.us' ,
            #           'bsb.spamlookup.net' ,
        );

        $rev_ip = implode('.', array_reverse(explode('.', @$_SERVER['REMOTE_ADDR'])));

        foreach ($rbls as $rbl) {
            $host = $rev_ip . '.' . $rbl;
            if (gethostbyname($host) != $host) {
                $this->protector->message .= "DENY by $rbl\n";
                $uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
                $this->protector->output_log('RBL SPAM', $uid, false, 128);
                die(_MD_PROTECTOR_DENYBYRBL);
            }
        }

        return true;
    }
}

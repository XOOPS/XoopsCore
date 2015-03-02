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

// get your 12-character access key from http://www.projecthoneypot.org/
define('PROTECTOR_HTTPBL_KEY', '............');

class protector_postcommon_post_deny_by_httpbl extends ProtectorFilterAbstract
{
    function execute()
    {
        $xoops = Xoops::getInstance();

        // http:bl servers (don't enable too many servers)
        $rbls = array(
            'http:BL' => PROTECTOR_HTTPBL_KEY . '.%s.dnsbl.httpbl.org',
        );

        $rev_ip = implode('.', array_reverse(explode('.', @$_SERVER['REMOTE_ADDR'])));
        // test
        // $rev_ip = '162.142.248.125' ;

        foreach ($rbls as $rbl_name => $rbl_fmt) {
            $host = sprintf($rbl_fmt, $rev_ip);
            if (gethostbyname($host) != $host) {
                $this->protector->message .= "DENY by $rbl_name\n";
                $uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
                $this->protector->output_log('RBL SPAM', $uid, false, 128);
                die(_MD_PROTECTOR_DENYBYRBL);
            }
        }
        return true;
    }
}

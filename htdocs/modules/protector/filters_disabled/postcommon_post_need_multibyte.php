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

// Don't enable this for site using single-byte
// Perhaps, japanese, schinese, tchinese, and korean can use it

class protector_postcommon_post_need_multibyte extends ProtectorFilterAbstract
{
    function execute()
    {
        $xoops = Xoops::getInstance();

        if (!function_exists('mb_strlen')) {
            return true;
        }

        // registered users always pass this plugin
        if ($xoops->isUser()) {
            return true;
        }

        $lengths = array(
            0          => 100, // default value
            'message'  => 2,
            'com_text' => 2,
            'excerpt'  => 2,
        );

        foreach ($_POST as $key => $data) {
            // dare to ignore arrays/objects
            if (!is_string($data)) {
                continue;
            }

            $check_length = isset($lengths[$key]) ? $lengths[$key] : $lengths[0];
            if (strlen($data) > $check_length) {
                if (strlen($data) == mb_strlen($data)) {
                    $this->protector->message .= "No multibyte character was found ($data)\n";
                    $this->protector->output_log('Singlebyte SPAM', 0, false, 128);
                    die('Protector rejects your post, because your post looks like SPAM');
                }
            }
        }

        return true;
    }
}

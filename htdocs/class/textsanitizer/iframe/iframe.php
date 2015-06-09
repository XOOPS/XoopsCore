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
 * TextSanitizer extension
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

class MytsIframe extends MyTextSanitizerExtension
{
    /**
     * @param MyTextSanitizer $ts
     * @return bool
     */
    public function load(MyTextSanitizer &$ts)
    {
        $ts->patterns[] = "/\[iframe=(['\"]?)([^\"']*)\\1]([^\"]*)\[\/iframe\]/sU";
        $ts->replacements[] = "<iframe src='\\3' width='100%' height='\\2' scrolling='auto' frameborder='yes' marginwidth='0' marginheight='0' noresize></iframe>";

        return true;
    }
}

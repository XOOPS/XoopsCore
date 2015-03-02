<?php
/**
 * Xcaptcha extension module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
define("_MI_XCAPTCHA_ADMENU_TEXT", "Text");

if ( !defined("_XCAPTCHA_TEXT") ) {
    define("_XCAPTCHA_FORM_TEXT", "Configuration CAPTCHA : Texte");
    if (!defined("_XCAPTCHA_NUM_CHARS")) {
        define("_XCAPTCHA_NUM_CHARS", "Number of characters");
    }

    define("_XCAPTCHA_TEXT", true);
}

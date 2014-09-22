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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

define("_MI_XCAPTCHA_ADMENU_RECAPTCHA", "Recaptcha");

if ( !defined("_XCAPTCHA_RECAPTCHA") ) {
    define("_XCAPTCHA_FORM_RECAPTCHA", "Configuration CAPTCHA : Recaptcha");

    define("_XCAPTCHA_PRIVATE_KEY", "Private key");
    define("_XCAPTCHA_PUBLIC_KEY", "Public key");
    define("_XCAPTCHA_THEME", "Theme");
    define("_XCAPTCHA_LANG", "Language");

    define("_XCAPTCHA_RECAPTCHA", true);
}

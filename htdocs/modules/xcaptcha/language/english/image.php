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

define("_MI_XCAPTCHA_ADMENU_IMAGE", "Image");

if ( !defined("_XCAPTCHA_IMAGE") ) {
    define("_XCAPTCHA_FORM_IMAGE", "Configuration CAPTCHA : Image");

    if (!defined("_XCAPTCHA_NUM_CHARS")) {
        define("_XCAPTCHA_NUM_CHARS", "Number of characters");
    }
    define("_XCAPTCHA_CASESENSITIVE", "Case insensitive");
    define("_XCAPTCHA_FONTSIZE_MIN", "Minimum size of the font");
    define("_XCAPTCHA_FONTSIZE_MAX", "Maximum size of the font");
    define("_XCAPTCHA_BACKGROUND_TYPE", "Background type");

    define("_XCAPTCHA_BACKGROUND_BAR", "Bars");
    define("_XCAPTCHA_BACKGROUND_CIRCLE", "Circles");
    define("_XCAPTCHA_BACKGROUND_LINE", "Lines");
    define("_XCAPTCHA_BACKGROUND_RECTANGLE", "Rectangles");
    define("_XCAPTCHA_BACKGROUND_ELLIPSE", "Ellipses");
    define("_XCAPTCHA_BACKGROUND_POLYGONE", "Polygons");
    define("_XCAPTCHA_BACKGROUND_IMAGE", "Image");

    define("_XCAPTCHA_BACKGROUND_NUM", "Number of \"drawing\"");
    define("_XCAPTCHA_POLYGON_POINT", "Number of points for the polygon");
    define("_XCAPTCHA_SKIP_CHARACTERS", "Ignore characters");

    define("_XCAPTCHA_IMAGE", true);
}

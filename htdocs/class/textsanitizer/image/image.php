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

class MytsImage extends MyTextSanitizerExtension
{
    /**
     * @param MyTextSanitizer $ts
     * @return bool
     */
    public function load(MyTextSanitizer &$ts)
    {
        static $jsLoaded;

        $xoops = Xoops::getInstance();
        $config = $this->loadConfig(__DIR__);
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 width=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img width=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

		$xoops_url = \XoopsBaseConfig::get('url');
		
        if (empty($ts->config['allowimage'])) {
            $ts->replacements[] = '<a href="\\5" rel="external">\\5</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\1" rel="external">\\1</a>';

            $ts->replacements[] = '<a href="' . $xoops_url . '/image.php?id=\\4" rel="external" title="\\5">\\5</a>';
            $ts->replacements[] = '<a href="' . $xoops_url . '/image.php?id=\\2" rel="external" title="\\3">\\3</a>';

        } else {
            if (!empty($config['resize']) && empty($config['clickable']) && !empty($config['max_width']) && is_object($xoops->theme())) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $xoops->theme()->addScript('/class/textsanitizer/image/image.js', array(
                            'type' => 'text/javascript'
                        ));
                }
                $resizedStr = XoopsLocale::RESIZED_IMAGE;
                $ts->replacements[] = "<img src='\\5' class='\\2' alt='" . $resizedStr . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' alt='" . $resizedStr . "' border='0'" . ($config['resize']
                    ? "onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
                $ts->replacements[] = "<img src='\\3' alt='" . $resizedStr . "' border='0' onload=\"JavaScript:if(this.width>\\2)this.width=\\2\" /><br />";
                $ts->replacements[] = "<img src='\\1' alt='" . $resizedStr . "' border='0'" . ($config['resize']
                    ? " onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";

            } else {
                if (!empty($config['clickable']) && !empty($config['max_width']) && is_object($xoops->theme())) {
                    if (!$jsLoaded) {
                        $jsLoaded = true;
                        $xoops->theme()->addScript('/class/textsanitizer/image/image.js', array(
                                'type' => 'text/javascript'
                            ));
                    }
                    $openImageStr = XoopsLocale::CLICK_TO_SEE_ORIGINAL_IMAGE_IN_NEW_WINDOW;
                    $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\5\");'><img src='\\5' class='\\2' alt='" . $openImageStr . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" /></a>";
                    $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\3\");'><img src='\\3' class='\\2' alt='" . $openImageStr . "' border='0' " . ($config['resize']
                        ? "onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/></a>";
                    $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\3\");'><img src='\\3' alt='" . $openImageStr . "' border='0' onload=\"JavaScript:if(this.width>\\2)this.width=\\2\" /></a><br />";
                    $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\1\");'><img src='\\1' alt='" . $openImageStr . "' border='0' title='" . $openImageStr . "'" . ($config['resize']
                        ? " onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/></a>";
                } else {
                    $originalStr = XoopsLocale::ORIGINAL_IMAGE;
                    $ts->replacements[] = "<img src='\\5' class='\\2' border='0' alt='" . $originalStr . "' onload=\"JavaScript:if(this.width>\\4) this.width=\\4\" />";
                    $ts->replacements[] = "<img src='\\3' class='\\2' border='0' alt='" . $originalStr . "' " . ($config['resize']
                        ? "onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
                    $ts->replacements[] = "<img src='\\3' border='0' alt='" . $originalStr . "' onload=\"JavaScript:if(this.width>\\2) this.width=\\2\" />";
                    $ts->replacements[] = "<img src='\\1' border='0' alt='" . $originalStr . "' " . ($config['resize']
                        ? " onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
                }
            }
            $ts->replacements[] = '<img src="' . $xoops_url . '/image.php?id=\\4" class="\\2" title="\\5" />';
            $ts->replacements[] = '<img src="' . $xoops_url . '/image.php?id=\\2" title="\\3" />';
        }
        return true;
    }
}

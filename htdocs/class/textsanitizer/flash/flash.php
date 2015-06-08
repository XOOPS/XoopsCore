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

class MytsFlash extends MyTextSanitizerExtension
{
    /**
     * @param int $textarea_id
     * @return string[]
     */
    public function encode($textarea_id)
    {
        $config = parent::loadConfig(__DIR__);
        $code = "<img src='{$this->image_path}/swf.gif' alt='" . XoopsLocale::FLASH . "' onclick='xoopsCodeFlash(\"{$textarea_id}\",\"" . htmlspecialchars(XoopsLocale::FLASH_URL, ENT_QUOTES) . "\",\"" . htmlspecialchars(XoopsLocale::HEIGHT, ENT_QUOTES) . "\",\"" . htmlspecialchars(XoopsLocale::WIDTH, ENT_QUOTES) . "\", \"" . $config['detect_dimension'] . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOF
            function xoopsCodeFlash(id, enterFlashPhrase, enterFlashHeightPhrase, enterFlashWidthPhrase, enableDimensionDetect)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterFlashPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var text2 = enableDimensionDetect ? "" : prompt(enterFlashWidthPhrase, "");
                    var text3 = enableDimensionDetect ? "" : prompt(enterFlashHeightPhrase, "");
                    var result = "[flash="+text2+","+text3+"]" + text + "[/flash]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOF;

        return array(
            $code, $javascript
        );
    }
    static function myCallback($match) {
    return self::decode( $match[5], $match[3], $match[4] );
    }

    /**
     * @param MyTextSanitizer $ts
     * @return bool
     */
    public function load(MyTextSanitizer &$ts)
    {
//        $ts->patterns[] = "/\[(swf|flash)=(['\"]?)([^\"']*),([^\"']*)\\2]([^\"]*)\[\/\\1\]/esU";
//        $ts->replacements[] = __CLASS__ . "::decode( '\\5', '\\3', '\\4' )";

//mb------------------------------
        $ts->callbackPatterns[] = "/\[(swf|flash)=(['\"]?)([^\"']*),([^\"']*)\\2]([^\"]*)\[\/\\1\]/sU";
        $ts->callbacks[] = __CLASS__ . "::myCallback";
//mb------------------------------

        return true;
    }

    /**
     * @param string $url
     * @param int $width
     * @param int $height
     * @return string
     */
    public static function decode($url, $width, $height)
    {
        $config = parent::loadConfig(__DIR__);
        if ((empty($width) || empty($height)) && !empty($config['detect_dimension'])) {
            if (!$dimension = @getimagesize($url)) {
                return "<a href='{$url}' rel='external' title=''>{$url}</a>";
            }
            if (!empty($width)) {
                $height = $dimension[1] * $width / $dimension[0];
            } else {
                if (!empty($height)) {
                    $width = $dimension[0] * $height / $dimension[1];
                } else {
                    list ($width, $height) = array(
                        $dimension[0], $dimension[1]
                    );
                }
            }
        }

        $rp = "<object width='{$width}' height='{$height}' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0'>";
        $rp .= "<param name='movie' value='{$url}'>";
        $rp .= "<param name='QUALITY' value='high'>";
        $rp .= "<PARAM NAME='bgcolor' VALUE='#FFFFFF'>";
        $rp .= "<param name='wmode' value='transparent'>";
        $rp .= "<embed src='{$url}' width='{$width}' height='{$height}' quality='high' bgcolor='#FFFFFF' wmode='transparent'  pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'></embed>";
        $rp .= "</object>";
        return $rp;
    }
}

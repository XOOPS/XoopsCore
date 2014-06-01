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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MytsMp3 extends MyTextSanitizerExtension
{
    /**
     * @param int $textarea_id
     * @return array
     */
    public function encode($textarea_id)
    {
        $code = "<img src='{$this->image_path}/mp3.gif' alt='" . XoopsLocale::MP3 . "'  onclick='xoopsCodeMp3(\"{$textarea_id}\",\"" . htmlspecialchars(XoopsLocale::MP3_URL, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOF
            function xoopsCodeMp3(id, enterMp3Phrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterMp3Phrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var result = "[mp3]" + text + "[/mp3]";
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
        return  self::decode($match[1]);
    }

    public function load(MyTextSanitizer &$ts)
    {
//        $ts->patterns[] = "/\[mp3\](.*?)\[\/mp3\]/es";
//        $ts->replacements[] = __CLASS__ . "::decode( '\\1' )";
//mb------------------------------
        $ts->callbackPatterns[] = "/\[mp3\](.*?)\[\/mp3\]/s";
        $ts->callbacks[] = __CLASS__ . "::myCallback";
//mb------------------------------

        return true;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function decode ($url, $width, $height)
    {
        $rp = "<embed flashvars=\"playerID=1&amp;bg=0xf8f8f8&amp;leftbg=0x3786b3&amp;lefticon=0x78bee3&amp;rightbg=0x3786b3&amp;rightbghover=0x78bee3&amp;righticon=0x78bee3&amp;righticonhover=0x3786b3&amp;text=0x666666&amp;slider=0x3786b3&amp;track=0xcccccc&amp;border=0x666666&amp;loader=0x78bee3&amp;loop=no&amp;soundFile={$url}\" quality='high' menu='false' wmode='transparent' pluginspage='http://www.macromedia.com/go/getflashplayer' src='" . XOOPS_URL . "/assets/images/form/player.swf'  width=290 height=24 type='application/x-shockwave-flash'></embed>";
        return $rp;
    }
}

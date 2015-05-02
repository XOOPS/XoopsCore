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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */
 
class MytsSoundcloud extends MyTextSanitizerExtension
{
    public function encode($textarea_id)
    {
        $config = parent::loadConfig(__DIR__);
        $alttxt = htmlspecialchars(XoopsLocale::SOUNDCLOUD, ENT_QUOTES);
        $prompt = htmlspecialchars(XoopsLocale::SOUNDCLOUD_URL, ENT_QUOTES);
        $code = "<img src='{$this->image_path}/soundcloud.png' "
            . " alt='{$alttxt}' title='{$alttxt}' "
            . " onclick='xoopsCodeSoundCloud(\"{$textarea_id}\",\"{$prompt}\");' "
            . " onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOH
            function xoopsCodeSoundCloud(id, enterSoundCloud)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterSoundCloud, "");
                }

                var domobj = xoopsGetElementById(id);
                if (text.length > 0) {
                    xoopsInsertText(domobj, "[soundcloud]"+text+"[/soundcloud]");
                }
                domobj.focus();
            }
EOH;

        return array($code, $javascript);
    }

    public function load(&$ts)
    {
        $ts->callbackPatterns[] = "/\[soundcloud\](http[s]?:\/\/[^\"'<>]*)(.*)\[\/soundcloud\]/sU";
        $ts->callbacks[] = __CLASS__ . "::myCallback";
    }

    public static function myCallback($match)
    {
        $url = $match[1] . $match[2];
        $config = parent::loadConfig(__DIR__);
        if (!preg_match("/^http[s]?:\/\/(www\.)?soundcloud\.com\/(.*)/i", $url, $matches)) {
            trigger_error("Not matched: {$url}", E_USER_WARNING);

            return "";
        }

        $code = '<object height="81" width="100%"><param name="movie" '
            . 'value="http://player.soundcloud.com/player.swf?url='.$url.'&amp;g=bb">'
            . '</param><param name="allowscriptaccess" value="always"></param>'
            . '<embed allowscriptaccess="always" height="81" '
            . 'src="http://player.soundcloud.com/player.swf?url=' . $url
            . '&amp;g=bb" type="application/x-shockwave-flash" width="100%"></embed></object>'
            . '<a href="'.$url.'">'.$url.'</a>';

        return $code;
    }
}

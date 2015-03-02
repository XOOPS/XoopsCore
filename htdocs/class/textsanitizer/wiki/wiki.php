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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MytsWiki extends MyTextSanitizerExtension
{
    /**
     * @param int $textarea_id
     * @return string[]
     */
    public function encode($textarea_id)
    {
        $code = "<img src='{$this->image_path}/wiki.gif' alt='" .XoopsLocale::WIKI . "' onclick='xoopsCodeWiki(\"{$textarea_id}\",\"" . htmlspecialchars(XoopsLocale::WIKI_WORD_TO_LINK, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOH
            function xoopsCodeWiki(id, enterWikiPhrase){
                if (enterWikiPhrase == null) {
                    enterWikiPhrase = "Enter the word to be linked to Wiki:";
                }
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                }else {
                    var text = prompt(enterWikiPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text != null && text != "" ) {
                    var result = "[[" + text + "]]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
        return array(
            $code, $javascript
        );
    }

    static function myCallback($match) {
        return  self::decode( $match[1] );
    }
    /**
     * @param MyTextSanitizer $ts
     * @return void
     */
    public function load(MyTextSanitizer &$ts)
    {
//        $ts->patterns[] = "/\[\[([^\]]*)\]\]/esU";
//        $ts->replacements[] = __CLASS__ . "::decode( '\\1' )";
//mb------------------------------
        $ts->callbackPatterns[] = "/\[\[([^\]]*)\]\]/sU";
        $ts->callbacks[] = __CLASS__ . "::myCallback";
//mb------------------------------
    }

    /**
     * @return string
     */
    public static function decode ($url, $width, $height)
    {
        $config = parent::loadConfig(__DIR__);
        if (empty($url) || empty($config['link'])) {
            return $url;
        }
        $charset = !empty($config['charset']) ? $config['charset'] : "UTF-8";
        $ret = "<a href='" . sprintf($config['link'], urlencode(XoopsLocale::convert_encoding($url, $charset))) . "' rel='external' title=''>{$text}</a>";
        return $ret;
    }
}

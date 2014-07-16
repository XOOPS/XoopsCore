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

class MytsRtsp extends MyTextSanitizerExtension
{
    /**
     * @param int $textarea_id
     * @return string[]
     */
    public function encode($textarea_id)
    {
        $code = "<img src='{$this->image_path}/rtspimg.gif' alt='" . XoopsLocale::REAL_PLAYER . "' onclick='xoopsCodeRtsp(\"{$textarea_id}\",\"" . htmlspecialchars(XoopsLocale::RTSP_URL, ENT_QUOTES) . "\",\"" . htmlspecialchars(XoopsLocale::HEIGHT, ENT_QUOTES) . "\",\"" . htmlspecialchars(XoopsLocale::WIDTH, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOH
            function xoopsCodeRtsp(id,enterRtspPhrase, enterRtspHeightPhrase, enterRtspWidthPhrase){
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                        var selection = "rtsp://"+selection;
                        var text = selection;
                    } else {
                        var text = prompt(enterRtspPhrase+"       Rtsp or http", "Rtsp://");
                    }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 && text!="rtsp://") {
                    var text2 = prompt(enterRtspWidthPhrase, "480");
                    var text3 = prompt(enterRtspHeightPhrase, "330");
                    var result = "[rtsp="+text2+","+text3+"]" + text + "[/rtsp]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
        return array($code, $javascript);
    }

    /**
     * @param MyTextSanitizer $ts
     * @return void
     */
    public function load(MyTextSanitizer &$ts)
    {
        $ts->patterns[] = "/\[rtsp=(['\"]?)([^\"']*),([^\"']*)\\1]([^\"]*)\[\/rtsp\]/sU";
        $rp = "<object classid=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" HEIGHT='\\3' ID=Player WIDTH='\\2' VIEWASTEXT>";
        $rp .= "<param NAME=\"_ExtentX\" VALUE=\"12726\">";
        $rp .= "<param NAME=\"_ExtentY\" VALUE=\"8520\">";
        $rp .= "<param NAME=\"AUTOSTART\" VALUE=\"0\">";
        $rp .= "<param NAME=\"SHUFFLE\" VALUE=\"0\">";
        $rp .= "<param NAME=\"PREFETCH\" VALUE=\"0\">";
        $rp .= "<param NAME=\"NOLABELS\" VALUE=\"0\">";
        $rp .= "<param NAME=\"CONTROLS\" VALUE=\"ImageWindow\">";
        $rp .= "<param NAME=\"CONSOLE\" VALUE=\"_master\">";
        $rp .= "<param NAME=\"LOOP\" VALUE=\"0\">";
        $rp .= "<param NAME=\"NUMLOOP\" VALUE=\"0\">";
        $rp .= "<param NAME=\"CENTER\" VALUE=\"0\">";
        $rp .= "<param NAME=\"MAINTAINASPECT\" VALUE=\"1\">";
        $rp .= "<param NAME=\"BACKGROUNDCOLOR\" VALUE=\"#000000\">";
        $rp .= "<param NAME=\"SRC\" VALUE=\"\\4\">";
        $rp .= "<embed autostart=\"0\" src=\"\\4\" type=\"audio/x-pn-realaudio-plugin\" HEIGHT='\\3' WIDTH='\\2' controls=\"ImageWindow\" console=\"cons\"> </embed>";
        $rp .= "</object>";
        $rp .= "<br /><object CLASSID=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA HEIGHT=32 ID=Player WIDTH='\\2' VIEWASTEXT>";
        $rp .= "<param NAME=\"_ExtentX\" VALUE=\"18256\">";
        $rp .= "<param NAME=\"_ExtentY\" VALUE=\"794\">";
        $rp .= "<param NAME=\"AUTOSTART\" VALUE=\"0\">";
        $rp .= "<param NAME=\"SHUFFLE\" VALUE=\"0\">";
        $rp .= "<param NAME=\"PREFETCH\" VALUE=\"0\">";
        $rp .= "<param NAME=\"NOLABELS\" VALUE=\"0\">";
        $rp .= "<param NAME=\"CONTROLS\" VALUE=\"controlpanel\">";
        $rp .= "<param NAME=\"CONSOLE\" VALUE=\"_master\">";
        $rp .= "<param NAME=\"LOOP\" VALUE=\"0\">";
        $rp .= "<param NAME=\"NUMLOOP\" VALUE=\"0\">";
        $rp .= "<param NAME=\"CENTER\" VALUE=\"0\">";
        $rp .= "<param NAME=\"MAINTAINASPECT\" VALUE=\"0\">";
        $rp .= "<param NAME=\"BACKGROUNDCOLOR\" VALUE=\"#000000\">";
        $rp .= "<param NAME=\"SRC\" VALUE=\"\\4\">";
        $rp .= "<embed autostart=\"0\" src=\"\\4\" type=\"audio/x-pn-realaudio-plugin\" HEIGHT='30' WIDTH='\\2' controls=\"ControlPanel\" console=\"cons\"> </embed>";
        $rp .= "</object>";

        $ts->replacements[] = $rp;
    }
}
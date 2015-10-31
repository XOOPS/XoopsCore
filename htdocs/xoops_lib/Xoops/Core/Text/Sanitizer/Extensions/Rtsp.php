<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text\Sanitizer\Extensions;

use Xoops\Core\Text\Sanitizer;
use Xoops\Core\Text\Sanitizer\ExtensionAbstract;

/**
 * Sanitizer extension for rtsp, real player
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Rtsp extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
    ];

    /**
     * Provide button and javascript code used by the DhtmlTextArea
     *
     * @param string $textAreaId dom element id
     *
     * @return string[] editor button as HTML, supporting javascript
     */
    public function getDhtmlEditorSupport($textAreaId)
    {
        $buttonCode = $this->getEditorButtonHtml(
            $textAreaId,
            'rtspimg.gif',
            \XoopsLocale::REAL_PLAYER,
            'xoopsCodeRtsp',
            \XoopsLocale::RTSP_URL,
            \XoopsLocale::HEIGHT,
            \XoopsLocale::WIDTH
        );

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
        return array($buttonCode, $javascript);
    }

    /**
     * Register extension with the supplied sanitizer instance
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {

        $this->shortcodes->addShortcode(
            'rtsp',
            function ($attributes, $content, $tagName) {
                $args = ltrim($attributes[0], '=');
                list($width, $height) = explode(',', $args);
                $url = $content;

                $template = <<<'EOT'
    <object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" HEIGHT="%3$s" ID=Player WIDTH="%2$s" VIEWASTEXT>
        <param NAME="_ExtentX" VALUE="12726">
        <param NAME="_ExtentY" VALUE="8520">
        <param NAME="AUTOSTART" VALUE="0">
        <param NAME="SHUFFLE" VALUE="0">
        <param NAME="PREFETCH" VALUE="0">
        <param NAME="NOLABELS" VALUE="0">
        <param NAME="CONTROLS" VALUE="ImageWindow">
        <param NAME="CONSOLE" VALUE="_master">
        <param NAME="LOOP" VALUE="0">
        <param NAME="NUMLOOP" VALUE="0">
        <param NAME="CENTER" VALUE="0">
        <param NAME="MAINTAINASPECT" VALUE="1">
        <param NAME="BACKGROUNDCOLOR" VALUE="#000000">
        <param NAME="SRC" VALUE="%1$s">
        <embed autostart="0" src="%1$s" type="audio/x-pn-realaudio-plugin" HEIGHT="%3$s" WIDTH="%2$s" controls="ImageWindow" console="cons"> </embed>
    </object>
    <br />
    <object CLASSID=clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA HEIGHT=32 ID=Player WIDTH="%2$s" VIEWASTEXT>
        <param NAME="_ExtentX" VALUE="18256">
        <param NAME="_ExtentY" VALUE="794">
        <param NAME="AUTOSTART" VALUE="0">
        <param NAME="SHUFFLE" VALUE="0">
        <param NAME="PREFETCH" VALUE="0">
        <param NAME="NOLABELS" VALUE="0">
        <param NAME="CONTROLS" VALUE="controlpanel">
        <param NAME="CONSOLE" VALUE="_master">
        <param NAME="LOOP" VALUE="0">
        <param NAME="NUMLOOP" VALUE="0">
        <param NAME="CENTER" VALUE="0">
        <param NAME="MAINTAINASPECT" VALUE="0">
        <param NAME="BACKGROUNDCOLOR" VALUE="#000000">
        <param NAME="SRC" VALUE="%1$s">
        <embed autostart="0" src="%1$s" type="audio/x-pn-realaudio-plugin" HEIGHT='30' WIDTH='%2$s' controls="ControlPanel" console="cons"> </embed>
    </object>
EOT;

                $newContent = sprintf($template, $url, $width, $height);
                return $newContent;
            }
        );
    }
}

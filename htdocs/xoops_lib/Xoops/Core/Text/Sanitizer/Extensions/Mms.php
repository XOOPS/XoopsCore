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
 * TextSanitizer extension
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Mms extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = ['enabled' => false];

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
            'wmp.gif',
            \XoopsLocale::MMS,
            'xoopsCodeWmp',
            \XoopsLocale::MMS_URL,
            \XoopsLocale::HEIGHT,
            \XoopsLocale::WIDTH
        );

        $javascript = <<<EOH
            function xoopsCodeMms(id, enterMmsPhrase, enterMmsHeightPhrase, enterMmsWidthPhrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var selection="mms://"+selection;
                    var text = selection;
                } else {
                    var text = prompt(enterMmsPhrase+"       mms or http", "mms://");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 && text != "mms://") {
                    var text2 = prompt(enterMmsWidthPhrase, "480");
                    var text3 = prompt(enterMmsHeightPhrase, "330");
                    var result = "[mms="+text2+","+text3+"]" + text + "[/mms]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
        return [$buttonCode, $javascript];
    }

    /**
     * Register extension with the supplied sanitizer instance
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {
        $this->shortcodes->addShortcode(
            'mms',
            function ($attributes, $content, $tagName) {
                $args = ltrim($attributes[0], '=');
                list($width, $height) = explode(',', $args);
                $url = $content;

                $template = <<<'EOT'
    <object id=videowindow1 height="%3$s" width="%2$s" classid="CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6">
        <param name="URL" value="%1$s">
        <param name="rate" value="1">
        <param name="balance" value="0">
        <param name="currentPosition" value="0">
        <param name="defaultFrame" value="">
        <param name="playCount" value="1">
        <param name="autoStart" value="0">
        <param name="currentMarker" value="0">
        <param name="invokeURLs" value="-1">
        <param name="baseURL" value="">
        <param name="volume" value="50">
        <param name="mute" value="0">
        <param name="uiMode" value="full">
        <param name="stretchToFit" value="0">
        <param name="windowlessVideo" value="0">
        <param name="enabled" value="-1">
        <param name="enableContextMenu" value="-1">
        <param name="fullScreen" value="0">
        <param name="SAMIStyle" value="">
        <param name="SAMILang" value="">
        <param name="SAMIFilename" value="">
        <param name="captioningID" value="">
        <param name="enableErrorDialogs" value="0">
        <param name="_cx" value="12700">
        <param name="_cy" value="8731">
    </object>
EOT;

                $newContent = sprintf($template, $url, $width, $height);
                return $newContent;
            }
        );
    }
}

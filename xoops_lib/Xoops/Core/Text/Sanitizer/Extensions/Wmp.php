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
 * @copyright 2000-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Wmp extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
        'enable_wmp_entry' => false,  // false to disable entry button in editor, existing content will still play
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
        if (false === $this->config['enable_wmp_entry']) {
            return parent::getDhtmlEditorSupport($textAreaId);
        }

        $buttonCode = $this->getEditorButtonHtml(
            $textAreaId,
            'fa fa-fw fa-windows',
            \XoopsLocale::WMP,
            'xoopsCodeWmp',
            \XoopsLocale::WMP_URL,
            \XoopsLocale::HEIGHT,
            \XoopsLocale::WIDTH
        );

        $javascript = <<<EOH
            function xoopsCodeWmp(id, enterWmpPhrase, enterWmpHeightPhrase, enterWmpWidthPhrase) {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterWmpPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var text2 = prompt(enterWmpWidthPhrase, "480");
                    var text3 = prompt(enterWmpHeightPhrase, "330");
                    var result = "[wmp="+text2+","+text3+"]" + text + "[/wmp]";
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
            'wmp',
            function ($attributes, $content, $tagName) {
                $args = ltrim($attributes[0], '=');
                list($width, $height) = explode(',', $args);
                $url = $content;

                $template = '<object classid="clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6"'
                    . ' id="WindowsMediaPlayer" width="%2$s" height="%3$s">' . "\n"
                    . '<param name="URL" value="%1$s">'. "\n"
                    . '<param name="AutoStart" value="0">' . "\n"
                    . '<embed autostart="0" src="%1$s" type="video/x-ms-wmv" width="%2$s" height="%3$s"'
                    . ' controls="ImageWindow" console="cons"> </embed>' . "\n"
                    . '</object>' . "\n";
                $newContent = sprintf($template, $url, $width, $height);
                return $newContent;
            }
        );
    }
}

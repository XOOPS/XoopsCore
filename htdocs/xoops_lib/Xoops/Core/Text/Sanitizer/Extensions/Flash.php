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
 * Sanitizer extension for flash content
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Flash extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'detect_dimension' => '1',
        'template' => '<object type="application/x-shockwave-flash" data="%1$s" width="%2$d" height="%3$d"></object>',
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
            'swf.gif',
            \XoopsLocale::FLASH,
            'xoopsCodeFlash',
            \XoopsLocale::FLASH_URL,
            \XoopsLocale::HEIGHT,
            \XoopsLocale::WIDTH,
            $this->config['detect_dimension']
        );

        $javascript = <<<EOF

            function xoopsCodeFlash(id, enterFlashPhrase, enterFlashHeightPhrase, enterFlashWidthPhrase, enableDimensionDetect)
            {
                enableDimensionDetect = Boolean(parseInt(enableDimensionDetect));
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
                    if (text2.length == 0 && text2.length == 0) {
                        var result = "[flash]" + text + "[/flash]";
                    } else {
                        var result = "[flash="+text2+","+text3+"]" + text + "[/flash]";
                    }
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }

EOF;

        return [$buttonCode, $javascript];
    }

    /**
     * Register extension with the supplied sanitizer instance
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {
        $function = function ($attributes, $content, $tagName) {
            $args = ltrim($attributes[0], '=');
            list($width, $height) = explode(',', $args);

            if ((empty($width) || empty($height)) && (bool)$this->config['detect_dimension']) {
                $dimension = @getimagesize($content);
                if ($dimension === false) {
                    return "<a href='{$content}' rel='external'>{$content}</a>";
                }
                list($width, $height) = $dimension;
            }
            if (empty($width) || empty($height)) {
                $width = 100;
                $height = 100;
            }

            $template = $this->config['template'];
            $newcontent = sprintf($template, $content, $width, $height);
            return $newcontent;
        };

        $this->shortcodes->addShortcode('flash', $function);
        $this->shortcodes->addShortcode('swf', $function);
    }
}

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
 * @copyright 2000-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Flash extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
        'detect_dimension' => '1',
        'template' => '<object type="application/x-shockwave-flash" data="%1$s" width="%2$d" height="%3$d"></object>',
        'fallback_width' => '320',
        'fallback_height' => '240',
        'enable_flash_entry' => false,  // false to disable entry button in editor, existing content will still play
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
        if (false === $this->config['enable_flash_entry']) {
            return parent::getDhtmlEditorSupport($textAreaId);
        }

        $buttonCode = $this->getEditorButtonHtml(
            $textAreaId,
            'fa fa-fw fa-flash',
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
                        var result = '[flash url="'+text+'" /]';
                    } else {
                        var result = '[flash url="'+text+'" width='+text2+' height='+text3+' /]';
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
            if (array_key_exists(0, $attributes) && '=' === mb_substr($attributes[0], 0, 1)) {
                $args = ltrim($attributes[0], '=');
                list($width, $height) = explode(',', $args);
                $url = $content;
            } else {
                $defaults = [
                    'url' => trim($content),
                    'width' => null,
                    'height' => null,
                ];
                $cleanAttributes = $this->shortcodes->shortcodeAttributes($defaults, $attributes);
                $url = $cleanAttributes['url'];
                $width = $cleanAttributes['width'];
                $height = $cleanAttributes['height'];
            }
            if ((empty($width) || empty($height)) && (bool)$this->config['detect_dimension']) {
                $dimension = @getimagesize($content);
                if (false !== $dimension) {
                    list($width, $height) = $dimension;
                }
            }
            if (empty($width) || empty($height)) {
                $width = $this->config['fallback_width'];
                $height = $this->config['fallback_height'];
            }

            $template = $this->config['template'];
            $newcontent = sprintf($template, $url, $width, $height);

            return $newcontent;
        };

        $this->shortcodes->addShortcode('flash', $function);
        $this->shortcodes->addShortcode('swf', $function);
    }
}

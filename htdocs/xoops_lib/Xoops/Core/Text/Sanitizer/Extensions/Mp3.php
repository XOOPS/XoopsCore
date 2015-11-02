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
class Mp3 extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'template' => '<audio controls><source src="%1$s" type="audio/mpeg"></audio>',
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
            'mp3.gif',
            \XoopsLocale::MP3,
            'xoopsCodeMp3',
            \XoopsLocale::MP3_URL
        );

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
                    xoopsInsertText(domobj, '[mp3 url="'+text+'" /]');
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
        $this->shortcodes->addShortcode(
            'mp3',
            function ($attributes, $content, $tagName) {
                $defaults = [
                    'url'    => trim($content),
                ];
                $cleanAttributes = $this->shortcodes->shortcodeAttributes($defaults, $attributes);
                $newContent = sprintf($this->config['template'], $cleanAttributes['url']);

                return $newContent;
            }
        );
    }
}

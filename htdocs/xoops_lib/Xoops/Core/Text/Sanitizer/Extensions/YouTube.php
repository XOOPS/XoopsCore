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
 * YouTube extension
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class YouTube extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'template' => '<div class="embed-responsive %4$s">
            <iframe class="embed-responsive-item" width="%2$d" height="%3$d" src="https://www.youtube.com/embed/%1$s" frameborder="0" allowfullscreen></iframe>
            </div>',
        'width'  => "640",
        'height' => "385",
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
            'youtube.gif',
            \XoopsLocale::YOUTUBE,
            'xoopsCodeYoutube',
            \XoopsLocale::YOUTUBE_URL,
            \XoopsLocale::HEIGHT,
            \XoopsLocale::WIDTH
        );

        $javascript = <<<EOH

            function xoopsCodeYoutube(id, enterFlashPhrase, enterFlashHeightPhrase, enterFlashWidthPhrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterFlashPhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var text2 = prompt(enterFlashWidthPhrase, "640");
                    var text3 = prompt(enterFlashHeightPhrase, "385");
                    var result = '[youtube url="' + text + '"';
                    if ( text2.length > 0) {
                        result += ' width="' + text2 + '" height="' + text3 + '"';
                    }
                    result += " /]";
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
            'youtube',
            function ($attributes, $content, $tagName) {
                if (array_key_exists(0, $attributes) && '=' === substr($attributes[0], 0, 1)) {
                    $args = ltrim($attributes[0], '=');
                    list($width, $height) = explode(',', $args);
                    $url = $content;
                } else {
                    $defaults = [
                        'url'    => trim($content),
                        'width'  => $this->config['width'],
                        'height' => $this->config['height'],
                    ];
                    $cleanAttributes = $this->shortcodes->shortcodeAttributes($defaults, $attributes);
                    $url = $cleanAttributes['url'];
                    $width = (int) $cleanAttributes['width'];
                    $height = (int) $cleanAttributes['height'];
                }

                // from: http://stackoverflow.com/questions/2936467/parse-youtube-video-id-using-preg-match/6382259#6382259
                $youtubeRegex = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)'
                    .'([^"&?/ ]{11})%i';

                if (preg_match($youtubeRegex, $url, $match)) {
                    $videoId = $match[1];
                } elseif (preg_match('%^[^"&?/ ]{11}$%', $url)) {
                    $videoId = $url;
                } else {
                    return ''; // giveup
                }

                switch ($width) {
                    case 4:
                        $height = 3;
                        break;
                    case 16:
                        $height = 9;
                        break;
                }

                $aspectRatio = $width/$height; // 16x9 = 1.777777778, 4x3 = 1.333333333
                $responsiveAspect = ($aspectRatio < 1.4) ? 'embed-responsive-4by3' : 'embed-responsive-16by9';
                if ($width < 17 && $height < 10) {
                    $scale = (int) 640 / $width;
                    $width = $width * $scale;
                    $height = $height * $scale;
                }

                $template = $this->config['template'];
                $newContent = sprintf($template, $videoId, $width, $height, $responsiveAspect);
                return $newContent;
            }
        );
    }
}

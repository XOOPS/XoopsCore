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
 * @author    iHackCode <https://github.com/ihackcode>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SoundCloud extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
        'params' => "color=ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false",
        'width'  => "100%",
        'height' => "166",
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
            'soundcloud.png',
            \XoopsLocale::SOUNDCLOUD,
            'xoopsCodeSoundCloud',
            \XoopsLocale::SOUNDCLOUD_URL
        );

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
                    xoopsInsertText(domobj, '[soundcloud url="'+text+'" /]');
                }
                domobj.focus();
            }
EOH;

        return [$buttonCode, $javascript];
    }

    /**
     * Register extension with the supplied sanitizer instance
     *
     * Soundcloud offers a wp shortcode version under share/embed that we can accept:
     * [soundcloud url="https://api.soundcloud.com/tracks/46363550"
     *  params="color=ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false"
     *  width="100%" height="166" iframe="true" /]
     *
     * For backward compatibility, we accept this form, too:
     * [soundcloud]https://api.soundcloud.com/tracks/46363550[/soundcloud]
     * But, the old URL form seems to no longer work correctly :(
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {
        $shortcodes = $this->shortcodes;
        $shortcodes->addShortcode(
            'soundcloud',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $defaults = [
                    'url'    => trim($content),
                    'params' => $this->config['params'],
                    'width'  => $this->config['width'],
                    'height' => $this->config['height'],
                ];
                $cleanAttributes = $shortcodes->shortcodeAttributes($defaults, $attributes);

                parse_str($defaults['params'], $defaultParams);
                parse_str($cleanAttributes['params'], $inputParams);
                $cleanParams = $shortcodes->shortcodeAttributes($defaultParams, $inputParams);
                $cleanAttributes = array_filter($cleanAttributes, 'urlencode');
                $cleanParams = array_filter($cleanParams, 'urlencode');

                $template = '<iframe width="%2$s" height="%3$s" scrolling="no" frameborder="no"'
                    .' src="https://w.soundcloud.com/player/?url=%1$s&amp;color=%4$s&amp;auto_play=%5$s'
                    . '&amp;hide_related=%6$s&amp;show_comments=%7$s&amp;show_user=%8$s&amp;'
                    . 'show_reposts=%9$s"></iframe>';

                $newContent = sprintf(
                    $template,
                    $cleanAttributes['url'],
                    $cleanAttributes['width'],
                    $cleanAttributes['height'],
                    $cleanParams['color'],
                    $cleanParams['auto_play'],
                    $cleanParams['hide_related'],
                    $cleanParams['show_comments'],
                    $cleanParams['show_user'],
                    $cleanParams['show_reposts']
                );
                return $newContent;
            }
        );
    }
}

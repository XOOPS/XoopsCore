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
class XoopsCode extends ExtensionAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = ['enabled' => true];

    /**
     * Register extension with the supplied sanitizer instance
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {
        $shortcodes = $this->shortcodes;

        $shortcodes->addShortcode(
            'siteurl',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $url = ltrim($attributes[0], '=');
                $url = \Xoops::getInstance()->url($url);
                $newcontent = '<a href="' .$url. '">' . $shortcodes->process($content) . '</a>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'url',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $url = ltrim($attributes[0], '=');
                $url = \Xoops::getInstance()->url($url);
                $newcontent = '<a href="' .$url. '">' . $shortcodes->process($content) . '</a>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'color',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $color = ltrim($attributes[0], '=');
                $color = preg_match('/^[a-f0-9]{3}$|^[a-f0-9]{6}$/i', $color) ? '#' . $color : $color;
                $newcontent = '<span style="color: ' .$color. '">' . $shortcodes->process($content) . '</span>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'size',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $size = ltrim($attributes[0], '=');
                $newcontent = '<span style="font-size: ' .$size. '">' . $shortcodes->process($content) . '</span>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'font',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $font = ltrim($attributes[0], '=');
                $newcontent = '<span style="font-family: ' .$font. '">' . $shortcodes->process($content) . '</span>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'email',
            function ($attributes, $content, $tagName) {
                $newcontent = '<a href="mailto:' . trim($content) . '</a>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'b',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<strong>' . $shortcodes->process($content) . '</strong>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'i',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<em>' . $shortcodes->process($content) . '</em>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'u',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<u>' . $shortcodes->process($content) . '</u>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'd',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<del>' . $shortcodes->process($content) . '</del>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'center',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<div style="text-align: center;">' . $shortcodes->process($content) . '</div>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'left',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<div style="text-align: left;">' . $shortcodes->process($content) . '</div>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'right',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<div style="text-align: right;">' . $shortcodes->process($content) . '</div>';
                return $newcontent;
            }
        );
    }
}

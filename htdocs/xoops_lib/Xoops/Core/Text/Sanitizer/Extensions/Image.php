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
 * @category  Sanitizer\Extensions
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Image extends ExtensionAbstract
{

    static $jsLoaded;

    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'clickable' => true,  // Click to open an image in a new window in full size
        'resize' => true,     // Resize the image down to max_width set below
        'max_width' => 300,   // Maximum width of an image displayed on page
        'allowimage' => true, // true to allow images, false to force links only
    ];

    /**
     * Register extension with the supplied sanitizer instance
     *
     * @return void
     */
    public function registerExtensionProcessing()
    {
        $ts = $this->ts;

        $config = $ts->getConfig('image'); // direct load to allow Sanitizer to change 'allowimage'

        $this->shortcodes->addShortcode(
            'img',
            function ($attributes, $content, $tagName) use ($config) {
                $xoops = \Xoops::getInstance();
                $defaults = [
                    'id' => 0,
                    'url' => trim($content),
                    'align' => '',
                    'width'  => $this->config['max_width'],
                ];
                $cleanAttributes = $this->shortcodes->shortcodeAttributes($defaults, $attributes);
                \Xmf\Debug::dump($cleanAttributes);
                if (0 !== $cleanAttributes['id']) {
                    $cleanAttributes['url'] = $xoops->url('/image.php?id=' . $cleanAttributes['id']);
                }
                $url = $cleanAttributes['url'];

                $cleanAttributes['align'] = $this->ts->cleanEnum(
                    $cleanAttributes['align'],
                    ['right', 'left', 'center'],
                    '',
                    true
                );
                $class = '';
                if ($cleanAttributes['align']!= '') {
                    $class = ' class="' . $cleanAttributes['align'] . '"';
                }
                $width = $cleanAttributes['width'];
                if (preg_match('/[0-9]{1}$/', $width)) {
                    $width .= 'px';
                }

                if (!$config['allowimage']) {
                    $template = '<a href="%1$s" rel="external">%2$s</a>';
                    $alt = $this->ts->htmlSpecialChars($url);
                } elseif ($config['resize'] && !$config['clickable']) {
                    $alt = $this->ts->htmlSpecialChars(\XoopsLocale::RESIZED_IMAGE);
                    $template = '<img src="%1$s" alt="%2$s"%3$s style="max-width: %4$s;" />';
                } elseif ($config['resize'] && $config['clickable']) {
                    if (!self::$jsLoaded) {
                        self::$jsLoaded = true;
                        $xoops->theme()->addScript(
                            '/class/textsanitizer/image/image.js',
                            ['type' => 'text/javascript']
                        );
                    }
                    $alt = $this->ts->htmlSpecialChars(\XoopsLocale::CLICK_TO_SEE_ORIGINAL_IMAGE_IN_NEW_WINDOW);
                    $template = '<a href="javascript:loadImage(\'%1$s\');"><img src="%1$s" alt="%2$s"%3$s style="max-width: %4$s;" />';
                } else{
                    $alt = $ts->htmlSpecialChars(\XoopsLocale::ORIGINAL_IMAGE);
                    $template = '<img src="%1$s" alt="%2$s"%3$s />';
                }
                $newContent = sprintf($template, $url, $alt, $class, $width);
                return $newContent;
            }
        );
/*
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 width=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img width=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        // case: no images allowed
        if (empty($config['allowimage'])) {
            $ts->replacements[] = '<a href="\\5" rel="external">\\5</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\1" rel="external">\\1</a>';
            // case: no images allowed, id image
            $ts->replacements[] = '<a href="' . $xoops_url . '/image.php?id=\\4" rel="external" title="\\5">\\5</a>';
            $ts->replacements[] = '<a href="' . $xoops_url . '/image.php?id=\\2" rel="external" title="\\3">\\3</a>';

        } else {
            // case: resize to max_width
            if (!empty($config['resize']) && empty($config['clickable']) && !empty($config['max_width']) && !empty($GLOBALS['xoTheme'])) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $xoops->theme()->addScript(
                        '/class/textsanitizer/image/image.js',
                        ['type' => 'text/javascript']
                    );
                }
                $resizedStr = $ts->htmlSpecialChars(\XoopsLocale::RESIZED_IMAGE);
                $ts->replacements[] = "<img src='\\5' class='\\2' alt='".$resizedStr . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' alt='".$resizedStr . "' border='0'" . ($config['resize'] ? "onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : "") . "/>";
                $ts->replacements[] = "<img src='\\3' alt='".$resizedStr . "' border='0' onload=\"JavaScript:if(this.width>\\2)this.width=\\2\" /><br />";
                $ts->replacements[] = "<img src='\\1' alt='".$resizedStr . "' border='0'" . ($config['resize'] ? " onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : "") . "/>";

            } elseif (!empty($config['clickable']) && !empty($config['max_width']) && !empty($GLOBALS['xoTheme'])) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $GLOBALS['xoTheme']->addScript('/class/textsanitizer/image/image.js', array(
                        'type' => 'text/javascript'));
                }
                $clickToOpenStr = $ts->htmlSpecialChars(\XoopsLocale::CLICK_TO_SEE_ORIGINAL_IMAGE_IN_NEW_WINDOW);
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\5\");'><img src='\\5' class='\\2' alt='".$clickToOpenStr . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" /></a>";
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\3\");'><img src='\\3' class='\\2' alt='".$clickToOpenStr . "' border='0' " . ($config['resize'] ? "onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : "") . "/></a>";
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\3\");'><img src='\\3' alt='".$clickToOpenStr . "' border='0' onload=\"JavaScript:if(this.width>\\2)this.width=\\2\" /></a><br />";
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\1\");'><img src='\\1' alt='".$clickToOpenStr . "' border='0' title='".$clickToOpenStr . "'" . ($config['resize'] ? " onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : "") . "/></a>";
            } else {
                $originalStr = $ts->htmlSpecialChars(\XoopsLocale::ORIGINAL_IMAGE);
                $ts->replacements[] = "<img src='\\5' class='\\2' border='0' alt='".$originalStr ."' onload=\"JavaScript:if(this.width>\\4) this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' border='0' alt='".$originalStr ."' " . ($config['resize'] ? "onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : "") . "/>";
                $ts->replacements[] = "<img src='\\3' border='0' alt='".$originalStr ."' onload=\"JavaScript:if(this.width>\\2) this.width=\\2\" />";
                $ts->replacements[] = "<img src='\\1' border='0' alt='".$originalStr ."' " . ($config['resize'] ? " onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : "") . "/>";
            }
            $ts->replacements[] = '<img src="' . $xoops_url . '/image.php?id=\\4" class="\\2" title="\\5" />';
            $ts->replacements[] = '<img src="' . $xoops_url . '/image.php?id=\\2" title="\\3" />';
        }
*/
    }
}

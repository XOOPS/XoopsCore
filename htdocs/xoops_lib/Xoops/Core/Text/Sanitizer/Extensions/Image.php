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

    protected static $jsLoaded;

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
        $config = $this->ts->getConfig('image'); // direct load to allow Sanitizer to change 'allowimage'

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
                            'media/xoops/image.js',
                            ['type' => 'text/javascript']
                        );
                    }
                    $alt = $this->ts->htmlSpecialChars(\XoopsLocale::CLICK_TO_SEE_ORIGINAL_IMAGE_IN_NEW_WINDOW);
                    $template = '<a href="javascript:loadImage(\'%1$s\');"><img src="%1$s" title="%2$s" alt="%2$s"%3$s style="max-width: %4$s;" /></a>';
                } else {
                    $alt = $this->ts->htmlSpecialChars(\XoopsLocale::ORIGINAL_IMAGE);
                    $template = '<img src="%1$s" alt="%2$s"%3$s />';
                }
                $newContent = sprintf($template, $url, $alt, $class, $width);
                return $newContent;
            }
        );
    }
}

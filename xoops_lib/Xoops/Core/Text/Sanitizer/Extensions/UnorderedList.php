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
 * @author    Wishcraft <simon@xoops.org>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class UnorderedList extends ExtensionAbstract
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
            'ul',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<ul>' . $shortcodes->process($content) . '</ul>';
                return $newcontent;
            }
        );

        $shortcodes->addShortcode(
            'li',
            function ($attributes, $content, $tagName) use ($shortcodes) {
                $newcontent = '<li>' . $shortcodes->process($content) . '</li>';
                return $newcontent;
            }
        );
    }
}

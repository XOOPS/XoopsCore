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
use Xoops\Core\Text\Sanitizer\FilterAbstract;

/**
 * TextSanitizer filter to Replace banned words in a string with their replacements
 * or terminate current request
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Quote extends FilterAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
    ];

    /**
     * Convert quote codes to blockquote tags
     *
     * This seems to work for well matched sets, but is actually crisscrossing nested tags
     *
     * @param string $text text to censor
     *
     * @return string
     */
    public function applyFilter($text)
    {
        if (!$this->config['enabled']) {
            return $text;
        }

        //look for both open and closing tags in the correct order
        $pattern = "/\[quote](.*)\[\/quote\]/sU";
        $replacement = \XoopsLocale::C_QUOTE . '<div class="xoopsQuote"><blockquote>\\1</blockquote></div>';

        $text = preg_replace($pattern, $replacement, $text, -1, $count);
        //no more matches, return now
        if (!$count) {
            return $text;
        }
        //new matches could have been created, keep doing it until we have no matches
        return $this->applyFilter($text);
    }
}

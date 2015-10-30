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
 * TextSanitizer filter - clean up HTML text
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Clickable extends FilterAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'truncate_length' => 60,
    ];

    /**
     * Make and URL's in the text clickable links
     *
     * @param string $text text string to filter
     *
     * @return mixed
     */
    public function applyFilter($text)
    {
        if (!$this->config['enabled']) {
            return $text;
        }

        $valid_chars = "a-z0-9\/\-_+=.~!%@?#&;:$\|";
        $end_chars   = "a-z0-9\/\-_+=~!%@?#&;:$\|";

        $pattern     = "/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([{$valid_chars}]+[{$end_chars}])/i";
        $text = preg_replace_callback(
            $pattern,
            function ($match) {
                return $match[1] . "<a href=\"$match[2]://$match[3]\" title=\"$match[2]://$match[3]\""
                . "rel=\"external\">$match[2]://".$this->truncate($match[3]).'</a>';
            },
            $text
        );

        $pattern     = "/(^|[^]_a-z0-9-=\"'\/:\.])www\.((([a-zA-Z0-9\-]*\.){1,}){1}([a-zA-Z]{2,6}){1})((\/([a-zA-Z0-9\-\._\?\,\'\/\\+&%\$#\=~])*)*)/i";
        $text = preg_replace_callback(
            $pattern,
            function ($match) {
                return $match[1] ."<a href=\"http://www.$match[2]$match[6]\" "
                . "title=\"www.$match[2]$match[6]\" rel=\"external\">" .
                $this->truncate('www.'.$match[2].$match[6]) .'</a>';
            },
            $text
        );

        $pattern     = "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([{$valid_chars}]+[{$end_chars}])/i";
        $text = preg_replace_callback(
            $pattern,
            function ($match) {
                return $match[1]."<a href=\"ftp://ftp.$match[2].$match[3]\" "
                . "title=\"ftp.$match[2].$match[3]\" rel=\"external\">"
                . $this->truncate('ftp.'.$match[2].$match[3]) .'</a>';
            },
            $text
        );

        $pattern     = "/(^|[^]_a-z0-9-=\"'\/:\.])([-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+)@((?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?)/i";
        $text = preg_replace_callback(
            $pattern,
            function ($match) {
                return $match[1]. "<a href=\"mailto:$match[2]@$match[3]\" title=\"$match[2]@$match[3]\">"
                . $this->truncate($match[2] . "@" . $match[3]) . '</a>';
            },
            $text
        );


        return $text;
    }

    /**
     * truncate string in context of
     *
     * @param string $text string to be truncated
     *
     * @return string
     */
    protected function truncate($text)
    {
        $config = $this->config;
        if (empty($text) || empty($config['truncate_length']) || mb_strlen($text) < $config['truncate_length']) {
            return $text;
        }
        $len = (((mb_strlen($text) - $config['truncate_length']) - 5) / 2);
        if ($len < 5) {
            $ret = mb_substr($text, 0, $len) . ' ... ' . mb_substr($text, -$len);
        } else {
            $ret = mb_substr($text, 0, $config['truncate_length']);
        }
        return $ret;
    }
}

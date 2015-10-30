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
 * TextSanitizer filter - clean XSS in HTML text
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Xss extends FilterAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'htmlawed_config' => ['safe' => 1],
        'htmlawed_spec' => [],
    ];

    /**
     * filter possible XSS
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

        /*
        $patterns = array();
        $replacements = array();
        $text = str_replace("\x00", "", $text);
        $c = "[\x01-\x1f]*";
        $patterns[] = "/\bj{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}[\s]*:/si";
        $replacements[] = "javascript;";
        $patterns[] = "/\ba{$c}b{$c}o{$c}u{$c}t{$c}[\s]*:/si";
        $replacements[] = "about;";
        $patterns[] = "/\bx{$c}s{$c}s{$c}[\s]*:/si";
        $replacements[] = "xss;";
        $text = preg_replace($patterns, $replacements, $text);
        */
        $text = \htmLawed::hl($text, $this->config['htmlawed_config'], $this->config['htmlawed_spec']);

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

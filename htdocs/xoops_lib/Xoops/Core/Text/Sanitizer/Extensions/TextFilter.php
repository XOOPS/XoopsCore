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
class TextFilter extends FilterAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
        'tags' => array(),      // Tags to be filtered out
        'patterns' => [         // patterns to be used for filtering
            'search' => '',
            'replace' => '',
        ],
    ];

    /**
     * Apply HTML cleanup to text
     *
     * @param string $text  text to filter
     * @param bool   $force true to force filtering even if user is an admin
     *
     * @return mixed
     */
    public function applyFilter($text, $force = true)
    {
        $xoops = \Xoops::getInstance();
        if (!$force && $xoops->userIsAdmin) {
            return $text;
        }

        if (class_exists('\HTMLPurifier')) {
            $config = \HTMLPurifier_Config::createDefault();
            $purifier = new \HTMLPurifier($config);
            $text = $purifier->purify($text);
            return $text;
        }

        $tags = array();
        $search = array();
        $replace = array();
        $config = $this->config;
        if (!empty($config["patterns"])) {
            foreach ($config["patterns"] as $pattern) {
                if (empty($pattern['search'])) {
                    continue;
                }
                $search[] = $pattern['search'];
                $replace[] = $pattern['replace'];
            }
        }
        if (!empty($config["tags"])) {
            $tags = array_map("trim", $config["tags"]);
        }

        // Set embedded tags
        $tags[] = "SCRIPT";
        $tags[] = "VBSCRIPT";
        $tags[] = "JAVASCRIPT";
        foreach ($tags as $tag) {
            $search[] = "/<" . $tag . "[^>]*?>.*?<\/" . $tag . ">/si";
            $replace[] = " [!" . strtoupper($tag) . " FILTERED!] ";
        }
        // Set meta refresh tag
        $search[] = "/<META[^>\/]*HTTP-EQUIV=(['\"])?REFRESH(\\1)[^>\/]*?\/>/si";
        $replace[] = "";
        // Sanitizing scripts in IMG tag
        //$search[]= "/(<IMG[\s]+[^>\/]*SOURCE=)(['\"])?(.*)(\\2)([^>\/]*?\/>)/si";
        //$replace[]="";
        // Set iframe tag
        $search[] = "/<IFRAME[^>\/]*SRC=(['\"])?([^>\/]*)(\\1)[^>\/]*?\/>/si";
        $replace[] = " [!IFRAME FILTERED! \\2] ";
        $search[] = "/<IFRAME[^>]*?>([^<]*)<\/IFRAME>/si";
        $replace[] = " [!IFRAME FILTERED! \\1] ";
        // action
        $text = preg_replace($search, $replace, $text);
        return $text;
    }
}

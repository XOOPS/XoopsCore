<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * TextSanitizer extension
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MytsTextfilter extends MyTextSanitizerExtension
{
    /**
     * @param MyTextSanitizer $ts
     * @param string $text
     * @param bool $force
     * @return mixed
     */
    public function load(MyTextSanitizer &$ts, $text, $force = false)
    {
        $xoops = Xoops::getInstance();
        if (empty($force) && $xoops->userIsAdmin) {
            return $text;
        }
        // Built-in fitlers for XSS scripts
        // To be improved
        $text = $ts->filterXss($text);

        if (XoopsLoad::load("purifier", "framework")) {
            $text = XoopsPurifier::purify($text);
            return $text;
        }

        $tags = array();
        $search = array();
        $replace = array();
        $config = parent::loadConfig(dirname(__FILE__));
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
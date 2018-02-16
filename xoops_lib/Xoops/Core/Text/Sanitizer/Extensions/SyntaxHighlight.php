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
 * TextSanitizer filter
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SyntaxHighlight extends FilterAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => false,
        'highlighter' => 'php', // Source code highlight: '' - disable; 'php' - php highlight; 'geshi' - geshi highlight
        'language' => 'PHP'
    ];

    /**
     * apply syntax highlighting to a text string
     *
     * @param string $source   source code text to highlight
     * @param string $language of source code
     *
     * @return bool|mixed|string
     */
    public function applyFilter($source, $language = 'php')
    {
        $config = $this->config;
        if (empty($config['highlighter'])) {
            return "<pre>{$source}</pre>";
        }
        $source = $this->ts->undoHtmlSpecialChars($source);
        $source = stripslashes($source);
        if ($config['highlighter'] === 'geshi') {
            $language = str_replace('=', '', $language);
            $language = ($language) ? $language : $config['language'];
            $language = strtolower($language);
            if ($source2 = SyntaxHighlight::geshi($source, $language)) {
                return $source2;
            }
        }
        $source = SyntaxHighlight::php($source);
        return $source;
    }

    /**
     * apply PHP highlight_string
     *
     * @param string $text source string
     *
     * @return string
     */
    public function php($text)
    {
        $text = trim($text);
        $addedOpenTag = false;
        if (!strpos($text, "<?php") && (substr($text, 0, 5) !== "<?php")) {
            $text = "<?php " . $text;
            $addedOpenTag = true;
        }

        $oldlevel = error_reporting(0);

        //There is a bug in the highlight function(php < 5.3) that it doesn't render
        //backslashes properly like in \s. So here we replace any backslashes
        $text = str_replace("\\", "XxxX", $text);

        $buffer = highlight_string($text, true); // Require PHP 4.20+

        //Placing backspaces back again
        $buffer = str_replace("XxxX", "\\", $buffer);

        error_reporting($oldlevel);
        $pos_open = $pos_close = 0;
        if ($addedOpenTag) {
            $pos_open = strpos($buffer, '&lt;?php&nbsp;');
        }

        $str_open = ($addedOpenTag) ? substr($buffer, 0, $pos_open) : "";

        $length_open = ($addedOpenTag) ? $pos_open + 14 : 0;
        $str_internal = substr($buffer, $length_open);

        $buffer = $str_open . $str_internal;
        return $buffer;
    }

    /**
     * apply geshi highlighting
     *
     * @param string $source   source code text to highlight
     * @param string $language of source code
     *
     * @return bool
     */
    public function geshi($source, $language)
    {
        if (!@\XoopsLoad::load("geshi", "framework")) {
            return false;
        }

        // Create the new XoopsGeshi object, passing relevant stuff
        // XoopsGeshi should be extending geSHi in Frameworks/geshi/xoopsgeshi.php
        $geshi = new XoopsGeshi($source, $language);

        // Enclose the code in a <div>
        $geshi->set_header_type(GESHI_HEADER_NONE);

        // Sets the proper encoding charset other than "ISO-8859-1"
        $geshi->set_encoding(\XoopsLocale::getCharset());

        $geshi->set_link_target("_blank");

        // Parse the code
        $code = $geshi->parse_code();

        return $code;
    }
}

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

/**
 * Replaces banned words in a string with their replacements or terminate current request
 *
 * @param   string $text
 * @return  string
 *
 */
class MytsCensor extends MyTextSanitizerExtension
{
    /**
     * @param MyTextSanitizer $ts
     * @param $text
     * @return mixed|string
     */
    public function load(MyTextSanitizer &$ts, $text)
    {
        static $censorConf;

        $xoops = Xoops::getInstance();
        if (!isset($censorConf)) {
            $censorConf = $xoops->getConfigs();
            $config = parent::loadConfig(dirname(__FILE__));
            //merge and allow config override
            $censorConf = array_merge($censorConf, $config);
        }

        if (empty($censorConf['censor_enable'])) {
            return $text;
        }

        if (empty($censorConf['censor_words'])) {
            return $text;
        }

        if (empty($censorConf['censor_admin']) && $xoops->userIsAdmin) {
            return $text;
        }

        $replacement = $censorConf['censor_replace'];
        foreach ($censorConf['censor_words'] as $bad) {
            $bad = trim($bad);
            if (!empty($bad)) {
                if (false === strpos($text, $bad)) {
                    continue;
                }
                if (!empty($censorConf['censor_terminate'])) {
                    trigger_error("Censor words found", E_USER_ERROR);
                    $text = '';
                    return $text;
                }
                $patterns[] = "/(^|[^0-9a-z_]){$bad}([^0-9a-z_]|$)/siU";
                $replacements[] = "\\1{$replacement}\\2";
                $text = preg_replace($patterns, $replacements, $text);
            }
        }
        return $text;
    }
}
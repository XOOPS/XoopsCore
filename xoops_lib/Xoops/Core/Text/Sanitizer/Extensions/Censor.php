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
class Censor extends FilterAbstract
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [
        'enabled' => true,
        'censor_terminate' => false, //set to true if you want to trigger an error page
        'censor_admin' => true,      //set to false if you don't want to censor admin entries
        'censor_words' => ['shit', 'piss', 'fuck', 'cunt', 'cocksucker', 'motherfucker', 'tits'],
        'censor_replace' => '%#$@!',
    ];

    /**
     * Censor text string according to
     *
     * @param string $text text to censor
     *
     * @return string
     */
    public function applyFilter($text)
    {
        $xoops = \Xoops::getInstance();

        $enabled = (bool) $xoops->getConfig('censor_enable');

        $censorWords = (array) $xoops->getConfig('censor_words');
        $censorWords = empty($censorWords) ? $this->config['censor_words'] : $censorWords;

        $censorReplace = $xoops->getConfig('censor_replace');
        $censorReplace = empty($censorReplace) ? $this->config['censor_replace'] : $censorReplace;

        if ($enabled === false
            || empty($censorWords)
            || ((false === $this->config['censor_admin']) && $xoops->userIsAdmin)
        ) {
            return $text;
        }

        $patterns = [];
        $replacements = [];

        foreach ($censorWords as $bad) {
            $bad = trim($bad);
            if (!empty($bad)) {
                if (false === stripos($text, $bad)) {
                    continue;
                }
                if ((bool) $this->config['censor_terminate']) {
                    trigger_error("Censor words found", E_USER_ERROR);
                    return '';
                }
                $patterns[] = "/(^|[^0-9a-z_]){$bad}([^0-9a-z_]|$)/siU";
                $replacements[] = "\\1{$censorReplace}\\2";
            }
        }

        $text = preg_replace($patterns, $replacements, $text);

        return $text;
    }
}

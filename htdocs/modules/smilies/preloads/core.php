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
 * Smilies core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
class SmiliesCorePreload extends Xoops\Core\PreloadItem
{
    static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap(array(
            'smilies' => $path . '/class/helper.php',
        ));
    }

    static function eventCoreClassXoopsformFormdhtmltextareaCodeicon($args)
    {
        /* @var $dhtml Xoops\Form\DhtmlTextArea */
        $dhtml = $args[1];
        $args[0] .= "<img src='" . XOOPS_URL . "/images/smiley.gif' alt='" . XoopsLocale::SMILIES . "' title='" . XoopsLocale::SMILIES . "' onclick='openWithSelfMain(\"" . XOOPS_URL . "/modules/smilies/popup.php?target={$dhtml->getName()}\",\"smilies\",300,650);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
    }

    static function eventCoreClassModuleTextsanitizerSmiley($args)
    {
        $smileys = MyTextSanitizer::getInstance()->getSmileys();
        $message =& $args[0];
        foreach ($smileys as $smile) {
            $message = str_replace($smile['smiley_code'], '<img class="imgsmile" src="' . XOOPS_UPLOAD_URL . '/' . htmlspecialchars($smile['smiley_url']) . '" alt="' . $smile['smiley_emotion'] . '" />', $message);
        }
    }

    static function eventCoreClassModuleTextsanitizerGetSmileys($args)
    {
        $isAll = $args[0];
        $smileys =& $args[1];
        $myts =& $args[2];

        if (count($myts->smileys) == 0) {
            $myts->smileys = Smilies::getInstance()->getHandlerSmilies()->getSmilies(0, 0, false);
        }
        if ($isAll) {
            $smileys = $myts->smileys;
            return true;
        }

        foreach ($myts->smileys as $smile) {
            if (empty($smile['smiley_display'])) {
                continue;
            }
            $smileys[] = $smile;
        }
        return true;
    }
}

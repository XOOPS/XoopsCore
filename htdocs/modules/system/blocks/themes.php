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
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_themes_show($options)
{
    $xoops = Xoops::getInstance();
    $theme_options = '';
    $theme_set_allowed = $xoops->getConfig('theme_set_allowed');
    foreach ($theme_set_allowed as $theme) {
        $theme_options .= '<option value="' . $theme . '"';
        if ($theme == $xoops->getConfig('theme_set')) {
            $theme_options .= ' selected="selected"';
        }
        $theme_options .= '>' . $theme . '</option>';
    }
    $block = array();
    if ($options[0] == 1) {
        $block['theme_select'] = "<img vspace=\"2\" id=\"xoops_theme_img\" src=\"" . XOOPS_THEME_URL . "/" . $xoops->getConfig('theme_set') . "/screenshot.png\" alt=\"screenshot\" width=\"" . intval($options[1]) . "\" /><br /><select class=\"span2\" id=\"xoops_theme_select\" name=\"xoops_theme_select\" onchange=\"showImgSelected('xoops_theme_img', 'xoops_theme_select', 'themes', '/screenshot.png', '" . XOOPS_URL . "');\">" . $theme_options . "</select><br /><input type=\"submit\" value=\"" . XoopsLocale::A_GO . "\" />";
    } else {
        $block['theme_select'] = '<select class="span2" name="xoops_theme_select" onchange="submit();" size="3">' . $theme_options . '</select>';
    }

    $block['theme_select'] .= '<br />(' . sprintf(SystemLocale::F_THEMES, '<strong>' . count($theme_set_allowed) . '</strong>') . ')<br />';
    return $block;
}

function b_system_themes_edit($options)
{
    $block_form = new Xoops\Form\BlockForm();
    $block_form->addElement(new Xoops\Form\RadioYesNo(SystemLocale::DISPLAY_SCREENSHOT_IMAGE, 'options[0]', $options[0]));
    $block_form->addElement( new Xoops\Form\Text(SystemLocale::SCREENSHOT_IMAGE_WIDTH, 'options[1]', 1, 3, $options[1]), true);
    return $block_form->render();
}

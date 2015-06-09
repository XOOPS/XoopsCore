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
 * banners module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */
function banners_blocks_show($options) {
    $block = array();
    $xoops = Xoops::getInstance();
    require_once dirname(__DIR__) . '/class/bannerrender.php';
    $render = new BannerRender();
    switch ($options[0]) {
        case 'random':
            $nb_display = $options[1];
            $align = $options[2];
            array_shift($options);
            array_shift($options);
            array_shift($options);
            $client = $options;
            $block['banners'] = $render->displayBanner($nb_display, $align, $client);
            break;

        case 'id':
            $ids = $options[1];
            $align = $options[2];
            $block['banners'] = $render->displayBanner(1, $align, 0, $ids);
            break;
    }
    return $block;
}

function banners_blocks_edit($options) {
    $form = '';
    switch ($options[0]) {

        case 'random':
            $form .= _MB_BANNERS_DISP . "&nbsp;\n";
            $form .= "<input type=\"hidden\" name=\"options[0]\" value=\"" . $options[0] . "\" />\n";
            $form .= "<input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"" . $options[1] . "\" type=\"text\" />&nbsp;" . _MB_BANNERS . "<br />\n";
            switch ($options[2]){
                case 'H':
                    $checked_H = 'checked="checked"';
                    $checked_V = '';
                    break;

                case 'V':
                    $checked_H = '';
                    $checked_V = 'checked="checked"';
                    break;
            }
            $form .= _MB_BANNERS_ALIGNEMENT . " : <input name=\"options[2]\" value=\"H\" type=\"radio\" " . $checked_H . "/>" . _MB_BANNERS_ALIGNEMENT_H . "&nbsp;\n";
            $form .= "<input name=\"options[2]\" value=\"V\" type=\"radio\" " . $checked_V . "/>" . _MB_BANNERS_ALIGNEMENT_V . "<br />\n";
            array_shift($options);
            array_shift($options);
            array_shift($options);
            $form .= _MB_BANNERS_CLIENTSTODISPLAY . "<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">\n";
            $xoops = Xoops::getInstance();
            $client_Handler = $xoops->getModuleHandler('bannerclient','banners');
            $criteria = new CriteriaCompo();
            $criteria->setSort('bannerclient_name');
            $criteria->setOrder('ASC');
            $client_arr = $client_Handler->getAll($criteria);
            $form .= "<option value=\"0\" " . (array_search(0, $options) === false ? '' : 'selected="selected"') . ">" . _MB_BANNERS_ALLCLIENTS . "</option>\n";
            foreach (array_keys($client_arr) as $i) {
                $form .= "<option value=\"" . $client_arr[$i]->getVar('cid') . "\" " . (array_search($client_arr[$i]->getVar('cid'), $options) === false ? '' : 'selected="selected"') . ">" . $client_arr[$i]->getVar('name')."</option>\n";
            }
            $form .= "</select>\n";
            break;

        case 'id':
            $form .= _MB_BANNERS_IDDISPLAY . "&nbsp;\n";
            $form .= "<input type=\"hidden\" name=\"options[0]\" value=\"" . $options[0] . "\" />\n";
            $form .= "<input name=\"options[1]\" size=\"20\" maxlength=\"255\" value=\"" . $options[1] . "\" type=\"text\" />&nbsp;" . _MB_BANNERS_SEP . "<br />\n";
            switch ($options[2]){
                case 'H':
                    $checked_H = 'checked="checked"';
                    $checked_V = '';
                    break;

                case 'V':
                    $checked_H = '';
                    $checked_V = 'checked="checked"';
                    break;
            }
            $form .= _MB_BANNERS_ALIGNEMENT . " : <input name=\"options[2]\" value=\"H\" type=\"radio\" " . $checked_H . "/>" . _MB_BANNERS_ALIGNEMENT_H . "&nbsp;\n";
            $form .= "<input name=\"options[2]\" value=\"V\" type=\"radio\" " . $checked_V . "/>" . _MB_BANNERS_ALIGNEMENT_V . "<br />\n";
            break;
    }
    return $form;
}

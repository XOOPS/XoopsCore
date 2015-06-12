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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

class PagePage_relatedForm extends Xoops\Form\ThemeForm
{
    /**
     * @param PagePage_related|XoopsObject $obj
     */
    public function __construct(PagePage_related &$obj)
    {
        $helper = Page::getInstance();
        $xoops = $helper->xoops();

        $xoops->theme()->addScript('modules/page/media/jquery/multi-select.0.3-7/js/jquery.multi-select.js');
        $xoops->theme()->addScript('modules/page/media/jquery/multi-select-init.js');
        $xoops->theme()->addStylesheet('modules/page/media/jquery/multi-select.0.3-7/css/multi-select.css');

        // Get handler
        $content_Handler = $helper->getContentHandler();
        $link_Handler = $helper->getLinkHandler();

        $title = $obj->isNew() ? PageLocale::A_ADD_RELATED : PageLocale::A_EDIT_RELATED;

        parent::__construct($title, 'form', 'related.php', 'post', true);

        //name
        $this->addElement(new Xoops\Form\Text(PageLocale::RELATED_NAME, 'related_name', 4, 255, $obj->getVar('related_name'), ''), true);
        //menu
        $menu = new Xoops\Form\RadioYesNo(PageLocale::RELATED_MENU, 'related_domenu', $obj->getVar('related_domenu'));
        $menu->setDescription(PageLocale::RELATED_MENU_DSC);
        $this->addElement($menu, false);
        //navigation
        $navigation = new Xoops\Form\Select(PageLocale::RELATED_NAVIGATION, 'related_navigation', $obj->getVar('related_navigation'), 1, false);
        $navigation->addOption(1, PageLocale::L_RELATED_NAVIGATION_OPTION1);
        $navigation->addOption(2, PageLocale::L_RELATED_NAVIGATION_OPTION2);
        $navigation->addOption(3, PageLocale::L_RELATED_NAVIGATION_OPTION3);
        $navigation->addOption(4, PageLocale::L_RELATED_NAVIGATION_OPTION4);
        $navigation->addOption(5, PageLocale::L_RELATED_NAVIGATION_OPTION5);
        $navigation->setClass('span3');
        $this->addElement($navigation);

        $related_links = $link_Handler->getContentByRelated($obj->getVar('related_id'));
        $contents_used = $link_Handler->getContentUsed();
        $contents = $content_Handler->getPageTitle(1);

        $related_links_form = new Xoops\Form\Select(PageLocale::RELATED_MAIN, 'datas', $related_links, $size = 20, $multiple = true);
        foreach ($contents as $k => $content) {
            if (!in_array($content['content_id'], $contents_used) || in_array($content['content_id'], $related_links)) {
                $related_links_form->addOption($content['content_id'], $content['content_title']);
            }
        }
        $this->addElement($related_links_form, true);

        $this->addElement(new Xoops\Form\Hidden('related_id', $obj->getVar('related_id')));

        /**
         * Buttons
         */
        $button_tray = new Xoops\Form\ElementTray('', '');
        $button_tray->addElement(new Xoops\Form\Hidden('op', 'save'));

        $button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $button->setClass('btn btn-success');
        $button_tray->addElement($button);

        $button_2 = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
        $button_2->setClass('btn btn-warning');
        $button_tray->addElement($button_2);

        $button_3 = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
        $button_3->setExtra("onclick='javascript:history.go(-1);'");
        $button_3->setClass('btn btn-danger');
        $button_tray->addElement($button_3);

        $this->addElement($button_tray);
    }
}

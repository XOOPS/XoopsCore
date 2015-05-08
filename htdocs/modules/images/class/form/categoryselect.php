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
 * images module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @version         $Id$
 */

class ImagesCategoryselectForm extends Xoops\Form\ThemeForm
{
    /**
     * @param category id $imgcat_id
     */
    public function __construct($imgcat_id)
    {
        $xoops = Xoops::getInstance();
        $groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;

        $helper = Xoops\Module\Helper::getHelper('images');
        $categories = $helper->getHandlerCategories()->getListByPermission($groups, 'imgcat_read');

        parent::__construct('', 'category_select', $xoops->getEnv('PHP_SELF'), 'post');
        $select = new Xoops\Form\Select('', 'imgcat_id', $imgcat_id);
        $select->addOption(0, _AM_IMAGES_CAT_SELECT);
        $select->addOptionArray($categories);
        $select->setExtra("onchange='javascript:window.location.href=\"images.php?imgcat_id=\" + this.value'");
        $this->addElement($select);
    }
}

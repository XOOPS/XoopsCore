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
 * Ranks Form Class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @package         system
 * @subpackage      userrank
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class UserrankRanksForm extends Xoops\Form\ThemeForm
{
    /**
     * @param XoopsRank|XoopsObject $obj
     */
    public function __construct(XoopsObject $obj)
    {
        if ($obj->isNew()) {
            $blank_img = 'blank.gif';
        } else {
            $blank_img = str_replace('ranks/', '', $obj->getVar('rank_image', 'e'));
        }

        $title = $obj->isNew() ? sprintf(_AM_USERRANK_ADD) : sprintf(_AM_USERRANK_EDIT);

        parent::__construct($title, 'form', 'userrank.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $this->addElement(new Xoops\Form\Text(_AM_USERRANK_TITLE, 'rank_title', 4, 50, $obj->getVar('rank_title')), true);
        $min = new Xoops\Form\Text(_AM_USERRANK_MINPOST, 'rank_min', 1, 10, $obj->getVar('rank_min'));
        $min->setPattern('^\d+$', _AM_USERRANK_ERROR_POST);
        $this->addElement($min);
        $max = new Xoops\Form\Text(_AM_USERRANK_MAXPOST, 'rank_max', 1, 10, $obj->getVar('rank_max'));
        $max->setPattern('^\d+$', _AM_USERRANK_ERROR_POST);
        $this->addElement($max);

        $imgtray_img = new Xoops\Form\ElementTray(_AM_USERRANK_IMAGE, '<br />');
        $imgpath_img = sprintf(_AM_USERRANK_IMAGE_PATH, XOOPS_UPLOAD_PATH . '/ranks/');
        $imageselect_img = new Xoops\Form\Select($imgpath_img, 'rank_image', $blank_img);
        $image_array_img = XoopsLists::getImgListAsArray(XOOPS_UPLOAD_PATH . '/ranks');
        $imageselect_img->addOption("$blank_img", $blank_img);
        foreach ($image_array_img as $image_img) {
            $imageselect_img->addOption("$image_img", $image_img);
        }
        $imageselect_img->setExtra("onchange='showImgSelected(\"xo-ranks-img\", \"rank_image\", \"ranks\", \"\", \"" . XOOPS_UPLOAD_URL . "\")'");
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(new Xoops\Form\Label('', "<br /><img src='" . XOOPS_UPLOAD_URL . "/ranks/" . $blank_img . "' name='image_img' id='xo-ranks-img' alt='' />"));

        $fileseltray_img = new Xoops\Form\ElementTray('<br />', '<br /><br />');
        $fileseltray_img->addElement(new Xoops\Form\File(_AM_USERRANK_UPLOAD, 'rank_image'), false);
        $fileseltray_img->addElement(new Xoops\Form\Label(''), false);
        $imgtray_img->addElement($fileseltray_img);
        $this->addElement($imgtray_img);

        if (!$obj->isNew()) {
            $rank_special = $obj->getVar('rank_special');
        } else {
            $rank_special = 0;
        }

        $special_tray = new Xoops\Form\ElementTray(_AM_USERRANK_SPECIAL, '<br />');
        $special_tray->setDescription(_AM_USERRANK_SPECIAL_CAN);
        $special_tray->addElement(new Xoops\Form\RadioYesNo('', 'rank_special', $rank_special));
        $this->addElement($special_tray);
        if (!$obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden('rank_id', $obj->getVar('rank_id')));
        }
        $this->addElement(new Xoops\Form\Hidden('op', 'userrank_save'));
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}

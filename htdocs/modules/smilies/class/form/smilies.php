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
 * smilies module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 */

class SmiliesSmiliesForm extends Xoops\Form\ThemeForm
{
    /**
     * @param SmiliesSmiley|XoopsObject $obj
     */
    public function __construct(SmiliesSmiley $obj)
    {
        $xoops = Xoops::getInstance();
        if ($obj->isNew()) {
            $blank_img = 'blank.gif';
        } else {
            $blank_img = str_replace('smilies/', '', $obj->getVar('smiley_url', 'e'));
        }

        $title = $obj->isNew() ? sprintf(_AM_SMILIES_ADD) : sprintf(_AM_SMILIES_EDIT);

        parent::__construct($title, 'form', $xoops->getEnv('PHP_SELF'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        $this->addElement(new Xoops\Form\Text(_AM_SMILIES_CODE, 'smiley_code', 2, 25, $obj->getVar('smiley_code')), true);
        $this->addElement(new Xoops\Form\Text(_AM_SMILIES_DESCRIPTION, 'smiley_emotion', 4, 50, $obj->getVar('smiley_emotion')), true);

        $imgtray_img = new Xoops\Form\ElementTray(_AM_SMILIES_FILE, '<br />');
        $imgpath_img = sprintf(_AM_SMILIES_IMAGE_PATH, \XoopsBaseConfig::get('uploads-url') . '/smilies/');
        $imageselect_img = new Xoops\Form\Select($imgpath_img, 'smiley_url', $blank_img);
        $image_array_img = XoopsLists::getImgListAsArray(\XoopsBaseConfig::get('uploads-url') . '/smilies');
        $imageselect_img->addOptionArray($image_array_img);

        $imageselect_img->setExtra('onchange="showImgSelected(\'xo-smilies-img\', \'smiley_url\', \'smilies\', \'\', \'' . \XoopsBaseConfig::get('uploads-url') . '\' )"');
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(new Xoops\Form\Label('', "<br /><img src='" . \XoopsBaseConfig::get('uploads-url') . "/smilies/" . $blank_img . "' name='image_img' id='xo-smilies-img' alt=''>"));

        $fileseltray_img = new Xoops\Form\ElementTray('<br />', '<br /><br />');
        $fileseltray_img->addElement(new Xoops\Form\File(_AM_SMILIES_UPLOADS, 'smiley_url'), false);
        $fileseltray_img->addElement(new Xoops\Form\Label(''), false);
        $imgtray_img->addElement($fileseltray_img);
        $this->addElement($imgtray_img);

        $this->addElement(new Xoops\Form\RadioYesNo(_AM_SMILIES_OFF, 'smiley_display', $obj->getVar('smiley_display')));

        $this->addElement(new Xoops\Form\Hidden('smiley_id', $obj->getVar('smiley_id')));

        /**
         * Buttons
         */
        $buttonTray = new Xoops\Form\ElementTray('', '');
        $buttonTray->addElement(new Xoops\Form\Hidden('op', 'save'));

        $buttonSubmit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $buttonSubmit->setClass('btn btn-success');
        $buttonTray->addElement($buttonSubmit);

        $buttonReset = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
        $buttonReset->setClass('btn btn-warning');
        $buttonTray->addElement($buttonReset);

        switch (basename($xoops->getEnv('PHP_SELF'), '.php')) {
            case 'xoops_smilies':
                $buttonClose = new Xoops\Form\Button('', 'button', XoopsLocale::A_CLOSE, 'button');
                $buttonClose->setExtra('onclick="tinyMCEPopup.close();"');
                $buttonClose->setClass('btn btn-danger');
                $buttonTray->addElement($buttonClose);
                break;

            case 'smilies':
            default:
                $buttonCancel = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
                $buttonCancel->setExtra("onclick='javascript:history.go(-1);'");
                $buttonCancel->setClass('btn btn-danger');
                $buttonTray->addElement($buttonCancel);
                break;
        }

        $this->addElement($buttonTray);
    }
}

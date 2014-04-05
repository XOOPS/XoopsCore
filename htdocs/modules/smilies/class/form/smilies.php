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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class SmiliesSmiliesForm extends XoopsThemeForm
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
            $blank_img = str_replace( 'smilies/', '', $obj->getVar('smiley_url', 'e') );
        }

        $title = $obj->isNew() ? sprintf(_AM_SMILIES_ADD) : sprintf(_AM_SMILIES_EDIT);

        parent::__construct($title, 'form', $xoops->getEnv('PHP_SELF'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        $this->addElement(new XoopsFormText(_AM_SMILIES_CODE, 'smiley_code', 2, 25, $obj->getVar('smiley_code'), '', '^Code(.*)$', 'Code', true), true);
        $this->addElement(new XoopsFormText(_AM_SMILIES_DESCRIPTION, 'smiley_emotion', 4, 50, $obj->getVar('smiley_emotion'), '', '', '', true), true);

        $imgtray_img = new XoopsFormElementTray( _AM_SMILIES_FILE, '<br />' );
        $imgpath_img = sprintf( _AM_SMILIES_IMAGE_PATH, XOOPS_UPLOAD_PATH . '/smilies/' );
        $imageselect_img = new XoopsFormSelect( $imgpath_img, 'smiley_url', $blank_img );
        $image_array_img = XoopsLists::getImgListAsArray( XOOPS_UPLOAD_PATH . '/smilies' );
        $imageselect_img->addOptionArray($image_array_img);

        $imageselect_img->setExtra( 'onchange="showImgSelected(\'xo-smilies-img\', \'smiley_url\', \'smilies\', \'\', \'' . XOOPS_UPLOAD_URL . '\' )"' );
        $imgtray_img->addElement( $imageselect_img, false);
        $imgtray_img->addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_UPLOAD_URL . "/smilies/" . $blank_img . "' name='image_img' id='xo-smilies-img' alt=''>" ) );

        $fileseltray_img = new XoopsFormElementTray('<br />','<br /><br />');
        $fileseltray_img->addElement(new XoopsFormFile(_AM_SMILIES_UPLOADS , 'smiley_url', 500000),false);
        $fileseltray_img->addElement(new XoopsFormLabel(''), false);
        $imgtray_img->addElement($fileseltray_img);
        $this->addElement($imgtray_img);

        $this->addElement(new XoopsFormRadioYN(_AM_SMILIES_OFF, 'smiley_display', $obj->getVar('smiley_display')));

        $this->addElement(new XoopsFormHidden('smiley_id', $obj->getVar('smiley_id')));

        /**
         * Buttons
         */
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));

        $button = new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $button->setClass('btn btn-success');
        $button_tray->addElement($button);

        $button_2 = new XoopsFormButton('', 'reset', XoopsLocale::A_RESET, 'reset');
        $button_2->setClass('btn btn-warning');
        $button_tray->addElement($button_2);

        switch (basename($xoops->getEnv('PHP_SELF'), '.php')) {
            case 'xoops_smilies':
            $button_3 = new XoopsFormButton('', 'button', XoopsLocale::A_CLOSE, 'button');
            $button_3->setExtra('onclick="tinyMCEPopup.close();"');
            $button_3->setClass('btn btn-danger');
            $button_tray->addElement($button_3);
            break;

            case 'smilies':
            default:
            $button_3 = new XoopsFormButton('', 'cancel', XoopsLocale::A_CANCEL, 'button');
            $button_3->setExtra("onclick='javascript:history.go(-1);'");
            $button_3->setClass('btn btn-danger');
            $button_tray->addElement($button_3);
            break;
        }

        $this->addElement($button_tray);
    }
}

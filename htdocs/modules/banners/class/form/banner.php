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
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id: $
 */
class BannersBannerForm extends Xoops\Form\ThemeForm
{
    /**
     * @param BannersBanner|XoopsObject $obj
     */
    public function __construct(BannersBanner &$obj)
    {
        $xoops = Xoops::getInstance();
        $helper = Banners::getInstance();

        if ($obj->isNew()) {
            $blank_img = 'blank.gif';
            $html_banner = 0;
        } else {
            if (substr_count($obj->getVar('banner_imageurl'), $xoops_upload_url . '/banners/') == 0) {
                $blank_img = 'blank.gif';
            } else {
                $namefile =
                    substr_replace($obj->getVar('banner_imageurl'), '', 0, strlen($xoops_upload_url . '/banners/'));
                $pathfile =  $xoops_root_path . '/uploads/banners/' . $namefile;
                if (is_file($pathfile)) {
                    $blank_img = str_replace($xoops_upload_url . '/banners/', '', $obj->getVar('banner_imageurl', 'e'));
                } else {
                    $blank_img = 'blank.gif';
                }
            }
            $html_banner = $obj->getVar('banner_htmlbanner');
        }

        $title = $obj->isNew() ? sprintf(_AM_BANNERS_BANNERS_ADD) : sprintf(_AM_BANNERS_BANNERS_EDIT);

        parent::__construct($title, 'form', 'banners.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
        $client_Handler = $helper->getHandlerBannerclient();
        $client_select = new Xoops\Form\Select(_AM_BANNERS_CLIENTS_NAME, 'cid', $obj->getVar('banner_cid'));
        $client_select->addOptionArray($client_Handler->getList());
        $this->addElement($client_select, true);

        $imptotal =
            new Xoops\Form\Text(_AM_BANNERS_BANNERS_IMPRESSIONSP, 'imptotal', 1, 255, $obj->getVar('banner_imptotal'));
        //$imptotal->setPattern('^[0-9]*[0-9]+$|^[0-9]+[0-9]*$', _AM_BANNERS_BANNERS_IMPRESSIONSP_PATTERN);
        $this->addElement($imptotal, true);

        $imgtray_img = new Xoops\Form\ElementTray(_AM_BANNERS_BANNERS_IMAGE, '<br /><br />');
        $imgtray_img->addElement(
            new Xoops\Form\Text(_AM_BANNERS_BANNERS_IMGURL, 'imageurl', 8, 255, $obj->getVar('banner_imageurl'))
        );
        $imgpath_img = sprintf(_AM_BANNERS_BANNERS_IMAGE_PATH, $xoops_upload_path . '/banners/');
        $imageselect_img = new Xoops\Form\Select($imgpath_img, 'banners_imageurl', $blank_img);
        $image_array_img = XoopsLists::getImgListAsArray($xoops_upload_path . '/banners');
        $imageselect_img->addOption("$blank_img", $blank_img);
        foreach ($image_array_img as $image_img) {
            $imageselect_img->addOption("$image_img", $image_img);
        }
        $imageselect_img->setExtra(
            'onchange="showImgSelected(\'xo-banners-img\', \'banners_imageurl\', \'banners\', \'\', \''
            . $xoops_upload_url . '\' )"'
        );
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(
            new Xoops\Form\Label(
                '',
                "<br /><img src='" . $xoops_upload_url . "/banners/" . $blank_img
                . "' name='image_img' id='xo-banners-img' alt='' />"
            )
        );
        $fileseltray_img = new Xoops\Form\ElementTray('<br />', '<br /><br />');
        $fileseltray_img->addElement(new Xoops\Form\File(_AM_BANNERS_BANNERS_UPLOADS, 'banners_imageurl'), false);
        $fileseltray_img->addElement(new Xoops\Form\Label(''), false);
        $imgtray_img->addElement($fileseltray_img);
        $this->addElement($imgtray_img);

        $this->addElement(new Xoops\Form\Text(_AM_BANNERS_BANNERS_CLICKURL, 'clickurl', 5, 255, $obj->getVar('banner_clickurl')), false);

        $this->addElement(new Xoops\Form\RadioYesNo(_AM_BANNERS_BANNERS_USEHTML, 'htmlbanner', $html_banner));

        $this->addElement(new Xoops\Form\TextArea(_AM_BANNERS_BANNERS_CODEHTML, 'htmlcode', $obj->getVar('banner_htmlcode'), 5, 5), false);
        if (!$obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden('bid', $obj->getVar('banner_bid')));
        }
        $this->addElement(new Xoops\Form\Hidden('op', 'save'));
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}

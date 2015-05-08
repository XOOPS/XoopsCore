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
 *  Publisher form class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once dirname(dirname(__DIR__)) . '/include/common.php';

class PublisherFileForm extends Xoops\Form\ThemeForm
{
    /**
     * @param PublisherFile $obj
     */
    public function __construct(PublisherFile $obj)
    {

        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();
        $publisher->loadLanguage('main');

        parent::__construct(_AM_PUBLISHER_UPLOAD_FILE, "form", $xoops->getEnv('PHP_SELF'));
        $this->setExtra('enctype="multipart/form-data"');

        // NAME
        $name_text = new Xoops\Form\Text(_CO_PUBLISHER_FILENAME, 'name', 50, 255, $obj->getVar('name'));
        $name_text->setDescription(_CO_PUBLISHER_FILE_NAME_DSC);
        $this->addElement($name_text, true);

        // DESCRIPTION
        $description_text = new Xoops\Form\TextArea(_CO_PUBLISHER_FILE_DESCRIPTION, 'description', $obj->getVar('description'));
        $description_text->setDescription(_CO_PUBLISHER_FILE_DESCRIPTION_DSC);
        $this->addElement($description_text);

        // FILE TO UPLOAD
        $file_box = new Xoops\Form\File(_CO_PUBLISHER_FILE_TO_UPLOAD, "item_upload_file");
        $file_box->setExtra("size ='50'");
        $this->addElement($file_box);

        $status_select = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_FILE_STATUS, 'file_status', _PUBLISHER_STATUS_FILE_ACTIVE);
        $status_select->setDescription(_CO_PUBLISHER_FILE_STATUS_DSC);
        $this->addElement($status_select);

        // fileid
        $this->addElement(new Xoops\Form\Hidden('fileid', $obj->getVar('fileid')));

        // itemid
        $this->addElement(new Xoops\Form\Hidden('itemid', $obj->getVar('itemid')));

        $files_button_tray = new Xoops\Form\ElementTray('', '');
        $files_hidden = new Xoops\Form\Hidden('op', 'uploadfile');
        $files_button_tray->addElement($files_hidden);

        if (!$obj->getVar('fileid')) {
            $files_butt_create = new Xoops\Form\Button('', '', _MD_PUBLISHER_UPLOAD, 'submit');
            $files_butt_create->setExtra('onclick="this.form.elements.op.value=\'uploadfile\'"');
            $files_button_tray->addElement($files_butt_create);

            $files_butt_another = new Xoops\Form\Button('', '', _CO_PUBLISHER_FILE_UPLOAD_ANOTHER, 'submit');
            $files_butt_another->setExtra('onclick="this.form.elements.op.value=\'uploadanother\'"');
            $files_button_tray->addElement($files_butt_another);
        } else {
            $files_butt_create = new Xoops\Form\Button('', '', _MD_PUBLISHER_MODIFY, 'submit');
            $files_butt_create->setExtra('onclick="this.form.elements.op.value=\'modify\'"');
            $files_button_tray->addElement($files_butt_create);
        }

        $files_butt_clear = new Xoops\Form\Button('', '', _MD_PUBLISHER_CLEAR, 'reset');
        $files_button_tray->addElement($files_butt_clear);

        $butt_cancel = new Xoops\Form\Button('', '', _MD_PUBLISHER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $files_button_tray->addElement($butt_cancel);

        $this->addElement($files_button_tray);
    }
}

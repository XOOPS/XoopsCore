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
 * @version         $Id$
 */

class BannersBannerclientForm extends Xoops\Form\ThemeForm
{
    /**
     * @param BannersBannerclient $obj
     */
    public function __construct(BannersBannerClient $obj)
    {
        $title = $obj->isNew() ? sprintf(_AM_BANNERS_CLIENTS_ADD) : sprintf(_AM_BANNERS_CLIENTS_EDIT);

        parent::__construct($title, 'form', 'clients.php', 'post', true);

        $this->addElement(new Xoops\Form\Text(_AM_BANNERS_CLIENTS_NAME, 'name', 5, 255, $obj->getVar('bannerclient_name')), true);
        // date
        if ($obj->isNew()) {
            $user = 'N';
        } else {
            if ($obj->getVar('bannerclient_uid') == 0) {
                $user = 'N';
            } else {
                $user = 'Y';
            }
        }
        $uname = new Xoops\Form\ElementTray(_AM_BANNERS_CLIENTS_UNAME, '');
        $type = new Xoops\Form\Radio('', 'user', $user);
        $options = array('N' =>_AM_BANNERS_CLIENTS_UNAME_NO, 'Y' => _AM_BANNERS_CLIENTS_UNAME_YES);
        $type->addOptionArray($options);
        $uname->addElement($type);
        $uname->addElement(new Xoops\Form\SelectUser('', 'uid', false, $obj->getVar('bannerclient_uid'), 1, false));
        $this->addElement($uname);
        $this->addElement(new Xoops\Form\TextArea(_AM_BANNERS_CLIENTS_EXTRAINFO, 'extrainfo', $obj->getVar('bannerclient_extrainfo'), 5, 5), false);
        if (!$obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden('cid', $obj->getVar('bannerclient_cid')));
        }
        $this->addElement(new Xoops\Form\Hidden('op', 'save'));
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}

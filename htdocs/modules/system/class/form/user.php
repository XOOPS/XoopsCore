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
 * SystemUserForm
 *
 * @category  Modules/system/class/form
 * @package   SystemUserForm
 * @author    Andricq Nicolas (AKA MusS)
 * @copyright 2000-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SystemUserForm extends Xoops\Form\ThemeForm
{
    /**
     * __construct
     *
     * @param XoopsUser|XoopsObject &$obj user object
     */
    public function __construct(XoopsUser &$obj)
    {
        $xoops = Xoops::getInstance();
        if ($obj->isNew()) {
            //Add user
            $uid_value = "";
            $uname_value = "";
            $name_value = "";
            $email_value = "";
            $email_cbox_value = 0;
            $url_value = "";
            $timezone_value = $xoops->getConfig('default_TZ');
            $icq_value = "";
            $aim_value = "";
            $yim_value = "";
            $msnm_value = "";
            $location_value = "";
            $occ_value = "";
            $interest_value = "";
            $sig_value = "";
            $sig_cbox_value = 0;
            $bio_value = "";
            $rank_value = 0;
            $mailok_value = 0;
            $form_title = SystemLocale::ADD_USER;
            $form_isedit = false;
            $groups = array(XOOPS_GROUP_USERS);
        } else {
            //Edit user
            $uid_value = $obj->getVar("uid", "E");
            $uname_value = $obj->getVar("uname", "E");
            $name_value = $obj->getVar("name", "E");
            $email_value = $obj->getVar("email", "E");
            $email_cbox_value = $obj->getVar("user_viewemail") ? 1 : 0;
            $url_value = $obj->getVar("url", "E");
            $timezone_value = $obj->getVar("timezone_offset");
            $icq_value = $obj->getVar("user_icq", "E");
            $aim_value = $obj->getVar("user_aim", "E");
            $yim_value = $obj->getVar("user_yim", "E");
            $msnm_value = $obj->getVar("user_msnm", "E");
            $location_value = $obj->getVar("user_from", "E");
            $occ_value = $obj->getVar("user_occ", "E");
            $interest_value = $obj->getVar("user_intrest", "E");
            $sig_value = $obj->getVar("user_sig", "E");
            $sig_cbox_value = ($obj->getVar("attachsig") == 1) ? 1 : 0;
            $bio_value = $obj->getVar("bio", "E");
            $rank_value = $obj->rank(false);
            $mailok_value = $obj->getVar('user_mailok', 'E');
            $form_title = sprintf(SystemLocale::F_UPDATE_USER, $obj->getVar("uname"));
            $form_isedit = true;
            $groups = array_values($obj->getGroups());
        }

        //Affichage du formulaire
        parent::__construct($form_title, "form_user", "admin.php", "post", true);

        $this->addElement(new Xoops\Form\Text(XoopsLocale::USER_NAME, "username", 4, 25, $uname_value), true);
        $this->addElement(new Xoops\Form\Text(XoopsLocale::NAME, "name", 5, 60, $name_value));
        $email_tray = new Xoops\Form\ElementTray(XoopsLocale::EMAIL, "<br />");
        $email_text = new Xoops\Form\Text("", "email", 5, 60, $email_value);
        $email_tray->addElement($email_text, true);
        $email_cbox = new Xoops\Form\Checkbox("", "user_viewemail", $email_cbox_value);
        $email_cbox->addOption(1, XoopsLocale::ALLOW_OTHER_USERS_TO_VIEW_EMAIL);
        $email_tray->addElement($email_cbox);
        $this->addElement($email_tray, true);
        $this->addElement(new Xoops\Form\Text(XoopsLocale::WEB_URL, "url", 5, 100, $url_value));
        $this->addElement(new Xoops\Form\SelectTimeZone(XoopsLocale::TIME_ZONE, "timezone_offset", $timezone_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::ICQ, "user_icq", 3, 15, $icq_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::AIM, "user_aim", 3, 18, $aim_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::YIM, "user_yim", 3, 25, $yim_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::MSNM, "user_msnm", 3, 100, $msnm_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::LOCATION, "user_from", 5, 100, $location_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::OCCUPATION, "user_occ", 5, 100, $occ_value));
        $this->addElement(new Xoops\Form\Text(XoopsLocale::INTEREST, "user_intrest", 5, 150, $interest_value));
        $sig_tray = new Xoops\Form\ElementTray(XoopsLocale::SIGNATURE, "<br />");
        $sig_tarea = new Xoops\Form\TextArea("", "user_sig", $sig_value, 5, 5);
        $sig_tray->addElement($sig_tarea);
        $sig_cbox = new Xoops\Form\Checkbox("", "attachsig", $sig_cbox_value);
        $sig_cbox->addOption(1, XoopsLocale::ALWAYS_ATTACH_MY_SIGNATURE);
        $sig_tray->addElement($sig_cbox);
        $this->addElement($sig_tray);
        $this->addElement(new Xoops\Form\TextArea(XoopsLocale::EXTRA_INFO, "bio", $bio_value, 5, 5));

        if ($xoops->isActiveModule('userrank')) {
            $rank_select = new Xoops\Form\Select(XoopsLocale::RANK, "rank", $rank_value);
            $ranklist = XoopsLists::getUserRankList();
            $rank_select->addOption(0, "--------------");
            if (count($ranklist) > 0) {
                $rank_select->addOptionArray($ranklist);
            }
            $this->addElement($rank_select);
        } else {
            $this->addElement(new Xoops\Form\Hidden("rank", $rank_value));
        }
        // adding a new user requires password fields
        if (!$form_isedit) {
            $this->addElement(new Xoops\Form\Password(XoopsLocale::PASSWORD, "password", 3, 32), true);
            $this->addElement(new Xoops\Form\Password(XoopsLocale::RETYPE_PASSWORD, "pass2", 3, 32), true);
        } else {
            $this->addElement(new Xoops\Form\Password(XoopsLocale::PASSWORD, "password", 3, 32));
            $this->addElement(new Xoops\Form\Password(XoopsLocale::RETYPE_PASSWORD, "pass2", 3, 32));
        }
        $this->addElement(
            new Xoops\Form\RadioYesNo(XoopsLocale::ONLY_USERS_THAT_ACCEPT_EMAIL, 'user_mailok', $mailok_value)
        );

        //Groups administration addition XOOPS 2.0.9: Mith
        $gperm_handler = $xoops->getHandlerGroupperm();
        $group_select = array();
        //If user has admin rights on groups
        if ($gperm_handler->checkRight("system_admin", XOOPS_SYSTEM_GROUP, $xoops->user->getGroups(), 1)) {
            //add group selection
            $group_select[] = new Xoops\Form\SelectGroup(XoopsLocale::GROUPS, 'groups', false, $groups, 5, true);
        } else {
            //add each user groups
            foreach ($groups as $key => $group) {
                $group_select[] = new Xoops\Form\Hidden('groups[' . $key . ']', $group);
            }
        }
        foreach ($group_select as $group) {
            $this->addElement($group);
            unset($group);
        }

        $this->addElement(new Xoops\Form\Hidden("fct", "users"));
        $this->addElement(new Xoops\Form\Hidden("op", "users_save"));
        $this->addElement(new Xoops\Form\Button("", "submit", XoopsLocale::A_SUBMIT, "submit"));

        if (!empty($uid_value)) {
            $this->addElement(new Xoops\Form\Hidden("uid", $uid_value));
        }
    }
}

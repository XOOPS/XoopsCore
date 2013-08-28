<?php
/**
 * XOOPS Registeration Form
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$email_tray = new XoopsFormElementTray(XoopsLocale::EMAIL, '<br />');
$email_text = new XoopsFormText('', 'email', 25, 60, $myts->htmlSpecialChars($email));
$email_option = new XoopsFormCheckBox('', 'user_viewemail', $user_viewemail);
$email_option->addOption(1, XoopsLocale::ALLOW_OTHER_USERS_TO_VIEW_EMAIL);
$email_tray->addElement($email_text, true);
$email_tray->addElement($email_option);

$reg_form = new XoopsThemeForm(XoopsLocale::USER_REGISTRATION, 'userinfo', 'register.php', 'post', true);
$uname_size = $xoopsConfigUser['maxuname'] < 25 ? $xoopsConfigUser['maxuname'] : 25;
$reg_form->addElement(new XoopsFormText(XoopsLocale::USERNAME, 'uname', $uname_size, $uname_size, $myts->htmlSpecialChars($uname)), true);
$reg_form->addElement($email_tray);
$reg_form->addElement(new XoopsFormPassword(XoopsLocale::PASSWORD, 'pass', 10, 32, $myts->htmlSpecialChars($pass)), true);
$reg_form->addElement(new XoopsFormPassword(XoopsLocale::VERIFY_PASSWORD, 'vpass', 10, 32, $myts->htmlSpecialChars($vpass)), true);
$reg_form->addElement(new XoopsFormText(XoopsLocale::WEBSITE, 'url', 25, 255, $myts->htmlSpecialChars($url)));
$tzselected = ($timezone_offset != '') ? $timezone_offset : $xoopsConfig['default_TZ'];
$reg_form->addElement(new XoopsFormSelectTimezone(XoopsLocale::TIME_ZONE, 'timezone_offset', $tzselected));
//$reg_form->addElement($avatar_tray);
$reg_form->addElement(new XoopsFormRadioYN(XoopsLocale::Q_RECEIVE_OCCASIONAL_EMAIL_NOTICES_FROM_ADMINISTRATORS, 'user_mailok', $user_mailok));
if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
    $disc_tray = new XoopsFormElementTray(XoopsLocale::DISCLAIMER, '<br />');
    $disc_text = new XoopsFormTextarea('', 'disclaimer', $xoopsConfigUser['reg_disclaimer'], 15, 80);
    $disc_text->setExtra('readonly="readonly"');
    $disc_tray->addElement($disc_text);
    $agree_chk = new XoopsFormCheckBox('', 'agree_disc', $agree_disc);
    $agree_chk->addOption(1, XoopsLocale::I_AGREE_TO_THE_ABOVE);
    $eltname = $agree_chk->getName();
    $eltmsg = str_replace('"', '\"', stripslashes(sprintf(XoopsLocale::F_ENTER, XoopsLocale::I_AGREE_TO_THE_ABOVE)));
    $agree_chk->customValidationCode[] = "if ( myform.{$eltname}.checked == false ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
    $disc_tray->addElement($agree_chk, true);
    $reg_form->addElement($disc_tray);
}
$reg_form->addElement(new XoopsFormHidden('op', 'newuser'));
$reg_form->addElement(new XoopsFormButton('', 'submitButton', XoopsLocale::A_SUBMIT, 'submit'));
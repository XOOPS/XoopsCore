<?php
/**
 *  xoops_code plugin for tinymce
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class / xoopseditor
 * @subpackage      tinymce / xoops plugins
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

$xoops_root_path = dirname( dirname ( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) ) ;
include_once $xoops_root_path . '/mainfile.php';
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$xoops->simpleHeader(true);

$form = new XoopsThemeForm('', 'imagecat_form', '#', false, 'vertical');
$form->addElement( new XoopsFormTextArea(XoopsLocale::PASTE_THE_CODE_YOU_WANT_TO_INSERT, 'text_id', '', 9, 7) );
/**
 * Buttons
 */
$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormHidden('op', 'save'));

$button = new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$button->setExtra('onclick="Xoops_codeDialog.insert();"');
$button->setClass('btn btn-success');
$button_tray->addElement($button);

$button_2 = new XoopsFormButton('', 'reset', XoopsLocale::A_RESET, 'reset');
$button_2->setClass('btn btn-warning');
$button_tray->addElement($button_2);

$button_3 = new XoopsFormButton('', 'button', XoopsLocale::A_CLOSE, 'button');
$button_3->setExtra('onclick="tinyMCEPopup.close();"');
$button_3->setClass('btn btn-danger');
$button_tray->addElement($button_3);

$form->addElement($button_tray);

$xoopsTpl = new XoopsTpl();
$xoopsTpl->assign('js_file', 'js/xoops_code.js');
$xoopsTpl->assign('css_file', 'css/xoops_code.css');
$xoopsTpl->assign('form', $form->render());
$xoopsTpl->assign('include_html', '');

$xoopsTpl->display('module:system|system_tinymce.tpl');
$xoops->simpleFooter();

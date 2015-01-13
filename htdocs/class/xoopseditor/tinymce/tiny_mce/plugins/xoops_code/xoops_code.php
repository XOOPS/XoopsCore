<?php
/**
 *  xoops_code plugin for tinymce
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class / xoopseditor
 * @subpackage      tinymce / xoops plugins
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

$xoops_root_path = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
include_once $xoops_root_path . '/mainfile.php';
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$xoops->simpleHeader(true);

$form = new Xoops\Form\ThemeForm('', 'imagecat_form', '#', false, 'vertical');
$form->addElement(new Xoops\Form\TextArea(XoopsLocale::PASTE_THE_CODE_YOU_WANT_TO_INSERT, 'text_id', '', 9, 7));
/**
 * Buttons
 */
$button_tray = new Xoops\Form\ElementTray('', '');
$button_tray->addElement(new Xoops\Form\Hidden('op', 'save'));

$button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$button->setExtra('onclick="Xoops_codeDialog.insert();"');
$button->setClass('btn btn-success');
$button_tray->addElement($button);

$button_2 = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
$button_2->setClass('btn btn-warning');
$button_tray->addElement($button_2);

$button_3 = new Xoops\Form\Button('', 'button', XoopsLocale::A_CLOSE, 'button');
$button_3->setExtra('onclick="tinyMCEPopup.close();"');
$button_3->setClass('btn btn-danger');
$button_tray->addElement($button_3);

$form->addElement($button_tray);

$xoopsTpl = new XoopsTpl();
$xoopsTpl->assign('js_file', 'js/xoops_code.js');
$xoopsTpl->assign('css_file', 'css/xoops_code.css');
$xoopsTpl->assign('form', $form->render());
$xoopsTpl->assign('include_html', '');

$xoopsTpl->display('module:system/system_tinymce.tpl');
$xoops->simpleFooter();

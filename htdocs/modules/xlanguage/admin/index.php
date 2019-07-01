<?php
/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
use Xmf\Request;

include __DIR__ . '/header.php';

switch ($op) {
    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('index.php', 2, implode(',', $xoops->security()->getErrors()));
        }

        $xlanguage_id = Request::getInt('xlanguage_id', 0, 'POST');
        if (isset($xlanguage_id) && $xlanguage_id > 0) {
            $lang = $helper->getHandlerLanguage()->get($xlanguage_id);
        } else {
            $lang = $helper->getHandlerLanguage()->create();
        }
        $lang->cleanVarsForDB();

        if ($helper->getHandlerLanguage()->insert($lang)) {
            $helper->getHandlerLanguage()->createConfig();
            $xoops->redirect('index.php', 2, _AM_XLANGUAGE_SAVED);
        }
        break;
    case 'add':
        $lang = $helper->getHandlerLanguage()->create();
        $form = $helper->getForm($lang, 'language');
        $form->display();
        //$admin_page->addInfoBox(_MI_XLANGUAGE_MODIFY);
        //$admin_page->addInfoBoxLine($form->render());
        break;
    case 'edit':
        $xlanguage_id = Request::getInt('xlanguage_id', 0, 'REQUEST');
        if (isset($xlanguage_id) && $xlanguage_id > 0) {
            if ($lang = $helper->getHandlerLanguage()->get($xlanguage_id)) {
                $form = $helper->getForm($lang, 'language');
                $form->display();
            //$admin_page->addInfoBox(_MI_XLANGUAGE_MODIFY);
                //$admin_page->addInfoBoxLine($form->render());
            } else {
                $xoops->redirect('index.php', 2);
            }
        } else {
            $xoops->redirect('index.php', 2);
        }
        break;
    case 'del':
        $xlanguage_id = Request::getInt('xlanguage_id', 0);
        if (isset($xlanguage_id) && $xlanguage_id > 0) {
            if ($lang = $helper->getHandlerLanguage()->get($xlanguage_id)) {
                $delete = Request::getInt('ok', 0, 'POST');
                if (1 == $delete) {
                    if (!$xoops->security()->check()) {
                        $xoops->redirect('index.php', 2, implode(',', $xoops->security()->getErrors()));
                    }
                    $helper->getHandlerLanguage()->delete($lang);
                    $helper->getHandlerLanguage()->createConfig();
                    $xoops->redirect('index.php', 2, _AM_XLANGUAGE_DELETED);
                } else {
                    $confirm = $xoops->confirm([
                        'ok' => 1, 'xlanguage_id' => $xlanguage_id, 'op' => 'del',
                    ], $_SERVER['REQUEST_URI'], sprintf(_AM_XLANGUAGE_DELETE_CFM . "<br /><b><span style='color : Red'> %s </span></b><br /><br />", $lang->getVar('xlanguage_name')));
                    $confirm = '<div class="confirm">' . $confirm . '</div>';
                    $admin_page->addInfoBox(_MI_XLANGUAGE_DELETE);
                    $admin_page->addInfoBoxLine($confirm);
                    $admin_page->displayIndex();
                }
            } else {
                $xoops->redirect('index.php', 2);
            }
        } else {
            $xoops->redirect('index.php', 2);
        }
        break;
    case 'createconfig':
        $helper->getHandlerLanguage()->createConfig();
        $xoops->redirect('index.php', 2, _AM_XLANGUAGE_CREATED);
        break;
    case 'default':
    default:
        $admin_page->addInfoBox(_AM_XLANGUAGE_LANGLIST);
        $admin_page->addInfoBoxLine($helper->getHandlerLanguage()->renderlist());
        $admin_page->displayIndex();
        break;
}
include __DIR__ . '/footer.php';

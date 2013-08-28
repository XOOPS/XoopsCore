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
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Laurent JEN (aka DuGris)
 * @package     system
 * @version     $Id$
 */

function b_search_show()
{    $search = Search::getInstance();
    $search->loadLanguage('main', 'search');

    $form = new XoopsSimpleForm('', 'search', $search->url('index.php'), 'get', false, 'inline');

    // create form elements
    $form->addElement(new XoopsFormText('', 'query', 2, 100, '', _MD_SEARCH_KEYWORDS), true);
    $form->addElement(new XoopsFormHidden('action', 'results'));
    $form->addElement(new XoopsFormHiddenToken('id'));

    $button = new XoopsFormButton('', 'submit', _MD_SEARCH, 'submit');
    $button->setClass('btn btn-primary');
    $form->addElement($button);

    $block['form'] = $form->render();
    return $block;
}
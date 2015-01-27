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
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      debugbar
 * @since
 * @author       XOOPS Development Team
 */

use Xoops\Core\Request;
use Xmf\Module\Helper;
use Xmf\Module\Permission;

include_once __DIR__ . '/admin_header.php';

$moduleAdmin = new \Xoops\Module\Admin();
$moduleAdmin->displayNavigation('permissions.php');

$helper = Helper::getHelper('logger');
$permHelper = new Permission();
if ($permHelper) {
    // this is the name and item we are going to work with
    $gperm_name='use_logger';
    $gperm_itemid=0;

    // if this is a post operation get our variables
    if ('POST'==Request::getMethod()) {
        $name=$permHelper->defaultFieldName($gperm_name, $gperm_itemid);
        $groups=Request::getVar($name, array(), $hash = 'POST');
        $permHelper->savePermissionForItem($gperm_name, $gperm_itemid, $groups);
        echo $xoops->alert('success', _MA_LOGGER_FORM_PROCESSED, _MA_LOGGER_PERMISSION_FORM);
    }

    $form = new \Xoops\Form\ThemeForm(_MA_LOGGER_PERMISSION_FORM, 'form', '', 'POST');
    $permElement = $permHelper->getGroupSelectFormForItem(
        $gperm_name,
        $gperm_itemid,
        _MA_LOGGER_PERMISSION_GROUPS,
        null,
        true
    );

    $form->addElement($permElement);
    $form->addElement(new \Xoops\Form\Button('', 'submit', _MA_LOGGER_FORM_SUBMIT, 'submit'));

    echo $form->render();
}

include_once __DIR__ . '/admin_footer.php';

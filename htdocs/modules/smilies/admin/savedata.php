<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Request;
use Xmf\Database\TableLoad;
use Xoops\Form\ElementFactory;
use Xoops\Form\ThemeForm;

/**
 * smilies module
 *
 * @copyright       2019 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Xoops Core Development Team - Mage Grégory (AKA Mage) - Laurent JEN (aka DuDris)
 */

include __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$xoops->header();
$admin = new \Xoops\Module\Admin();
$admin->displayNavigation(basename(__FILE__));

$op = Request::getCmd('op');

switch ($op) {
    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect(basename(__FILE__), 3, implode('<br />', $xoops->security()->getErrors()));
        }
        $skipColumns = array('smiley_id');
        $status = TableLoad::saveTableToYamlFile('smilies', '../sql/smiliesdata.yml', null, $skipColumns);
        echo $status ? 'Data saved.' : 'Failed';
        break;
    default:
        $form = new ThemeForm('Export Smiley Data', 'smiliedata', '', 'post', true);
        $factory = new ElementFactory();
        $factory->setContainer($form);
        $factory->create([
            ElementFactory::CLASS_KEY => 'Button',
            'caption' => '',
            'name' => 'op',
            'type' => 'submit',
            'value' => 'Save',
            'class' => 'btn btn-danger',
            'onclick' => 'return confirm("Are you sure?");',
        ]);
        $form->display();
        break;
}

$xoops->footer();

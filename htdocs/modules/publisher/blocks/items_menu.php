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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @subpackage      Blocks
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
use Xoops\Form\BlockForm;
use Xoops\Form\Label;
use Xoops\Form\Select;
use Xoops\Form\Text;
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

require_once dirname(__DIR__) . '/include/common.php';

/**
 * @param $options
 * @return array
 */
function publisher_items_menu_show($options)
{
    $block = [];

    $helper = Helper::getInstance();

    // Getting all top cats
    $block_categoriesObj = $helper->getCategoryHandler()->getCategories(0, 0, 0);

    if (0 == count($block_categoriesObj)) {
        return $block;
    }

    // Are we in Publisher ?
    $block['inModule'] = $helper->isCurrentModule();

    $catlink_class = 'menuMain';

    $categoryid = 0;

    if ($block['inModule']) {
        // Are we in a category and if yes, in which one ?
        $categoryid = isset($_GET['categoryid']) ? (int)$_GET['categoryid'] : 0;

        if (0 != $categoryid) {
            // if we are in a category, then the $categoryObj is already defined in publisher/category.php
            $categoryObj = $helper->getCategoryHandler()->get($categoryid);
            $block['currentcat'] = $categoryObj->getCategoryLink('menuTop');
            $catlink_class = 'menuSub';
        }
    }
    /* @var Publisher\Category $block_categoryObj */
    foreach ($block_categoriesObj as $catid => $block_categoryObj) {
        if ($catid != $categoryid) {
            $block['categories'][$catid]['categoryLink'] = $block_categoryObj->getCategoryLink($catlink_class);
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function publisher_items_menu_edit($options)
{
    $form = new BlockForm();

    $catEle = new Label(_MB_PUBLISHER_SELECTCAT, Publisher\Utils::createCategorySelect($options[0], 0, true, 'options[0]'));
    $orderEle = new Select(_MB_PUBLISHER_ORDER, 'options[1]', $options[1]);
    $orderEle->addOptionArray([
                                  'datesub' => _MB_PUBLISHER_DATE,
                                  'counter' => _MB_PUBLISHER_HITS,
                                  'weight' => _MB_PUBLISHER_WEIGHT,
                              ]);
    $dispEle = new Text(_MB_PUBLISHER_DISP, 'options[2]', 10, 255, $options[2]);

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($dispEle);

    return $form->render();
}

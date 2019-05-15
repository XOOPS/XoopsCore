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
use Xoops\Form\RadioYesNo;
use Xoops\Form\Select;
use Xoops\Form\Text;
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;

require_once dirname(__DIR__) . '/include/common.php';

/**
 * @param $options
 * @return array
 */
function publisher_latest_files_show($options)
{
    $helper = Helper::getInstance();
    /**
     * $options[0] : Category
     * $options[1] : Sort order - datesub | counter
     * $options[2] : Number of files to display
     * $oprions[3] : bool TRUE to link to the file download, FALSE to link to the article
     */

    $block = [];

    $sort = $options[1];
    $order = Publisher\Utils::getOrderBy($sort);
    $limit = $options[2];
    $directDownload = $options[3];

    // creating the files objects
    $filesObj = $helper->getFileHandler()->getAllFiles(0, _PUBLISHER_STATUS_FILE_ACTIVE, $limit, 0, $sort, $order, explode(',', $options[0]));
    /* @var Publisher\File $fileObj */
    foreach ($filesObj as $fileObj) {
        $aFile = [];
        $aFile['link'] = $directDownload ? $fileObj->getFileLink() : $fileObj->getItemLink();
        if ('datesub' === $sort) {
            $aFile['new'] = $fileObj->datesub();
        } elseif ('counter' === $sort) {
            $aFile['new'] = $fileObj->getVar('counter');
        } elseif ('weight' === $sort) {
            $aFile['new'] = $fileObj->getVar('weight');
        }
        $block['files'][] = $aFile;
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function publisher_latest_files_edit($options)
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
    $directEle = new RadioYesNo(_MB_PUBLISHER_DIRECTDOWNLOAD, 'options[3]', $options[3]);

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($dispEle);
    $form->addElement($directEle);

    return $form->render();
}

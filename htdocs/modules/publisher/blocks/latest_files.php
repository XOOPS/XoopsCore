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

include_once dirname(__DIR__) . '/include/common.php';

function publisher_latest_files_show($options)
{
    $publisher = Publisher::getInstance();
    /**
     * $options[0] : Category
     * $options[1] : Sort order - datesub | counter
     * $options[2] : Number of files to display
     * $oprions[3] : bool TRUE to link to the file download, FALSE to link to the article
     */

    $block = array();

    $sort = $options[1];
    $order = PublisherUtils::getOrderBy($sort);
    $limit = $options[2];
    $directDownload = $options[3];

    // creating the files objects
    $filesObj = $publisher->getFileHandler()->getAllFiles(0, _PUBLISHER_STATUS_FILE_ACTIVE, $limit, 0, $sort, $order, explode(',', $options[0]));
    /* @var $fileObj PublisherFile */
    foreach ($filesObj as $fileObj) {
        $aFile = array();
        $aFile['link'] = $directDownload ? $fileObj->getFileLink() : $fileObj->getItemLink();
        if ($sort == "datesub") {
            $aFile['new'] = $fileObj->datesub();
        } elseif ($sort == "counter") {
            $aFile['new'] = $fileObj->getVar('counter');
        } elseif ($sort == "weight") {
            $aFile['new'] = $fileObj->getVar('weight');
        }
        $block['files'][] = $aFile;
    }

    return $block;
}

function publisher_latest_files_edit($options)
{
    $form = new Xoops\Form\BlockForm();

    $catEle = new Xoops\Form\Label(_MB_PUBLISHER_SELECTCAT, PublisherUtils::createCategorySelect($options[0], 0, true, 'options[0]'));
    $orderEle = new Xoops\Form\Select(_MB_PUBLISHER_ORDER, 'options[1]', $options[1]);
    $orderEle->addOptionArray(array(
        'datesub' => _MB_PUBLISHER_DATE,
        'counter' => _MB_PUBLISHER_HITS,
        'weight'  => _MB_PUBLISHER_WEIGHT,
    ));
    $dispEle = new Xoops\Form\Text(_MB_PUBLISHER_DISP, 'options[2]', 10, 255, $options[2]);
    $directEle = new Xoops\Form\RadioYesNo(_MB_PUBLISHER_DIRECTDOWNLOAD, 'options[3]', $options[3]);

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($dispEle);
    $form->addElement($directEle);

    return $form->render();
}

<?php

namespace XoopsModules\Publisher;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Notifications;
use Xoops;
use Xoops\Core\Kernel\XoopsObject;
use XoopsModules\Publisher;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class Category
 * @package XoopsModules\Publisher
 */
class Category extends XoopsObject
{
    /**
     * @var Helper
     * @access public
     */
    public $helper = null;

    /**
     * @var array
     */
    public $_categoryPath = false;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->helper = Helper::getInstance();
        $this->initVar('categoryid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('parentid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', \XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('description', \XOBJ_DTYPE_TXTAREA, null, false, 255);
        $this->initVar('image', \XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('total', \XOBJ_DTYPE_INT, 1, false);
        $this->initVar('weight', \XOBJ_DTYPE_INT, 1, false);
        $this->initVar('created', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('template', \XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('header', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('meta_keywords', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('meta_description', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('short_url', \XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('moderator', \XOBJ_DTYPE_INT, null, false, 0);
        //not persistent values
        $this->initVar('itemcount', \XOBJ_DTYPE_INT, 0, false);
        $this->initVar('last_itemid', \XOBJ_DTYPE_INT);
        $this->initVar('last_title_link', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 1, false);
    }

    public function notLoaded(): bool
    {
        return (-1 == $this->getVar('categoryid'));
    }

    public function checkPermission(): bool
    {
        $xoops = Xoops::getInstance();
        if ($this->helper->isUserAdmin()) {
            return true;
        }
        if ($xoops->isUser() && $xoops->user->getVar('uid') == $this->getVar('moderator')) {
            return true;
        }

        return $this->helper->getPermissionHandler()->isGranted('category_read', $this->getVar('categoryid'));
    }

    /**
     * @param string $format
     *
     * @return mixed|string
     */
    public function image($format = 's')
    {
        if ('' != $this->getVar('image')) {
            return $this->getVar('image', $format);
        }

        return 'blank.png';
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function template($format = 'n')
    {
        return $this->getVar('template', $format);
    }

    /**
     * @param bool $withAllLink
     *
     * @return array|bool|string
     */
    public function getCategoryPath($withAllLink = true)
    {
        if (!$this->_categoryPath) {
            if ($withAllLink) {
                $ret = $this->getCategoryLink();
            } else {
                $ret = $this->getVar('name');
            }
            $parentid = $this->getVar('parentid');
            if (0 != $parentid) {
                $parentObj = $this->helper->getCategoryHandler()->get($parentid);
                if ($parentObj->notLoaded()) {
                    exit;
                }
                $ret = $parentObj->getCategoryPath($withAllLink) . ' > ' . $ret;
            }
            $this->_categoryPath = $ret;
        }

        return $this->_categoryPath;
    }

    /**
     * @return mixed|string
     */
    public function getCategoryPathForMetaTitle()
    {
        $ret = '';
        $parentid = $this->getVar('parentid');
        if (0 != $parentid) {
            $parentObj = $this->helper->getCategoryHandler()->get($parentid);
            if ($parentObj->notLoaded()) {
                exit('NOT LOADED');
            }
            $ret = $parentObj->getCategoryPath(false);
            $ret = \str_replace(' >', ' -', $ret);
        }

        return $ret;
    }

    public function getGroups_read(): ?array
    {
        return $this->helper->getPermissionHandler()->getGrantedGroupsById('category_read', $this->getVar('categoryid'));
    }

    public function getGroups_submit(): ?array
    {
        return $this->helper->getPermissionHandler()->getGrantedGroupsById('item_submit', $this->getVar('categoryid'));
    }

    public function getGroups_moderation(): ?array
    {
        return $this->helper->getPermissionHandler()->getGrantedGroupsById('category_moderation', $this->getVar('categoryid'));
    }

    public function getCategoryUrl(): string
    {
        return Publisher\Utils::seoGenUrl('category', $this->getVar('categoryid'), $this->getVar('short_url'));
    }

    /**
     * @param bool $class
     *
     * @return string
     */
    public function getCategoryLink($class = false): ?string
    {
        if ($class) {
            return "<a class='$class' href='" . $this->getCategoryUrl() . "'>" . $this->getVar('name') . '</a>';
        }

        return "<a href='" . $this->getCategoryUrl() . "'>" . $this->getVar('name') . '</a>';
    }

    /**
     * @param bool $sendNotifications
     * @param bool $force
     *
     * @return mixed
     */
    public function store($sendNotifications = true, $force = true)
    {
        $ret = $this->helper->getCategoryHandler()->insert($this, $force);
        if ($sendNotifications && $ret && $this->isNew()) {
            $this->sendNotifications();
        }
        $this->unsetNew();

        return $ret;
    }

    /**
     * Send notifications
     */
    public function sendNotifications(): void
    {
        $xoops = Xoops::getInstance();
        if ($xoops->isActiveModule('notifications')) {
            $tags = [];
            $tags['MODULE_NAME'] = $this->helper->getModule()->getVar('name');
            $tags['CATEGORY_NAME'] = $this->getVar('name');
            $tags['CATEGORY_URL'] = $this->getCategoryUrl();
            $notificationHandler = Notifications::getInstance()->getHandlerNotification();
            $notificationHandler->triggerEvent('global', 0, 'category_created', $tags);
        }
    }

    /**
     * @param array $category
     *
     * @return array
     */
    public function toArray($category = [])
    {
        $category['categoryid'] = $this->getVar('categoryid');
        $category['name'] = $this->getVar('name');
        $category['categorylink'] = $this->getCategoryLink();
        $category['categoryurl'] = $this->getCategoryUrl();
        $category['total'] = ($this->getVar('itemcount') > 0) ? $this->getVar('itemcount') : '';
        $category['description'] = $this->getVar('description');
        $category['header'] = $this->getVar('header');
        $category['meta_keywords'] = $this->getVar('meta_keywords');
        $category['meta_description'] = $this->getVar('meta_description');
        $category['short_url'] = $this->getVar('short_url');
        if ($this->getVar('last_itemid') > 0) {
            $category['last_itemid'] = $this->getVar('last_itemid', 'n');
            $category['last_title_link'] = $this->getVar('last_title_link', 'n');
        }
        if ('blank.png' !== $this->image()) {
            $category['image_path'] = Publisher\Utils::getImageDir('category', false) . $this->image();
        } else {
            $category['image_path'] = '';
        }
        $category['lang_subcategories'] = \sprintf(_CO_PUBLISHER_SUBCATEGORIES_INFO, $this->getVar('name'));

        return $category;
    }

    /**
     * @param array $category
     */
    public function toArrayTable($category = []): array
    {
        $category['categoryid'] = $this->getVar('categoryid');
        $category['categorylink'] = $this->getCategoryLink();
        $category['total'] = ($this->getVar('itemcount') > 0) ? $this->getVar('itemcount') : '';
        $category['description'] = $this->getVar('description');
        if ($this->getVar('last_itemid') > 0) {
            $category['last_itemid'] = $this->getVar('last_itemid', 'n');
            $category['last_title_link'] = $this->getVar('last_title_link', 'n');
        }
        if ('blank.png' !== $this->image()) {
            $category['image_path'] = Publisher\Utils::getImageDir('category', false) . $this->image();
        } else {
            $category['image_path'] = '';
        }
        $category['lang_subcategories'] = \sprintf(_CO_PUBLISHER_SUBCATEGORIES_INFO, $this->getVar('name'));

        return $category;
    }

    public function createMetaTags(): void
    {
        $publisher_metagen = new Publisher\Metagen($this->getVar('name'), $this->getVar('meta_keywords'), $this->getVar('meta_description'));
        $publisher_metagen->createMetaTags();
    }
}

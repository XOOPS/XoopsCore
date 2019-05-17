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

/**
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Class
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
use Xoops\Module\Helper\HelperAbstract;
use XoopsDatabaseFactory;
use XoopsModules\Publisher;

/**
 * Class Helper
 * @package XoopsModules\Publisher
 */
class Helper extends HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('publisher');
        //$this->setDebug(true);
        $this->loadLanguage('modinfo');
    }

    /**
     * @return Helper
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return \XoopsModules\Publisher\ItemHandler
     */
    public function getItemHandler(): ItemHandler
    {
        return $this->getHandler('Item');
    }

    /**
     * @return \XoopsModules\Publisher\CategoryHandler
     */
    public function getCategoryHandler(): CategoryHandler
    {
        return $this->getHandler('Category');
    }

    /**
     * @return \XoopsModules\Publisher\PermissionHandler
     */
    public function getPermissionHandler(): PermissionHandler
    {
        return $this->getHandler('Permission');
    }

    /**
     * @return \XoopsModules\Publisher\FileHandler
     */
    public function getFileHandler(): FileHandler
    {
        return $this->getHandler('File');
    }

    /**
     * @return \XoopsModules\Publisher\MimetypeHandler
     */
    public function getMimetypeHandler(): MimetypeHandler
    {
        return $this->getHandler('Mimetype');
    }

    /**
     * @return \XoopsModules\Publisher\RatingHandler
     */
    public function getRatingHandler(): RatingHandler
    {
        return $this->getHandler('Rating');
    }

    /**
     * @return \XoopsModules\Publisher\GroupPermHandler
     */
    public function getGrouppermHandler(): GroupPermHandler
    {
        return $this->getHandler('GroupPerm');
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|\XoopsObjectHandler|\XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $ret = false;
        //        /** @var Connection $db */
        $db = XoopsDatabaseFactory::getConnection();
        $class = '\\XoopsModules\\' . \ucfirst(mb_strtolower(\basename(\dirname(__DIR__)))) . '\\' . $name . 'Handler';
        $ret = new $class($db);

        return $ret;
    }
}

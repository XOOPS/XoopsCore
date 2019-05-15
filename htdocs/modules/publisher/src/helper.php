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

class Publisher extends Xoops\Module\Helper\HelperAbstract
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
     * @return Publisher
     */
    static function getInstance() {
        return parent::getInstance();
    }

    /**
     * @return PublisherItemHandler
     */
    public function getItemHandler()
    {
        return $this->getHandler('item');
    }

    /**
     * @return PublisherCategoryHandler
     */
    public function getCategoryHandler()
    {
        return $this->getHandler('category');
    }

    /**
     * @return PublisherPermissionHandler
     */
    public function getPermissionHandler()
    {
        return $this->getHandler('permission');
    }

    /**
     * @return PublisherFileHandler
     */
    public function getFileHandler()
    {
        return $this->getHandler('file');
    }

    /**
     * @return PublisherMimetypeHandler
     */
    public function getMimetypeHandler()
    {
        return $this->getHandler('mimetype');
    }

    /**
     * @return PublisherRatingHandler
     */
    public function getRatingHandler()
    {
        return $this->getHandler('rating');
    }

    /**
     * @return PublisherGrouppermHandler
     */
    public function getGrouppermHandler()
    {
        return $this->getHandler('groupperm');
    }
}

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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */

class Banners extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('banners');
    }

    /**
     * @return Banners
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return BannersBannerHandler
     */
    public function getHandlerBanner()
    {
        return $this->getHandler('banner');
    }

    /**
     * @return BannersBannerclientHandler
     */
    public function getHandlerBannerclient()
    {
        return $this->getHandler('bannerclient');
    }
}

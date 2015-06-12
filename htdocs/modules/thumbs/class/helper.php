<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Module\Helper\HelperAbstract;

/**
 * Module helper for thumbs modue
 *
 * @category  Xoops\Module\Helper
 * @package   Thumbs
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Thumbs extends HelperAbstract
{
    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $this->_dirname = 'thumbs';
    }

    /**
     * buildThumbPath
     *
     * @param string  $imgPath xoops virtual path to image to be thumbed
     * @param integer $width   maximum width of thumbnail in pixels, 0 to use default
     * @param integer $height  maximum height of thumbnail in pixels, 0 to use default
     *
     * @return string xoops virtual path for the thumbnail
     */
    public function buildThumbPath($imgPath, $width, $height)
    {
        //$xoops = \Xoops::getInstance();
        if ($width==0 && $height==0) {
            $width = $this->getConfig('thumbs_width');
            $height = $this->getConfig('thumbs_height');
        }
        $sizeDir = sprintf('/%01dx%01d/', $width, $height);
        $pathParts = pathinfo($imgPath);
        if ($pathParts['dirname'] == '.') {
            $pathParts['dirname'] = '';
        }
        $thumbPath = 'assets/thumbs/' . $pathParts['dirname'].$sizeDir.$pathParts['basename'];

        return $thumbPath;
    }
}

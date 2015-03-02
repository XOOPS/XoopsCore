<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Contract;

use Xoops\Core\Service\Response;

/**
 * Thumbnail service interface
 *
 * @category  Xoops\Core\Service\Contract\ThumbnailInterface
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
interface ThumbnailInterface
{
    const MODE = \Xoops\Core\Service\Manager::MODE_EXCLUSIVE;

    /**
     * getImgUrl - get URL to a thumbnail of the supplied image
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $imgPath  path to image to be thumbed
     * @param integer  $width    maximum width of thumbnail in pixels, 0 to use default
     * @param integer  $height   maximum height of thumbnail in pixels, 0 to use default
     *
     * @return void  - response->value set to URL string
     */
    public function getImgUrl(Response $response, $imgPath, $width = 0, $height = 0);

    /**
     * getImgTag - get a full HTML img tag to display a thumbnail of the supplied image
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $imgPath    path to image to be thumbed
     * @param integer  $width      maximum width of thumbnail in pixels, 0 to use default
     * @param integer  $height     maximum height of thumbnail in pixels, 0 to use default
     * @param array    $attributes array of attribute name => value pairs for img tag
     *
     * @return void  - response->value set to image tag
     */
    public function getImgTag(
        Response $response,
        $imgPath,
        $width = 0,
        $height = 0,
        $attributes = array()
    );
}

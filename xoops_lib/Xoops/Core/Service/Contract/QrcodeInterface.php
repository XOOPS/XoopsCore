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
 * Qrcode service interface
 *
 * @category  Xoops\Core\Service\Contract\QrcodeInterface
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
interface QrcodeInterface
{
    const MODE = \Xoops\Core\Service\Manager::MODE_EXCLUSIVE;

    /**
     * getImgUrl - get URL to QR Code image of supplied text
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param string   $qrText   text to encode in QR Code
     *
     * @return void  - response->value set to URL string
     */
    public function getImgUrl(Response $response, $qrText);

    /**
     * getImgTag - get a full HTML img tag to display a QR Code image of supplied text
     *
     * @param Response $response   \Xoops\Core\Service\Response object
     * @param string   $qrText     text to encode in QR Code
     * @param array    $attributes array of attribute name => value pairs for img tag
     *
     * @return void  - response->value set to URL string
     */
    public function getImgTag(Response $response, $qrText, $attributes = array());
}

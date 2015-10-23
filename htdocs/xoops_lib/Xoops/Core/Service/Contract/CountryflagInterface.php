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
 * CountryFlag service interface
 *
 * @category  Xoops\Core\Service\Contract\CountryflagInterface
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
interface CountryflagInterface
{
    const MODE = \Xoops\Core\Service\Manager::MODE_EXCLUSIVE;

    /**
     * getImgTag - get a full HTML tag or string to display a flag based on county code
     *
     * @param Response $response    \Xoops\Core\Service\Response object
     * @param string   $countryCode ISO 3166-1 alpha-2 code to select flag
     * @param array    $attributes  array of attribute name => value pairs for img tag
     *
     * @return void  - response->value set to URL string
     */
    public function getImgTag(Response $response, $countryCode, $attributes = array());
}

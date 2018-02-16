<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Lists;

use \Punic\Territory;
use Xoops\Form\OptionElement;

/**
 * Country - provide list of country names
 *
 * @category  Xoops\Core\Lists\Country
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Country extends ListAbstract
{
    /**
     * Get a list of localized country names
     *
     * @return array
     */
    public static function getList()
    {
        $countryList = Territory::getCountries();
        \XoopsLocale::asort($countryList);
        return $countryList;
    }

    /**
     * add list to a Xoops\Form\OptionElement
     *
     * @param OptionElement $element
     *
     * @return void
     */
    public static function setOptionsArray(OptionElement $element)
    {
        $element->addOptionArray([""   => "-"]);
        parent::setOptionsArray($element);
    }
}

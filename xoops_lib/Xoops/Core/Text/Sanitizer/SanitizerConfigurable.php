<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text\Sanitizer;

use Xoops\Core\Text\Sanitizer;

/**
 * Configuration for Sanitizer Filters and Extensions
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Richrd Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class SanitizerConfigurable
{
    /**
     * @var array default configuration values
     */
    protected static $defaultConfiguration = [];

    /**
     * get the default configuration for a component
     *
     * @return array [componentName] => [ ... configuration items ... ]
     */
    final public static function getDefaultConfig()
    {
        $fullName = get_called_class();
        $shortName = ($pos = strrpos($fullName, '\\')) ? substr($fullName, $pos + 1) : $fullName;
        $defaults = static::$defaultConfiguration;
        $defaults['configured_class'] = $fullName;
        $defaults['type'] = 'extension';
        if (is_a($defaults['configured_class'], 'Xoops\Core\Text\Sanitizer\FilterAbstract', true)) {
            $defaults['type'] = 'filter';
        } elseif (is_a($defaults['configured_class'], 'Xoops\Core\Text\Sanitizer', true)) {
            $defaults['type'] = 'sanitizer';
        }
        if (!array_key_exists('enabled', $defaults)) {
            $defaults['enabled'] = false;
        }
        $return[strtolower($shortName)] = $defaults;

        return $return;
    }
}

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
 * Abstract class for sanitizer filters
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Goghs Cheng (http://www.eqiao.com, http://www.devbeez.com/)
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class FilterAbstract extends SanitizerComponent
{
    /**
     * apply filter to a text string
     *
     * @param string $text string to filter
     *
     * @return string
     */
    abstract public function applyFilter($text);
}

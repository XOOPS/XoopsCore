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
use Xoops\Core\Text\ShortCodes;

/**
 * XOOPS Text/Sanitizer/SanitizerComponent - extension, filter
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class SanitizerComponent extends SanitizerConfigurable
{
    /**
     * @var Sanitizer
     */
    protected $ts;

    /**
     * @var ShortCodes
     */
    protected $shortcodes;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param Sanitizer $ts text sanitizer instance being extended
     */
    public function __construct(Sanitizer $ts)
    {
        $this->ts = $ts;
        $fullName = get_called_class();
        $shortName = ($pos = strrpos($fullName, '\\')) ? substr($fullName, $pos + 1) : $fullName;
        $this->config = $ts->getConfig($shortName);
        $this->shortcodes = $ts->getShortCodesInstance();
    }
}

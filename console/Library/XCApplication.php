<?php

namespace XoopsConsole\Library;

/**
 * A really simple container
 *
 * @category  XoopsConsole\Library
 * @package   SimpleContainer
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class XCApplication extends \Symfony\Component\Console\Application
{
    /**
     * @var SimpleContainer
     */
    public $XContainer = null;
}

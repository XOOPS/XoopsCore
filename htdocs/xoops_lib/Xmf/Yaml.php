<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf;

/**
 * Yaml dump and parse methods
 *
 * YAML is a serialization format most useful when human readability
 * is a consideration. It can be useful for configuration files, as
 * well as import and export functions.
 *
 * This file is a front end for a separate YAML package present in the
 * vendor directory. The intent is to provide a consistent interface
 * no mater what underlying library is actually used.
 *
 * At present, this class expects the symfony/yaml package.
 *
 * @category  Xmf\Module\Yaml
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @see       http://www.yaml.org/
 * @since     1.0
 */
class Yaml extends \Xoops\Core\Yaml
{
    // This class has been superceded by Xoops\Core\Yaml.
    // This stub remains for compatibility with Xmf for Xoops 2.5 series.
}

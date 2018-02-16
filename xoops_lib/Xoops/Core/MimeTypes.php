<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core;

use Dflydev\ApacheMimeTypes\PhpRepository;

/**
 * MimeTypes
 *
 * YProvide translation from file extension to mimetype and back.
 *
 * At present, this class expects the symfony/yaml package.
 *
 * @category  Xoops\Core\MimeTypes
 * @package   MimeTypes
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @see       https://github.com/dflydev/dflydev-apache-mime-types
 * @since     1.0
 */
class MimeTypes
{
    /**
     * findExtensions - given a mimetype, get array of possible file extensions
     *
     * @param string $type mimetype
     *
     * @return array of applicable extensions, empty if no match
     */
    public static function findExtensions($type)
    {
        $mt = new PhpRepository();
        return $mt->findExtensions($type);
    }

    /**
     * findType - given a file extensions, return applicable mimetype
     *
     * @param string $extension file extension
     *
     * @return string|null applicable mimetype (string) or null if no match
     */
    public static function findType($extension)
    {
        $mt = new PhpRepository();
        return $mt->findType(strtolower($extension));
    }
}

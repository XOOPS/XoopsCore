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

use Xoops\Form\OptionElement;

/**
 * ImageFiles - provide list of image file names from a directory
 *
 * @category  Xoops\Core\Lists\ImageFiles
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SubjectIcon extends ListAbstract
{
    /**
     * gets list of image file names in a directory
     *
     * @param string $subDirectory subdirectory (deprecated)
     *
     * @return array
     */
    public static function getList($subDirectory = '')
    {
        $xoops = \Xoops::getInstance();
        $subDirectory = trim($subDirectory, '/');
        $path = 'images/subject/' . $subDirectory;
        $subjects = ImageFile::getList($xoops->path($path), $subDirectory . '/');

        return $subjects;
    }

    /**
     * add list to a Xoops\Form\OptionElement
     *
     * @param OptionElement $element      Form element to add options to
     * @param string        $subDirectory subdirectory (deprecated)
     *
     * @return void
     */
    public static function setOptionsArray(OptionElement $element, $subDirectory = '')
    {
        $xoops = \Xoops::getInstance();
        $subjects = static::getList($subDirectory);
        foreach (array_keys($subjects) as $name) {
            $element->addOption(
                $name,
                '<img src="' . $xoops->url('images/subject/') . $name . '" alt="' . $name . '" />'
            );
        }
    }
}

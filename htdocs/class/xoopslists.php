<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS listing utilities
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     class
 * @since       2.0.0
 * @version     $Id$
 */

/**
 * XoopsLists
 *
 * @author     John Neill <catzwolf@xoops.org>
 * @copyright  2011-2015 copyright (c) XOOPS.org
 * @package    Xoops\Core
 * @subpackage Lists
 * @access public
 */
class XoopsLists
{
    /**
     * get list of timezones
     *
     * @return array
     */
    public static function getTimeZoneList()
    {
        return \Xoops\Core\Lists\TimeZone::getList();
    }

    /**
     * gets list of themes folder from themes directory
     *
     * @return array
     */
    public static function getThemesList()
    {
        return \Xoops\Core\Lists\Theme::getList();
    }

    /**
     * gets a list of module folders from the modules directory
     *
     * @return array
     */
    public static function getModulesList()
    {
        return \Xoops\Core\Lists\Module::getList();
    }

    /**
     * gets list of editors
     *
     * @return array
     */
    public static function getEditorList()
    {
        return \Xoops\Core\Lists\Editor::getList();
    }

    /**
     * gets list of name of directories inside a directory
     *
     * @param string $path filesystem path
     *
     * @return array
     */
    public static function getDirListAsArray($path)
    {
        $ignored = array('cvs', '_darcs');
        return \Xoops\Core\Lists\Directory::getList($path, $ignored);
    }

    /**
     * gets list of all files in a directory
     *
     * @param string $path   filesystem path
     * @param string $prefix prefix added to file names
     *
     * @return array
     */

    public static function getFileListAsArray($path, $prefix = '')
    {
        return \Xoops\Core\Lists\File::getList($path, $prefix);
    }

    /**
     * gets list of image file names in a directory
     *
     * @param string $path   filesystem path
     * @param string $prefix prefix added to file names
     *
     * @return array
     */
    public static function getImgListAsArray($path, $prefix = '')
    {
        return \Xoops\Core\Lists\ImageFile::getList($path, $prefix);
    }

    /**
     * gets list of html file names in a certain directory
     *
     * @param string $path   filesystem path
     * @param string $prefix prefix added to file names
     *
     * @return array
     */
    public static function getHtmlListAsArray($path, $prefix = '')
    {
        return \Xoops\Core\Lists\HtmlFile::getList($path, $prefix);
    }

    /**
     * gets list of subject icon image file names in a certain directory
     * if directory is not specified, default directory will be searched
     *
     * @param string $subDirectory
     *
     * @return array
     */
    public static function getSubjectsList($subDirectory = '')
    {
        return \Xoops\Core\Lists\SubjectIcon::getList($subDirectory);
    }

    /**
     * gets list of language folders inside default language directory
     *
     * @return array
     */
    public static function getLangList()
    {
        $lang_list = XoopsLists::getDirListAsArray(\XoopsBaseConfig::get('root-path') . '/language/');

        return $lang_list;
    }

    /**
     * gets list of locale folders inside default language directory
     *
     * @param boolean $showInCodeLanguage true to show a code's name in the language the code represents
     *
     * @return array
     */
    public static function getLocaleList($showInCodeLanguage = false)
    {
        return \Xoops\Core\Lists\Locale::getList($showInCodeLanguage);
    }

    /**
     * XoopsLists::getCountryList()
     *
     * @return array
     */
    public static function getCountryList()
    {
        return \Xoops\Core\Lists\Country::getList();
    }

    /**
     * Get a list of localized month names
     *
     * @param string $width The format name; it can be 'wide' (eg 'January'),
     *                      'abbreviated' (eg 'Jan') or 'narrow' (eg 'J').
     *
     * @return string Returns an empty string if $value is empty, the name of the month otherwise.
     */
    public static function getMonthList($width = 'wide')
    {
        return \Xoops\Core\Lists\Month::getList($width);
    }
}

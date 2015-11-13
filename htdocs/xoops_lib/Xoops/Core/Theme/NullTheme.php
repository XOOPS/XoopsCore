<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Theme;

/**
 * A null theme, mainly for testing
 *
 * @category  Xoops\Core\Theme
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class NullTheme extends XoopsTheme
{
    /**
     * Initializes this theme
     *
     * @return bool
     */
    public function xoInit()
    {
        return true;
    }

    /**
     * Render the page
     *
     * @return bool
     */
    public function render()
    {
        return true;
    }

    /**
     * Add StyleSheet or CSS code to the document head
     *
     * @return void
     */
    public function addStylesheet()
    {
    }

    /**
     * addScriptAssets - add a list of scripts to the page
     *
     * @return void
     */
    public function addScriptAssets()
    {
    }

    /**
     * addStylesheetAssets - add a list of stylesheets to the page
     *
     * @return void
     */
    public function addStylesheetAssets()
    {
    }

    /**
     * addBaseAssets - add a list of assets to the page, these will all
     * be combined into a single asset file at render time
     *
     * @return void
     */
    public function addBaseAssets()
    {
    }

    /**
     * addBaseScriptAssets - add a list of scripts to the page
     *
     * @return void
     */
    public function addBaseScriptAssets()
    {
    }

    /**
     * addBaseStylesheetAssets - add a list of stylesheets to the page
     *
     * @return void
     */
    public function addBaseStylesheetAssets()
    {
    }

    /**
     * setNamedAsset - Add an asset reference to the asset manager.
     *
     * @return boolean true if asset registers, false on error
     */
    public function setNamedAsset()
    {
    }
}

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
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
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
     * @param null|mixed $canvasTpl
     * @param null|mixed $pageTpl
     * @param null|mixed $contentTpl
     * @param mixed $vars
     * @return bool
     */
    public function render($canvasTpl = null, $pageTpl = null, $contentTpl = null, $vars = [])
    {
        return true;
    }

    /**
     * Add StyleSheet or CSS code to the document head
     *
     * @param mixed $src
     * @param mixed $attributes
     * @param mixed $content
     * @return void
     */
    public function addStylesheet($src = '', $attributes = [], $content = '')
    {
    }

    /**
     * addScriptAssets - add a list of scripts to the page
     *
     * @param mixed $assets
     * @param mixed $filters
     * @param null|mixed $target
     * @return void
     */
    public function addScriptAssets($assets, $filters = 'default', $target = null)
    {
    }

    /**
     * addStylesheetAssets - add a list of stylesheets to the page
     *
     * @param mixed $assets
     * @param mixed $filters
     * @param null|mixed $target
     * @return void
     */
    public function addStylesheetAssets($assets, $filters = 'default', $target = null)
    {
    }

    /**
     * addBaseAssets - add a list of assets to the page, these will all
     * be combined into a single asset file at render time
     *
     * @param mixed $type
     * @param mixed $assets
     * @return void
     */
    public function addBaseAssets($type, $assets)
    {
    }

    /**
     * addBaseScriptAssets - add a list of scripts to the page
     *
     * @param mixed $assets
     * @return void
     */
    public function addBaseScriptAssets($assets)
    {
    }

    /**
     * addBaseStylesheetAssets - add a list of stylesheets to the page
     *
     * @param mixed $assets
     * @return void
     */
    public function addBaseStylesheetAssets($assets)
    {
    }

    /**
     * setNamedAsset - Add an asset reference to the asset manager.
     *
     * @param mixed $name
     * @param mixed $assets
     * @param null|mixed $filters
     * @return bool true if asset registers, false on error
     */
    public function setNamedAsset($name, $assets, $filters = null)
    {
    }
}

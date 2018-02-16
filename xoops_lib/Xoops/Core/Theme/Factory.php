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
 * XoopsTheme component class file
 *
 * @category  Xoops\Core
 * @package   Theme
 * @author    Skalpa Keo <skalpa@xoops.org>
 * @copyright 2008-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Factory
{
    /**
     * @var string
     */
    public $xoBundleIdentifier = 'XoopsThemeFactory';

    /**
     * Currently enabled themes (if empty, all the themes in themes/ are allowed)
     *
     * @var array
     */
    public $allowedThemes = array();

    /**
     * Default theme to instantiate if none specified
     *
     * @var string
     */
    public $defaultTheme = 'default';

    /**
     * If users are allowed to choose a custom theme
     *
     * @var bool
     */
    public $allowUserSelection = true;

    /**
     * Instantiate the specified theme
     *
     * @param array $options options array
     *
     * @return XoopsTheme
     */
    public function createInstance($options = array())
    {
        $xoops = \Xoops::getInstance();
        // Grab the theme folder from request vars if present
        if (empty($options['folderName'])) {
            if (($req = @$_REQUEST['xoops_theme_select']) && $this->isThemeAllowed($req)) {
                $options['folderName'] = $req;
                if (isset($_SESSION) && $this->allowUserSelection) {
                    $_SESSION[$this->xoBundleIdentifier]['defaultTheme'] = $req;
                }
            } else {
                if (isset($_SESSION[$this->xoBundleIdentifier]['defaultTheme'])) {
                    $options['folderName'] = $_SESSION[$this->xoBundleIdentifier]['defaultTheme'];
                } else {
                    if (empty($options['folderName']) || !$this->isThemeAllowed($options['folderName'])) {
                        $options['folderName'] = $this->defaultTheme;
                    }
                }
            }
            $xoops->setConfig('theme_set', $options['folderName']);
        }
        $options['path'] = \XoopsBaseConfig::get('themes-path') . '/' . $options['folderName'];
        $inst = new XoopsTheme();
        foreach ($options as $k => $v) {
            $inst->$k = $v;
        }
        $inst->xoInit();
        return $inst;
    }

    /**
     * Checks if the specified theme is enabled or not
     *
     * @param string $name theme name
     *
     * @return bool
     */
    public function isThemeAllowed($name)
    {
        return (empty($this->allowedThemes) || in_array($name, $this->allowedThemes));
    }
}

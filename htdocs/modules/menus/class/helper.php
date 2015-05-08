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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Menus extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('menus');
    }

    /**
     * @return Menus
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return MenusMenusHandler
     */
    public function getHandlerMenus()
    {
        return $this->getHandler('menus');
    }

    /**
     * @return MenusMenuHandler
     */
    public function getHandlerMenu()
    {
        return $this->getHandler('menu');
    }

    /**
     * @param string $skin
     * @param bool $skin_from_theme
     *
     * @return array
     */
    public function getSkinInfo($skin, $skin_from_theme = false)
    {
        $error = false;
        $path = '';
        if ($skin_from_theme) {
            $path = "themes/" . $this->xoops()->getConfig('theme_set') . "/menu";
            if (!XoopsLoad::fileExists($this->xoops()->path("{$path}/skin_version.php"))) {
                $error = true;
            }
        }

        if ($error || !$skin_from_theme) {
            $path = "modules/menus/skins/{$skin}";
        }

        $file = $this->xoops()->path("{$path}/skin_version.php");
        $info = array();

        if (XoopsLoad::fileExists($file)) {
            include $file;
            $info =& $skinversion;
        }

        $info['path'] = $this->xoops()->path($path);
        $info['url'] = $this->xoops()->url($path);

        if (!isset($info['template'])) {
            $info['template'] = $this->xoops()->path("modules/menus/templates/block.tpl");
        } else {
            $info['template'] = $this->xoops()->path("{$path}/" . $info['template']);
        }

        if (!isset($info['prefix'])) {
            $info['prefix'] = $skin;
        }

        if (isset($info['css'])) {
            $info['css'] = (array)$info['css'];
            foreach ($info['css'] as $key => $value) {
                $info['css'][$key] = $this->xoops()->url("{$path}/{$value}");
            }
        }

        if (isset($info['js'])) {
            $info['js'] = (array)$info['js'];
            foreach ($info['js'] as $key => $value) {
                $info['js'][$key] = $this->xoops()->url("{$path}/{$value}");
            }
        }

        if (!isset($info['config'])) {
            $info['config'] = array();
        }

        return $info;
    }
}

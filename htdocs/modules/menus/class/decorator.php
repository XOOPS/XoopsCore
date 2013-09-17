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
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class MenusDecorator
{
    /**
     * @param string $dirname
     *
     * @return bool
     */
    static function getDecorators($dirname)
    {
        $available = self::getAvailableDecorators();
        if (!in_array($dirname, array_keys($available))) {
            return false;
        }

        return $available[$dirname];
    }

    /**
     * @return array
     */
    static function getAvailableDecorators()
    {
        static $decorators = false;
        if (!is_array($decorators)) {
            $decorators = array();
            $helper = Menus::getInstance();

            $dirnames = XoopsLists::getDirListAsArray($helper->path('decorators/'), '');
            foreach ($dirnames as $dirname) {
                if (XoopsLoad::loadFile($helper->path("decorators/{$dirname}/decorator.php"))) {
                    $className = 'Menus' . ucfirst($dirname) . 'Decorator';
                    $class = new $className($dirname);
                    if ($class instanceof MenusDecoratorAbstract && $class instanceof MenusDecoratorInterface) {
                        $decorators[$dirname] = $class;
                    }
                }
            }
        }

        return $decorators;
    }
}

class MenusDecoratorAbstract
{
    /**
     * @param string $dirname
     */
    public function __construct($dirname)
    {
        $this->loadLanguage($dirname);
    }

    public function loadLanguage($name)
    {
        $helper = Menus::getInstance();

        $language =  XoopsLocale::getLegacyLanguage();
        $path = $helper->path("decorators/{$name}/language");
        if (!$ret = XoopsLoad::loadFile("{$path}/{$language}/decorator.php")) {
            $ret = XoopsLoad::loadFile("{$path}/english/decorator.php");
        }

        return $ret;
    }
}

interface MenusDecoratorInterface
{
    function start();

    function end(&$menus);

    function decorateMenu(&$menu);

    function hasAccess($menu, &$hasAccess);

    function accessFilter(&$accessFilter);
}

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
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class MenusDecorator
{
    /**
     * @param string $dirname
     *
     * @return bool
     */
    public static function getDecorators($dirname)
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
    public static function getAvailableDecorators()
    {
        static $decorators = false;
        if (!is_array($decorators)) {
            $decorators = [];
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

    /**
     * @param string $name
     */
    public function loadLanguage($name)
    {
        $helper = Menus::getInstance();

        $language = XoopsLocale::getLegacyLanguage();
        $path = $helper->path("decorators/{$name}/language");
        if (!$ret = XoopsLoad::loadFile("{$path}/{$language}/decorator.php")) {
            $ret = XoopsLoad::loadFile("{$path}/english/decorator.php");
        }

        return $ret;
    }
}

interface MenusDecoratorInterface
{
    /**
     * @return void
     */
    public function start();

    /**
     * @param mixed $menus
     * @return void
     */
    public function end(&$menus);

    /**
     * @param mixed $menu
     * @return void
     */
    public function decorateMenu(&$menu);

    /**
     * @param mixed $menu
     * @param mixed $hasAccess
     * @return void
     */
    public function hasAccess($menu, &$hasAccess);

    /**
     * @param mixed $accessFilter
     * @return void
     */
    public function accessFilter(&$accessFilter);
}

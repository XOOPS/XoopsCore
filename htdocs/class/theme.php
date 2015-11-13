<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;

/**
 * XoopsTheme component class file
 *
 * @copyright XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Skalpa Keo <skalpa@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @since     2.3.0
 * @package   class
 * @version   $Id$
 */

/**
 * XoopsThemeFactory
 *
 * @author Skalpa Keo
 * @since  2.3.0
 */
class XoopsThemeFactory extends \Xoops\Core\Theme\Factory
{
}

/**
 * XoopsAdminThemeFactory
 *
 * @author Andricq Nicolas (AKA MusS)
 * @author trabis
 * @since  2.4.0
 */
class XoopsAdminThemeFactory extends \Xoops\Core\Theme\AdminFactory
{
}

class XoopsTheme extends \Xoops\Core\Theme\XoopsTheme
{
}

abstract class XoopsThemePlugin extends \Xoops\Core\Theme\PluginAbstract
{
}

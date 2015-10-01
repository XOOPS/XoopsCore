<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Handler\Scheme;

use Xoops\Core\Exception\NoHandlerException;
use Xoops\Core\Handler\FactorySpec;
use Xoops\Core\Kernel\XoopsObjectHandler;


/**
 * Kernel - build
 *
 * @category  Xoops\Core\Handler\Scheme
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Kernel implements SchemeInterface
{
    private $lookupTable = [
        'block'           => 'Block',
        'blockmodulelink' => 'BlockModuleLink',
        'config'          => 'Config',
        'configitem'      => 'ConfigItem',
        'configoption'    => 'ConfigOption',
        'group'           => 'Group',
        'groupperm'       => 'GroupPerm',
        'member'          => 'Member',
        'membership'      => 'Membership',
        'module'          => 'Module',
        'online'          => 'Online',
        'privmessage'     => 'PrivateMessage',
        'ranks'           => 'Ranks',
        'tplfile'         => 'TplFile',
        'tplset'          => 'TplSet',
        'user'            => 'User',
    ];

    /**
     * build a kernel handler
     *
     * @param FactorySpec $spec specification for requested handler
     *
     * @return XoopsObjectHandler|null
     */
    public function build(FactorySpec $spec)
    {
        $handler = null;
        $specName = strtolower($spec->getName());
        if (!isset($this->lookupTable[$specName])) {
            if (false === $spec->getOptional()) {
                throw new NoHandlerException(sprintf('Unknown handler %s', $specName));
            }
            return $handler;
        }

        $name = $this->lookupTable[$specName];
        $class = '\Xoops\Core\Kernel\Handlers\Xoops' . $name . 'Handler';
        if (class_exists($class)) {
            $handler = new $class($spec->getFactory()->db());
        }
        if ($handler === null) {
            if (false === $spec->getOptional()) {
                throw new NoHandlerException(sprintf('Class not found %s', $class));
            }
        }
        return $handler;
    }
}

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
 * FQN - build
 *
 * @category  Xoops\Core\Handler\Scheme
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class FQN implements SchemeInterface
{
    /**
     * build a handler from a fully qualified class name
     *
     * @param FactorySpec $spec specification for requested handler
     *
     * @return XoopsObjectHandler|null
     */
    public function build(FactorySpec $spec)
    {
        $handler = null;
        $class = $spec->getName();
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

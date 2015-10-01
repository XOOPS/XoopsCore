<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Handler;

use Xoops\Core\Database\Connection;
use Xoops\Core\Exception\InvalidHandlerSpecException;
use Xoops\Core\Exception\NoHandlerException;
use Xoops\Core\Handler\Scheme\SchemeInterface;
use Xoops\Core\Kernel\XoopsObjectHandler;

/**
 * Factory to build handlers
 *
 * @category  Xoops\Core\Handler\Factory
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Factory
{
    /**
     * @var Connection
     */
    private static $db;

    /**
     * @var Factory The reference to *Singleton* instance of this class
     */
    private static $instance;

    private $schemes;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Factory the singleton instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
            static::$db = \Xoops::getInstance()->db();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        $this->schemes = [
            'fqn'    => '\Xoops\Core\Handler\Scheme\FQN',
            'kernel' => '\Xoops\Core\Handler\Scheme\Kernel',
            'legacy' => '\Xoops\Core\Handler\Scheme\LegacyModule',
        ];
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * creates a new object
     *
     * @return XoopsObjectHandler handler object
     *
     * @throws InvalidHandlerSpecException
     * @throws NoHandlerException
     */

    /**
     * @param string      $name     Handler name
     *                                - well known kernel handler name
     *                                - FQN of handler class, PSR4 loadable
     *                                - Full class name, to be located in a module namespace
     *                                - legacy class file name (no extension) in module/dirname/class
     *                                - a registered scheme name, i.e. scheme:classname
     * @param string|null $dirname
     * @param bool        $optional
     *
     * @return XoopsObjectHandler|null
     */
    public function create($name, $dirname = null, $optional = false)
    {
        // have colon assume form of scheme:name
        $foundColon = strpos($name, ':');
        if ($foundColon !== false) {
            $scheme = substr($name, 0, $foundColon);
            $baseName = substr($name, $foundColon+1);
            $handler = $this->newSpec()->scheme($scheme)->name($baseName)->optional($optional)->build();
            return $handler;
        }

        // have namespace separator, assume fully qualified name
        $foundNS = (false !== strpos($name, '\\'));
        if ($foundNS) {
            $handler = $this->newSpec()->scheme('fqn')->name($name)->optional($optional)->build();
            return $handler;
        }

        // no dirname, assume kernel class
        if ($dirname === null) {
            $handler = $this->newSpec()->scheme('kernel')->name($name)->optional($optional)->build();
            return $handler;
        }

        // must be module handler
        $handler = $this->newSpec()->scheme('legacy')->name($name)->dirname($dirname)->optional($optional)->build();
        return $handler;
    }

    /**
     * @param string $name      scheme name
     * @param string $className fully qualified name of class that implements the scheme.
     *                           this class must implement the SchemeInterface
     */
    public function registerScheme($name, $className)
    {
        $this->schemes[strtolower($name)] = $className;
    }

    /**
     * @return FactorySpec
     */
    public static function newSpec()
    {
        $instance = Factory::getInstance();
        $spec = FactorySpec::getInstance($instance);
        return $spec;
    }

    /**
     * @param FactorySpec $spec specification
     * @return SchemeInterface
     */
    private function getSchemeObject(FactorySpec $spec)
    {
        $schemeName = $this->schemes[$spec->getScheme()];
        $scheme = null;
        if (class_exists($schemeName)) {
            $scheme = new $schemeName;
        }

        if (!($scheme instanceof SchemeInterface)) {
            throw new InvalidHandlerSpecException(sprintf('Unknown scheme %s', $schemeName));
        }
        return $scheme;
    }

    /**
     * Build dispatches the appropriate scheme class to instantiate a
     * handler based on the specification
     *
     * @param FactorySpec $spec specification for requested handler
     *
     * @return XoopsObjectHandler handler object
     *
     * @throws InvalidHandlerSpecException
     * @throws NoHandlerException
     */
    public function build(FactorySpec $spec)
    {
        $scheme = $this->getSchemeObject($spec);
        if ($scheme === null) {
            return null;
        }

        $handler = $scheme->build($spec);
        return $handler;
    }

    /**
     * @return Connection
     */
    public function db()
    {
        return static::$db;
    }
}

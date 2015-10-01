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

use Xoops\Core\Kernel\XoopsObjectHandler;

/**
 * HandlerFactory
 *
 * @category  Xoops\Core\Handler\FactorySpec
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class FactorySpec
{
    protected $factory;

    protected $specScheme;
    protected $specName;
    protected $specDirname;
    protected $specOptional = false;
    protected $specFQN;

    /**
     * get a new specification instance.
     *
     * Usually called by the Handler Factory newSpec() operation instead of direct
     * @param Factory $factory factory that created the spec
     *
     * @return FactorySpec
     */
    public static function getInstance(Factory $factory)
    {
        $specClass = get_called_class();
        $instance = new $specClass($factory);

        return $instance;
    }

    /**
     * @param Factory $factory factory that created the spec
     */
    protected function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Set Scheme
     *
     * @param string $value
     *
     * @return FactorySpec $this for fluent use
     */
    public function scheme($value)
    {
        $this->specScheme = $value;
        return $this;
    }

    /**
     * Set Name
     *
     * @param string $value
     *
     * @return FactorySpec $this for fluent use
     */
    public function name($value)
    {
        $this->specName = $value;
        return $this;
    }

    /**
     * Set Dirname
     *
     * @param string $value
     *
     * @return FactorySpec $this for fluent use
     */
    public function dirname($value)
    {
        $this->specDirname = $value;
        return $this;
    }

    /**
     * Set Optional
     *
     * @param boolean $value
     *
     * @return FactorySpec $this for fluent use
     */
    public function optional($value)
    {
        $this->specOptional = (bool) $value;
        return $this;
    }

    /**
     * Set FQN
     *
     * @param string $value
     *
     * @return FactorySpec $this for fluent use
     */
    public function fqn($value)
    {
        $this->specFQN = $value;
        return $this;
    }

    /**
     * request build from factory
     *
     * @return XoopsObjectHandler|null
     */
    public function build()
    {
        return $this->factory->build($this);
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->specScheme;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->specName;
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->specDirname;
    }

    /**
     * @return bool
     */
    public function getOptional()
    {
        return $this->specOptional;
    }

    /**
     * @return string
     */
    public function getFQN()
    {
        return $this->specFQN;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}

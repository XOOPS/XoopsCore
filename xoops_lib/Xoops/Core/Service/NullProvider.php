<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service;

/**
 * Null Service Provider object
 *
 * This provider will be used whenever there is no provider defined
 * for the service name requested. This will allow service consumers
 * to avoid handling the condition of a service not being available.
 *
 * Any calls to service methods will return NULL.
 *
 * Any read of service properties will return null, and any check for
 * isset() will return false.
 *
 * @category  Xoops\Core\Service\NullProvider
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2015 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class NullProvider extends Provider
{
    /** @var Response - a 'null' response object returned for any method calls */
    private $response = null;

    /**
     * __construct
     *
     * @param Manager $manager Manager instance
     * @param string  $service service name (case sensitive)
     */
    public function __construct(Manager $manager, $service)
    {
        $this->response = new Response();
        $this->response->setSuccess(false)->addErrorMessage(sprintf("No provider installed for %s", $service));
        parent::__construct($manager, $service);
    }

    /**
     * isAvailable - indicate the (lack of) availability of an actual provider
     *
     * @return boolean false to indicate no provider is available
     */
    public function isAvailable()
    {
        return false;
    }

    /**
     * Any property writes will go here
     *
     * @param string $name  not used
     * @param mixed  $value not used
     *
     * @return void
     */
    public function __set($name, $value)
    {
    }

    /**
     * Any property reads will go here and return null
     *
     * @param string $name not used
     *
     * @return null
     */
    public function __get($name)
    {
        return null;
    }

    /**
     * Any isset() or empty() on a property will go here and return false
     *
     * @param string $name not used
     *
     * @return false
     */
    public function __isset($name)
    {
        return false;
    }

    /**
     * Any property unset() will go here
     *
     * @param string $name not used
     *
     * @return void
     */
    public function __unset($name)
    {
    }

    /**
     * All non-static methods go here and will return null response
     *
     * @param string $name      not used
     * @param mixed  $arguments not used
     *
     * @return Response Response
     */
    public function __call($name, $arguments)
    {
        return $this->response;
    }

    /**
     * All static methods go here and will return null response
     *
     * @param string $name      not used
     * @param mixed  $arguments not used
     *
     * @return Response Response
     */
    public static function __callStatic($name, $arguments)
    {
        $response = new Response();
        $response->setSuccess(false)->addErrorMessage(sprintf("No provider installed for %s", get_called_class()));
        return $response;
    }
}

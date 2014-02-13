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
 * Xoops services manager, locate, register, choose and dispatch
 *
 * @category  Xoops\Core\Service\Manager
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class Manager
{
    /**
     * Service Mode constant - Exclusive mode where only one located service
     * will be used.
     */
    const MODE_EXCLUSIVE = 1;

    /**
     * Service Mode constant - Choice mode where one service from potentially
     * many located services will be used. The service dispatched will be selected
     * by user preference or system default.
     */
    const MODE_CHOICE = 2;

    /**
     * Service Mode constant - Multiple mode where all located services will be
     * dispatched in priority order.
     */
    const MODE_MULTIPLE  = 4;

    /**
     * Provider priorities
     */
    const PRIORITY_SELECTED = 0;
    const PRIORITY_HIGH     = 1;
    const PRIORITY_MEDIUM   = 5;
    const PRIORITY_LOW      = 9;

    /**
     * Services registry - array keyed on service name, with provider object as value
     *
     * @var array
     */
    protected $services = array();

    /**
     * __construct
     */
    protected function __construct()
    {
    }

    /**
     * Allow one instance only!
     *
     * @return Manager instance
     */
    public static function getInstance()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new Manager();
        }

        return $instance;
    }


    /**
     * registerChoice - record priority choices for service providers
     *
     * For MODE_CHOICE services, the default choice is recorded by invoking this
     * method.
     *
     * @param string $service the service name being set
     * @param array  $choices array of priorities for each of the named service providers
     *
     * @return void
     */
    public function registerChoice($service, $choices)
    {
        // not yet implemented - will record choices array  in current objects
    }

    /**
     * listChoices - list choices availabe for a named service
     *
     * For MODE_CHOICE services, this can supply an array containing the
     * available choices. This array can be used to construct a user form
     * to make a choice.
     *
     * @param string $service the service name being set
     *
     * @return array of available service provider objects for this service.
     */
    public function listChoices($service)
    {
        $providers = $this->locate()->getRegistered();
        return $providers;
    }

    /**
     * locate - create a provider object for a named service, locating all contract implementors
     *
     * @param string $service the service name being set
     *
     * @return Provider object for the requested service
     */
    public function locate($service)
    {
        if (isset($this->services[$service])) {
            // service already located
            $provider = $this->services[$service];
        } else {
            $xoops = \Xoops::getInstance();
            $provider = new Provider($this, $service);
            $event = 'core.service.locate.' . $service;
            // locate service provider(s)
            // In response to trigger message, the contract implementor should register()
            $xoops->events()->triggerEvent($event, $provider);
            // get reference to the list of providers and prioritize it.
            $registered=$provider->getRegistered();
            if (count($registered)) {
                uasort($registered, function ($a, $b) {
                    if ($a->getPriority() != $b->getPriority()) {
                        return ($a->getPriority() > $b->getPriority()) ? -1 : 1;
                    } else {
                        return 0;
                    }
                });
            } else {
                // replace with a null provider since no contract implementers were
                $provider = new NullProvider($this, $service);
            }
            $this->services[$service] = $provider;
        }

        return $provider;
    }
}

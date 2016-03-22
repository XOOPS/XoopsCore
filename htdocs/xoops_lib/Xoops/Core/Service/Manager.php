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

use Xmf\Yaml;

/**
 * Xoops services manager, locate, register, choose and dispatch
 *
 * @category  Xoops\Core\Service\Manager
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
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
     * by system default.
     */
    const MODE_CHOICE = 2;

    /**
     * Service Mode constant - Choice mode where one service from potentially many
     * located services will be used. The service dispatched will be selected by user
     * preference, or system default if no user valid preference is available.
     */
    const MODE_PREFERENCE = 4;

    /**
     * Service Mode constant - Multiple mode where all located services will be
     * dispatched in priority order.
     */
    const MODE_MULTIPLE  = 8;

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
     * Provider Preferences - array keyed on service name, where each element is
     * an array of provider name => priority entries
     *
     * @var array|null
     */
    protected $providerPrefs = null;

    /**
     * @var string config file with provider prefs
     */
    private $providerPrefsFilename = 'var/configs/system_provider_prefs.yml';

    /**
     * @var string config cache key
     */
    private $providerPrefsCacheKey = 'system/provider/prefs';

    /**
     * __construct
     */
    protected function __construct()
    {
        $this->providerPrefs = $this->readProviderPrefs();
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
     * readYamlProviderPrefs - read configured provider preferences from file
     *
     * @return array of configured provider preferences
     */
    public function readYamlProviderPrefs()
    {
        $xoops = \Xoops::getInstance();

        $providerPrefs = array();

        try {
            $file = $xoops->path($this->providerPrefsFilename);
            if (file_exists($file)) {
                $providerPrefs = Yaml::read($xoops->path($file));
            }
            if (empty($providerPrefs)) {
                $providerPrefs = array();
            }
        } catch (\Exception $e) {
            $xoops->events()->triggerEvent('core.exception', $e);
            $providerPrefs = array();
        }
        return $providerPrefs;
    }

    /**
     * readProviderPrefs - read configured provider preferences from cache
     *
     * @return array of configured provider preferences
     */
    protected function readProviderPrefs()
    {
        $xoops = \Xoops::getInstance();
        $providerPrefs = $xoops->cache()->cacheRead(
            $this->providerPrefsCacheKey,
            array($this, 'readYamlProviderPrefs')
        );
        return $providerPrefs;
    }

    /**
     * saveProviderPrefs - record array of provider preferences in config file, and
     * update cache
     *
     * @param array $providerPrefs array of provider preferences to save
     *
     * @return void
     */
    protected function saveProviderPrefs($providerPrefs)
    {
        if (is_array($providerPrefs)) {
            $xoops = \Xoops::getInstance();
            try {
                Yaml::save($providerPrefs, $xoops->path($this->providerPrefsFilename));
                $xoops->cache()->write($this->providerPrefsCacheKey, $providerPrefs);
            } catch (\Exception $e) {
                $xoops->events()->triggerEvent('core.exception', $e);
            }
        }
    }

    /**
     * saveChoice - record priority choices for service providers
     *
     * This registers a permanent choice (i.e. setting system default) that will
     * persist after the lifetime of this service manager.
     *
     * @param string $service the service name being set
     * @param array  $choices array of priorities for each of the named service providers
     *
     * @return void
     */
    public function saveChoice($service, $choices)
    {
        // read current preferences
        $prefs = $this->readProviderPrefs();
        // replace prefs for selected service
        $prefs[$service] = $choices;
        // save the changes
        $this->saveProviderPrefs($prefs);
        // apply to current manager instance
        $this->registerChoice($service, $choices);
    }

    /**
     * registerChoice - record priority choices for service providers
     *
     * This registers a temporary choice (i.e. applying user preferences) for the
     * lifetime of this service manager only.
     *
     * @param string $service the service name being set
     * @param array  $choices array of priorities for each of the named service providers
     *
     * @return void
     */
    public function registerChoice($service, $choices)
    {
        $provider = $this->locate($service);
        $providers = $provider->getRegistered();
        foreach ($providers as $p) {
            $name = strtolower($p->getName());
            if (isset($choices[$name])) {
                $p->setPriority($choices[$name]);
            }
        }
        $provider->sortProviders();
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
        $providers = $this->locate($service)->getRegistered();
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
        $service = strtolower($service);
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
                $choices = isset($this->providerPrefs[$service]) ? $this->providerPrefs[$service] : array();
                foreach ($registered as $p) {
                    $name = strtolower($p->getName());
                    if (isset($choices[$name])) {
                        $p->setPriority($choices[$name]);
                    }
                }
                $provider->sortProviders();
            } else {
                // replace with a null provider since no contract implementers were
                $provider = new NullProvider($this, $service);
            }
            $this->services[$service] = $provider;
        }

        return $provider;
    }
}

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Text\Sanitizer;

use Xoops\Core\Lists\File;

/**
 * Derive default configuration for all sanitizer extensions
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DefaultConfiguration extends ConfigurationAbstract
{
    /**
     * The system/modules key paths are cleared on module update/install. Since modules
     * can add extensions, we will store our defaults there so we will pick up changes.
     *
     * @var string cache key for defaults
     */
    protected $cacheKey = 'system/modules/sanitizer-defaults';

    /**
     * Get defaults to initialize
     */
    public function __construct()
    {
        $xoops = \Xoops::getInstance();
        //$xoops->cache()->delete($this->cacheKey);
        //\Xmf\Debug::startTimer('sanitizer-defaults');
        $sanitizerConfiguration = $xoops->cache()->cacheRead(
            $this->cacheKey,
            array($this, 'buildDefaultConfiguration')
        );
        parent::__construct($sanitizerConfiguration);
        //\Xmf\Debug::stopTimer('sanitizer-defaults');
    }

    /**
     * Ask each sanitizer extension for default configuration
     *
     * @return array
     */
    public function buildDefaultConfiguration()
    {
        $this->registerComponent(\Xoops\Core\Text\Sanitizer::getDefaultConfig());
        $extensions = File::getList(__DIR__ . '/Extensions');
        foreach ($extensions as $extensionFile) {
            if (substr($extensionFile, -4) === '.php') {
                $class =  __NAMESPACE__ . '\Extensions\\' . substr($extensionFile, 0, -4);
                if (is_a($class, 'Xoops\Core\Text\Sanitizer\SanitizerConfigurable', true)) {
                    $this->registerComponent($class::getDefaultConfig());
                }
            }
        }

        /**
         * Register any 3rd party extensions
         *
         * Listeners will be passed a Configuration object as the single argument, and should
         * call $arg->registerComponent() to register extensions
         *
         * All extensions must implement SanitizerConfigurable, extending either ExtensionAbstract
         * or FilterAbstract, and MUST autoload
         *
         * NB: Extensions and Filters all share the same configuration space, so a 3rd party
         * extension that has the same short name as system extension will override the system
         * supplied one.
         */
        \Xoops::getInstance()->events()->triggerEvent('core.sanitizer.configuration.defaults', $this);

        return (array) $this;
    }

    /**
     * Add a component (i.e extension or filter) to the configuration with default values
     *
     * @param array $configArray extension configuration
     */
    public function registerComponent($configArray)
    {
        if (is_array($configArray)) {
            foreach ($configArray as $key => $config) {
                if (isset($config['configured_class']) &&
                    is_a($config['configured_class'], 'Xoops\Core\Text\Sanitizer\SanitizerConfigurable', true)
                ) {
                    $this->set($key, $config);
                }
            }
        }
    }
}

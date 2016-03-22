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

use Xmf\Yaml;

/**
 * Provide a standard mechanism for a runtime registry for key/value pairs, useful
 * for attributes and parameters.
 *
 * @category  Sanitizer
 * @package   Xoops\Core\Text
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Configuration extends ConfigurationAbstract
{
    /**
     * @var string config file with sanitizer prefs
     */
    private $sanitizerPrefsFilename = 'var/configs/system_sanitizer_prefs.yml';

    /**
     * Get the sanitizer configuration.
     */
    public function __construct()
    {
        $sanitizerConfiguration = $this->readSanitizerPreferences();
        parent::__construct($sanitizerConfiguration);
    }

    /**
     * readSanitizerPreferences - read configured sanitizer preferences
     *
     * If configuration file does not exist, create it.
     *
     * If configurable extensions exist that are not in the configuration
     * file, add them, and rewrite the configuration file.
     *
     * @return array of sanitizer preferences
     */
    protected function readSanitizerPreferences()
    {
        $xoops = \Xoops::getInstance();

        $sanitizerPrefs = array();

        try {
            $file = $xoops->path($this->sanitizerPrefsFilename);
            $sanitizerPrefs = Yaml::read($file);
        } catch (\Exception $e) {
            $xoops->events()->triggerEvent('core.exception', $e);
        }
        if (!is_array($sanitizerPrefs)) {
            $sanitizerPrefs = array();
        }
        $changed = false;
        $defaultPrefs = new DefaultConfiguration();
        foreach ($defaultPrefs as $name => $value) {
            if (!array_key_exists($name, $sanitizerPrefs)) {
                $sanitizerPrefs[$name] = $defaultPrefs[$name];
                $changed = true;
            }
        }
        if ($changed) {
            $this->saveSanitizerPrefrences($sanitizerPrefs);
        }
        return $sanitizerPrefs;
    }

    /**
     * saveSanitizerPreferences - record array of sanitizer preferences in config file
     *
     * @param array $sanitizerPrefs array of sanitizer preferences to save
     *
     * @return void
     */
    protected function saveSanitizerPrefrences($sanitizerPrefs)
    {
        if (is_array($sanitizerPrefs)) {
            $xoops = \Xoops::getInstance();
            try {
                Yaml::save($sanitizerPrefs, $xoops->path($this->sanitizerPrefsFilename));
            } catch (\Exception $e) {
                $xoops->events()->triggerEvent('core.exception', $e);
            }
        }
    }
}

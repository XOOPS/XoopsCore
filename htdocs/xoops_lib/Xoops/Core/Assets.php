<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core;

use Assetic\AssetManager;
use Assetic\FilterManager;
use Assetic\Filter;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\LazyAssetManager;
use Assetic\Factory\Worker\CacheBustingWorker;
use Assetic\AssetWriter;

/**
 * Provides a standarized asset strategy
 *
 * @category  Assets
 * @package   Assets
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class Assets
{
    /**
     * @var boolean
     */
    private $debug = false;

    /**
     * @var array
     */
    private $default_filters = array(
            'css' => 'cssimport,cssembed,?cssmin',
            'js'  => '?jsmin',
    );

    /**
     * @var array
     */
    private $default_output = array(
            'css' => 'css/*.css',
            'js'  => 'js/*.js',
    );

    /**
     * getUrlToAssets
     *
     * Create an asset file from a list of assets
     *
     * @param string $type    type of asset, css or js
     * @param array  $assets  list of source files to process
     * @param string $filters comma separated list of filters
     * @param string $target  target path, will default to assets directory
     *
     * @return string URL to asset file
     */
    public function getUrlToAssets($type, $assets, $filters = 'default', $target = null)
    {

        if ($filters=='default') {
            if (isset($this->default_filters[$type])) {
                $filters = $this->default_filters[$type];
            } else {
                $filters = '';
            }
        }

        if (empty($filters)) {
            $filters = array();
        } else {
            $filters = explode(',', str_replace(' ', '', $filters));
        }

        if (isset($this->default_output[$type])) {
            $output = $this->default_output[$type];
        } else {
            $output = '';
        }

        $xoops = \Xoops::getInstance();

        if (isset($target)) {
            $target_path = $target;
        } else {
            $target_path = $xoops->path('assets');
        }

        $target_url = $xoops->url($target_path);

        try {
            $am = new AssetManager();
            $fm = new FilterManager();

            foreach ($filters as $filter) {
                switch (ltrim($filter, '?')) {
                    case 'cssembed':
                        $fm->set('cssembed', new Filter\PhpCssEmbedFilter());
                        break;
                    case 'cssmin':
                        $fm->set('cssmin', new Filter\CssMinFilter());
                        break;
                    case 'cssimport':
                        $fm->set('cssimport', new Filter\CssImportFilter());
                        break;
                    case 'cssrewrite':
                        $fm->set('cssrewrite', new Filter\CssRewriteFilter());
                        break;
                    case 'lessphp':
                        $fm->set('lessphp', new Filter\LessphpFilter());
                        break;
                    case 'scssphp':
                        $fm->set('scssphp', new Filter\ScssphpFilter());
                        break;
                    case 'jsmin':
                        $fm->set('jsmin', new Filter\JSMinFilter());
                        break;
                    default:
                        throw new \Exception(sprintf('%s filter not implemented.', $filter));
                        break;
                }
            }

            // Factory setup
            $factory = new AssetFactory($target_path);
            $factory->setAssetManager($am);
            $factory->setFilterManager($fm);
            $factory->setDefaultOutput($output);
            $factory->setDebug($this->debug);
            $lam = new LazyAssetManager($factory);
            $factory->addWorker(new CacheBustingWorker($lam));

            // Prepare the assets writer
            $writer = new AssetWriter($target_path);

            // Create the asset
            foreach ($assets as $k => $v) {
                if (substr_compare($v, '/', 0, 1) != 0) {
                    $assets[$k] = $xoops->path($v);
                }
            }

            // Create the asset
            $asset = $factory->createAsset(
                $assets,
                $filters
            );

            $asset_path = $asset->getTargetPath();
            if (!is_readable($target_path . $asset_path)) {
                $oldumask = umask(0002);
                $writer->writeAsset($asset);
                umask($oldumask);
            }

            return $xoops->url('assets/' . $asset_path);

        } catch (\Exception $e) {
            $xoops->events()->triggerEvent('core.exception', $e);
            return null;
        }
    }


    /**
     * setDebug enable debug mode, will skip filters prefixed with '?'
     *
     * @param boolean $debug true to enable debug mode
     *
     * @return void
     */
    public function setDebug($debug)
    {
        $this->debug = (boolean) $debug;
    }
}

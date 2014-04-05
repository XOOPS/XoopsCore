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
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;

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
     * @var AssetManager
     */
    private $assetManager = null;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->assetManager = new AssetManager();
        if (isset($_REQUEST['ASSET_DEBUG'])) {
            $this->setDebug(true);
        }
    }

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
        if (is_scalar($assets)) { // just a single path name
            $assets = array($assets);
        }

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
            $am = $this->assetManager;
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

            // Translate asset paths, remove duplicates
            $translated_assets = array();
            foreach ($assets as $k => $v) {
                // translate path if not a reference or absolute path
                if ((substr_compare($v, '@', 0, 1) != 0)
                    && (substr_compare($v, '/', 0, 1) != 0)) {
                    $v = $xoops->path($v);
                }
                if (!in_array($v, $translated_assets)) {
                    $translated_assets[] = $v;
                }
            }

            // Create the asset
            $asset = $factory->createAsset(
                $translated_assets,
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

    /**
     * Add an asset reference to the asset manager
     *
     * @param string $name    the name of the reference to be added
     * @param mixed  $assets  a string asset path, or an array of asset paths, may include wildcard
     * @param string $filters comma separated list of filters
     *
     * @return boolean true if asset registers, false on error
     */
    public function registerAssetReference($name, $assets, $filters = null)
    {
        $xoops = \Xoops::getInstance();

        $assetArray = array();
        $filterArray = array();

        try {
            if (is_scalar($assets)) { // just a single path name
                $assets = array($assets);
            }
            foreach ($assets as $a) {
                // translate path if not a reference or absolute path
                if ((substr_compare($a, '@', 0, 1) != 0)
                    && (substr_compare($a, '/', 0, 1) != 0)) {
                    $a = $xoops->path($a);
                }
                if (false===strpos($a, '*')) {
                    $assetArray[] = new FileAsset($a); // single file
                } else {
                    $assetArray[] = new GlobAsset($a);  // wild card match
                }
            }

            if (empty($filters)) {
                $filters = array();
            } else {
                $filters = explode(',', str_replace(' ', '', $filters));
            }
            foreach ($filters as $filter) {
                switch (ltrim($filter, '?')) {
                    case 'cssembed':
                        $filterArray[] = new Filter\PhpCssEmbedFilter();
                        break;
                    case 'cssmin':
                        $filterArray[] = new Filter\CssMinFilter();
                        break;
                    case 'cssimport':
                        $filterArray[] = new Filter\CssImportFilter();
                        break;
                    case 'cssrewrite':
                        $filterArray[] = new Filter\CssRewriteFilter();
                        break;
                    case 'lessphp':
                        $filterArray[] = new Filter\LessphpFilter();
                        break;
                    case 'scssphp':
                        $filterArray[] = new Filter\ScssphpFilter();
                        break;
                    case 'jsmin':
                        $filterArray[] = new Filter\JSMinFilter();
                        break;
                    default:
                        throw new \Exception(sprintf('%s filter not implemented.', $filter));
                        break;
                }
            }

            $collection = new AssetCollection($assetArray, $filterArray);
            $this->assetManager->set($name, $collection);

            return true;
        } catch (\Exception $e) {
            $xoops->events()->triggerEvent('core.exception', $e);
            return false;
        }
    }
}

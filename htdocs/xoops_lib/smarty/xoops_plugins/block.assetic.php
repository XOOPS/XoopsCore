<?php
/*
 * Smarty plugin
 * ------------------------------------------------------------
 * Type:       block
 * Name:       assetic
 * Purpose:    XOOPS smarty plugin for Assetic
 * Author:     Pierre-Jean Parra, Dejian Xu https://github.com/xudejian/assetic-smarty
 *             Richard Griffith <richard@geekwright.com>
 * Version:    1.0
 *
 * Parameters
 *  assets      - comma separated list of assets to process, relative to source_path
 *  output      - type of output, css = Stylesheet, js  = Javascript
 *  source_path - path to source assets, defaults to XOOPS_ROOT_PATH
 *  target_path - path where assets will be written, defaults to XOOPS_ROOT_PATH . '/assets'
 *  debug       - boolean, true for debug mode, defaults to false
 *  filters     - list of assetic filters to apply to asset. The following filters are supported
 *                    cssembed   - PhpCssEmbedFilter()
 *                    cssmin     - CssMinFilter()
 *                    cssimport  - CssImportFilter()
 *                    cssrewrite - CssRewriteFilter()
 *                    lessphp    - LessphpFilter()
 *                    scssphp    - ScssphpFilter()
 *                    jsmin      - JSMinFilter()
 *  asset_url  - smarty variable to assign asset path
 *
 * Example:
 * <{assetic
 *     assets="style/reset.css,style/common.css,style/other.css"
 *     output="css"
 *     debug=false
 *     filters="cssimport,cssembed,?cssmin"
 *     asset_url=asset_url}>
 *     <link rel="stylesheet" href="<{$asset_url}>">
 * <{/assetic}>
 * ------------------------------------------------------------
 */

use Assetic\AssetManager;
use Assetic\FilterManager;
use Assetic\Filter;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\LazyAssetManager;
use Assetic\Factory\Worker\CacheBustingWorker;
use Assetic\AssetWriter;
use Assetic\Asset\AssetCache;
use Assetic\Cache\FilesystemCache;

function smarty_block_assetic($params, $content, $template, &$repeat)
{
    $debug = isset($params['debug'])? (boolean)$params['debug'] : false;
    // Opening tag (first call only)
    if ($repeat) {
        $xoops = \Xoops::getInstance();

        if (isset($params['source_path'])) {
            $source_path = $params['source_path'];
        } else {
            $source_path = XOOPS_ROOT_PATH;
        }

        if (isset($params['target_path'])) {
            $target_path = $params['target_path'];
        } else {
            $target_path = $xoops->path('assets');
        }

        $target_url = $xoops->url($target_path);

        if (isset($params['asset_url'])) {
            $asset_url = $params['asset_url'];
        } else {
            $asset_url = 'asset_url';
        }

        try {
            $am = new AssetManager();
            $fm = new FilterManager();
            $fm->set('cssembed', new Filter\PhpCssEmbedFilter());
            $fm->set('cssmin', new Filter\CssMinFilter());
            $fm->set('cssimport', new Filter\CssImportFilter());
            $fm->set('cssrewrite', new Filter\CssRewriteFilter());
            $fm->set('lessphp', new Filter\LessphpFilter());
            $fm->set('scssphp', new Filter\ScssphpFilter());
            $fm->set('jsmin', new Filter\JSMinFilter());

            // Factory setup
            $factory = new AssetFactory($target_path);
            $factory->setAssetManager($am);
            $factory->setFilterManager($fm);
            if (isset($params['output'])) {
                switch(strtolower($params['output'])) {
                    case 'css':
                        $factory->setDefaultOutput('css/*.css');
                        break;
                    case 'js':
                        $factory->setDefaultOutput('js/*.js');
                        break;
                }
            }
            $factory->setDebug($debug);
            $lam = new LazyAssetManager($factory);
            $factory->addWorker(new CacheBustingWorker($lam));

            if (isset($params['filters'])) {
                $filters = explode(',', $params['filters']);
            } else {
                $filters = array();
            }

            // Prepare the assets writer
            $writer = new AssetWriter($target_path);

            $assets = explode(',', $params['assets']);
            foreach ($assets as $k => $v) {
                $assets[$k] = $source_path . '/' . $v;
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
            $template->assign($asset_url, $target_url . $asset_path);
        } catch (\Exception $e) {
            $xoops->events()->triggerEvent('core.exception', $e);
        }
    } else { // Closing tag
        if (isset($content)) {
            return $content;
        }
    }
}

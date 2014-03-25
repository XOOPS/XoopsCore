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
 *  assets      - comma separated list of assets to process. Assets should be absolute
 *                file paths, or Xoops::path resolveable
 *  output      - type of output, css = Stylesheet, js  = Javascript
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

function smarty_block_assetic($params, $content, $template, &$repeat)
{
    // Opening tag (first call only)
    if ($repeat) {
        $xoops = \Xoops::getInstance();

        $assets = explode(',', $params['assets']);

        if (isset($params['filters'])) {
            $filters = $params['filters'];
        } else {
            $filters = 'default';
        }

        $output = strtolower($params['output']);

        $debug = isset($params['debug'])? (boolean)$params['debug'] : false;
        if ($debug) {
            $xoops->assets()->setDebug($debug);
        }

        $url = $xoops->assets()->getUrlToAssets($output, $assets, $filters);

        if (isset($params['asset_url'])) {
            $asset_url = $params['asset_url'];
        } else {
            $asset_url = 'asset_url';
        }

        $template->assign($asset_url, $url);
    } else { // Closing tag
        if (isset($content)) {
            return $content;
        }
    }
}

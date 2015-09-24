<?php
/**
 * XOOPS addBaseStylesheetAssets() via Smarty template
 *
 * @copyright   2015 XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Richard Griffith <richard@geekwright.com>
 */

/**
 * Add one or more stylesheets to the base stylesheet asset
 *
 * @param string                   $params commas separated list of script assets
 * @param Smarty_Internal_Template $smarty passed by smarty
 *
 * @return string
 */
function smarty_function_addBaseStylesheet($params, Smarty_Internal_Template $smarty)
{
    $xoops = Xoops::getInstance();
    $assets = [];
    if (isset($params['assets'])) {
        $assets = explode(',', $params['assets']);
    }
    if (!empty($assets)) {
        $xoops->theme()->addBaseStylesheetAssets($assets);
    }
    return '';
}

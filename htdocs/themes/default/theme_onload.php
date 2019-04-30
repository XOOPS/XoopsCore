<?php
/* this script is include before blocks and modules loading
 * so you can add all script and css needed
 */
$xoops = Xoops::getInstance();
// replace the jquery ui theme config option with theme based asset definition
$xoops->theme()->setNamedAsset('jqueryuicss', 'media/jquery/ui/themes/smoothness/jquery-ui.css');

$xoops->theme()->addBaseScriptAssets(array(
    'themes/default/assets/js/jquery.min.js',
    'themes/default/assets/js/bootstrap.min.js',
    'themes/default/assets/js/ie10-viewport-bug-workaround.js',
));

$xoops->theme()->addBaseStylesheetAssets(array(
    'xoops.css',
    'themes/default/assets/css/bootstrap.css',
    'themes/default/assets/css/xoops.bootstrap.css',
    'themes/default/assets/css/style.css',
));
// Customizing forms rendering
$xoops->theme()->setRenderer(new \Xoops\Form\Renderer\Bootstrap3Renderer());

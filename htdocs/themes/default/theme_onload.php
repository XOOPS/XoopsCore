<?php
/* this script is include before blocks and modules loading
 * so you can add all script and css needed
 */
$xoops = Xoops::getInstance();
$xoops->theme()->addScriptAssets(
    array('media/jquery/jquery.js', 'media/bootstrap/js/bootstrap.min.js')
);

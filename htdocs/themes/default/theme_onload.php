<?php
/* this script is include before blocks and modules loading
 * so you can add all script and css needed
 */
$xoops = Xoops::getInstance();
$xoops->theme()->addScript('media/jquery/jquery.js');
$xoops->theme()->addScript('media/bootstrap/js/bootstrap.min.js');
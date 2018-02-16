<?php
$configs = array(
    'autoloader' => dirname(__DIR__) . '/xoops_lib/vendor/autoload.php',
    'configfile' => getcwd() . '/xoopsCIconfigs.php',
    'mainfile' => dirname(__DIR__) . '/htdocs/mainfile.php',
);
return new \XoopsConsole\Library\SimpleContainer($configs);

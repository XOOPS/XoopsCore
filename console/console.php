<?php

namespace XoopsConsole;

use XoopsConsole\Library\XCApplication;

if (php_sapi_name() != 'cli') {
    die ('CLI use only');
}

spl_autoload_register(function ($class) {
    $prefix = 'XoopsConsole\\';
    $base_dir = __DIR__ . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

date_default_timezone_set('UTC');
//set_time_limit(0);

$configs = (include 'config.php');

$mainfile = $configs->get('mainfile');
if (file_exists($mainfile)) {
    $xoopsOption["nocommon"] = true;
    include_once $mainfile;
    $xoops = \Xoops::getInstance();
    $xoops->loadLocale();
    $xoops->setTheme(new \Xoops\Core\Theme\NullTheme);
    $xoopsLogger = $xoops->logger();
    $xoopsLogger->quiet();
    $xoops->events();
    $psr4loader = new \Xoops\Core\Psr4ClassLoader();
    $psr4loader->register();
    // listeners respond with $arg->addNamespace($namespace, $directory);
    $xoops->events()->triggerEvent('core.include.common.classmaps');
    $xoops->events()->triggerEvent('core.include.common.psr4loader', $psr4loader);
} else {
    // apparently there is no mainfile, so fall back on the autoloader
    require_once $configs->get('autoloader');
}

// simple psr-4 loader, example from
// https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md


$app = new XCApplication('XOOPS Console', '0.1.0');
$app->XContainer = $configs;

$app->addCommands(array(
    new \XoopsConsole\Commands\ActivateModuleCommand(),
    new \XoopsConsole\Commands\CiBootstrapCommand(),
    new \XoopsConsole\Commands\CiInstallCommand(),
    new \XoopsConsole\Commands\DeactivateModuleCommand(),
    new \XoopsConsole\Commands\InstallModuleCommand(),
    new \XoopsConsole\Commands\UninstallModuleCommand(),
    new \XoopsConsole\Commands\UpdateModuleCommand(),
    new \XoopsConsole\Commands\SetConfigCommand(),
    new \XoopsConsole\Commands\RenameSystemTablesCommand(),
    new \XoopsConsole\Commands\Utf8mb4ModuleCommand(),
));

$app->run();

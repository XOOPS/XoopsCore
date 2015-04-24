<?php

namespace XoopsConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Xoops\Core\Yaml;

class CiBootstrapCommand extends Command
{
    /**
     * establish the command configuration
     * @return void
     */
    protected function configure()
    {
        $this->setName("ci-bootstrap")
            ->setDescription("Create a mainfile for CI processes")
            ->setDefinition(array())
            ->setHelp(<<<EOT
The <info>ci-bootstrap</info> command writes a basic mainfile for use in automation
of the travis-ci continuious integration environment.
EOT
             );
    }

    /**
     * Write mainfile.php using specified configFile
     *
     * @param string $configFile fully qualified path to YAML configuration file
     * @param string $mainfile   fully qualified name of mainfile to write
     * @return integer|false
     */
    protected function createMainfile($configFile, $mainfile)
    {
        $lines = <<<'EOT'
<?php
if (!class_exists('XoopsBaseConfig', false)) {
    include __DIR__ . '/class/XoopsBaseConfig.php';
    XoopsBaseConfig::getInstance('<{$xoopsbaseconfigs}>');
    XoopsBaseConfig::establishBCDefines();
    define("XOOPS_MAINFILE_INCLUDED", 1);

    define("XOOPS_GROUP_ADMIN", "1");
    define("XOOPS_GROUP_USERS", "2");
    define("XOOPS_GROUP_ANONYMOUS", "3");

    $rootPath = XoopsBaseConfig::get('root-path');
    if (!isset($xoopsOption['nocommon']) && !empty($rootPath)) {
        include $rootPath.'/include/common.php';
    }
}
EOT;
        $lines = str_replace('<{$xoopsbaseconfigs}>', $configFile, $lines);

        return file_put_contents($mainfile, $lines);
    }

    /**
     * This builds a config file suitable for travis-ci.org
     *
     * @param string $configFile fully qualified path to YAML configuration file
     * @param string $baseDir    base directory
     * @return integer|false
     */
    protected function createConfigFile($configFile, $baseDir)
    {
        $configs = array(
            'root-path' => $baseDir,
            'lib-path' => $baseDir . '/xoops_lib',
            'var-path' => $baseDir . '/xoops_data',
            'trust-path' => $baseDir . '/xoops_lib',
            'url' => 'http://localhost/',
            'prot' => 'http://',
            'asset-path' => $baseDir . '/assets',
            'asset-url' => 'http://localhost/assets',
            'cookie-domain' => '',
            'cookie-path' => '/',
            'db-type' => 'pdo_mysql',
            'db-charset' => 'utf8',
            'db-prefix' => 'x300',
            'db-host' => 'localhost',
            'db-user' => 'travis',
            'db-pass' => '',
            'db-name' => 'xoops_test',
            'db-pconnect' => 0,
            'db-parameters' => array(
                'driver'   => 'pdo_mysql',
                'charset'  => 'utf8',
                'dbname'   => 'xoops_test',
                'host'     => 'localhost',
                'user'     => 'travis',
                'password' => '',
            ),
        );
        Yaml::saveWrapped($configs, $configFile);
    }

    /**
     * write a configuration file in the current directory, and write htdocs/mainfile.php
     * that references that configuration relative to the console/Commands directory.
     *
     * @param InputInterface  $input  input handler
     * @param OutputInterface $output output handler
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $XContainer = $this->getApplication()->XContainer;

        $configFile = $XContainer->get('configfile');
        $mainfile = $XContainer->get('mainfile');
        $baseDir = dirname($mainfile);
        if (!file_exists($configFile)) {
            $baseDir = dirname($mainfile);
            if (false === $this->createConfigFile($configFile, $baseDir)) {
                $output->writeln(sprintf('<error>Could not write file %s!</error>', $configFile));
                return;
            }
            $output->writeln(sprintf('<info>Created config file %s.</info>', $configFile));
        } else {
            $output->writeln(sprintf('<info>Using existing config file %s.</info>', $configFile));
        }

        if (!file_exists($mainfile)) {
            if (false === $this->createMainfile($configFile, $mainfile)) {
                $output->writeln(sprintf('<error>Could not write %s!</error>', $mainfile));
                return;
            }
            $output->writeln(sprintf('<info>Wrote mainfile %s</info>', $mainfile));
        } else {
            $output->writeln(sprintf('<info>Using existing mainfile %s</info>', $mainfile));
        }

        if (!class_exists('\XoopsBaseConfig', false)) {
            include $baseDir . '/class/XoopsBaseConfig.php';
            \XoopsBaseConfig::getInstance($configFile);
        }
        \Xoops\Core\Cache\CacheManager::createDefaultConfig();
    }
}

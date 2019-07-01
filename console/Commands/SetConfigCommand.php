<?php

namespace XoopsConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

class SetConfigCommand extends Command
{
    /**
     * establish the command configuration
     * @return void
     */
    protected function configure()
    {
        $this->setName('set-config')
            ->setDescription('Set a system configuration value')
            ->setDefinition([
                new InputArgument('name', InputArgument::REQUIRED, 'Configuration item name'),
                new InputArgument('value', InputArgument::REQUIRED, 'Value for configuration item'),
            ])->setHelp(
                <<<EOT
The <info>set-config</info> command sets a configuration item to the specified value.
EOT
             );
    }

    /**
     * execute the command
     *
     * @param InputInterface  $input  input handler
     * @param OutputInterface $output output handler
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xoops = \Xoops::getInstance();

        $name = $input->getArgument('name');
        $value = $input->getArgument('value');

        $configHandler = $xoops->getHandlerConfig();
        $sysmodule = $xoops->getModuleByDirname('system');
        if (empty($sysmodule)) {
            $output->writeln('<error>Module system is not installed!</error>');

            return;
        }
        $mid = $sysmodule->mid();
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('conf_modid', $mid));
        $criteria->add(new Criteria('conf_name', $name));
        $objArray = $configHandler->getConfigs($criteria);
        $configItem = reset($objArray);
        if (empty($configItem)) {
            $output->writeln(sprintf('<error>Config item %s not found!</error>', $name));

            return;
        }
        $configItem->setConfValueForInput($value);
        $result = $configHandler->insertConfig($configItem);
        if (false === $result) {
            $output->writeln(sprintf('<error>Could not set %s!</error>', $name));
        }
        $output->writeln(sprintf('Set %s', $name));
    }
}

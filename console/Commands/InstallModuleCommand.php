<?php

namespace XoopsConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class InstallModuleCommand extends Command
{
    /**
     * establish the command configuration
     * @return void
     */
    protected function configure()
    {
        $this->setName("install-module")
            ->setDescription("Install a module")
            ->setDefinition(array(
                new InputArgument('module', InputArgument::REQUIRED),
            ))->setHelp(<<<EOT
The <info>install-module</info> command installs a module.
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
        $module = $input->getArgument('module');
        $output->writeln(sprintf('Installing %s', $module));
        $xoops = \Xoops::getInstance();
        if (false !== $xoops->getModuleByDirname($module)) {
            $output->writeln(sprintf('<error>%s module is alreay installed!</error>', $module));
            return;
        }
        $xoops->setTpl(new \XoopsTpl());
        \XoopsLoad::load('module', 'system');
        $sysmod = new \SystemModule();
        $result = $sysmod->install($module);
        foreach ($sysmod->trace as $message) {
            if (is_array($message)) {
                foreach ($message as $subMessage) {
                    if (!is_array($subMessage)) {
                        $output->writeln(strip_tags($subMessage));
                    }
                }
            } else {
                $output->writeln(strip_tags($message));
            }
        }
        if ($result===false) {
            $output->writeln(sprintf('<error>Install of %s failed!</error>', $module));
        } else {
            $output->writeln(sprintf('<info>Install of %s completed.</info>', $module));
        }
        $xoops->cache()->delete('system');
    }
}

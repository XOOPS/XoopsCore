<?php

namespace XoopsConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('test')
             ->setDescription('Sample description for our command named test')
             ->setDefinition([
                new InputOption('flag', 'f', InputOption::VALUE_NONE, 'Raise a flag'),
                new InputArgument('name', InputArgument::OPTIONAL, 'A name', 'uhhh, ... Clem'),
            ])
             ->setHelp(
                 <<<EOT
The <info>test</info> command just says hello.
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('flag')) {
            $output->writeln('<info>flagged</info>');
        }
        $output->writeln(sprintf('Hello, %s!', $input->getArgument('name')));
    }
}

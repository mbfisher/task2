<?php

namespace Task\Cli;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Task\Cli\Command\RunCommand;

class Application extends ConsoleApplication
{
    public function __construct()
    {
        parent::__construct('task', '1.0.0');
    }

    protected function getCommandName(InputInterface $input)
    {
        $commandName = $input->getFirstArgument();

        if (!$commandName) {
            return 'list';
        }

        if ($commandName === 'run' && !$input->getParameterOption(['-help', '--h'])) {
            throw new \InvalidArgumentException('Use a tasks name to run it');
        }

        if($this->has($commandName)) {
            return $commandName;
        }

        return 'run';
    }

    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [
            new RunCommand()
        ]);
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        $inputDefinition->addOption(new InputOption('--taskfile', '-t', InputOption::VALUE_OPTIONAL, 'Path to Taskfile', null));

        return $inputDefinition;
    }
}
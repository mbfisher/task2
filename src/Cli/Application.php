<?php

namespace Task\Cli;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Task\Cli\Command\RunCommand;

class Application extends ConsoleApplication
{
    public function __construct()
    {
        parent::__construct('task', '1.0.0');
    }

    protected function getCommandName(InputInterface $input)
    {
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

        return $inputDefinition;
    }
}
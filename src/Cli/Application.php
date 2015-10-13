<?php

namespace Task\Cli;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Task\Cli\Command\RunCommand;
use Task\ProjectInterface;
use Task\TaskInterface;

class Application extends ConsoleApplication
{
    private $project;

    public function __construct()
    {
        $this->project = $this->findProject();

        parent::__construct('task', '1.0.0');
    }

    /**
     * @return ProjectInterface
     * @throws \RuntimeException
     */
    protected function findProject()
    {
        if (!$taskfile = $this->findTaskfile()) {
            throw new \RuntimeException("No Taskfile found");
        }

        $project = require $taskfile;

        if (!($project instanceof ProjectInterface)) {
            throw new \UnexpectedValueException("Taskfile must return an instance of Task\\ProjectInterface");
        }

        return $project;
    }

    protected function findTaskfile()
    {
        $cwd = getcwd();

        foreach (['Taskfile', 'taskfile', 'taskfile.php'] as $variant) {
            $file = $cwd . DIRECTORY_SEPARATOR . $variant;

            if (is_file($file)) {
                return $file;
            }
        }

        return false;
    }

    protected function getCommandName(InputInterface $input)
    {
        return 'run';
    }

    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [
            new RunCommand($this->getProject())
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

    /**
     * @return ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }
}
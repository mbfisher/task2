<?php

namespace Task\Cli;

use Doctrine\Common\Collections\ArrayCollection;
use League\CLImate\CLImate;
use React\EventLoop\Factory;
use React\Promise\Deferred;
use React\Stream\Buffer;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Task\Cli\Output\ConsoleOutput;
use Task\Output\OutputInterface;
use Task\ProjectInterface;

class Application
{
    protected function createInput(InputInterface $input = null)
    {
        $input = $input ?: new ArgvInput();

        $definition = $this->createDefinition();
        $input->bind($definition);

        return $input;
    }

    protected function createDefinition()
    {
        $definition = new InputDefinition();
        $definition->addArgument(new InputArgument('task', InputArgument::REQUIRED));
        $definition->addOption(new InputOption('taskfile', 't', InputOption::VALUE_REQUIRED));
        $definition->addOption(new InputOption('parameter', 'p', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY));

        return $definition;
    }


    public function run(OutputInterface $output = null)
    {
        $deferred = new Deferred();

        $input = $this->createInput();

        try {
            $input->validate();
            $project = $this->findProject($input->getOption('taskfile'));
        } catch (\RuntimeException $ex) {
            echo $ex->getMessage(), "\n";
            $deferred->reject(1);
            return $deferred->promise();
        }

        $name = $input->getArgument('task');

        if (!$project->hasTask($name)) {
            echo "Task '$name' not found", "\n";
            $deferred->reject(1);
            return $deferred->promise();
        }

        $loop = Factory::create();
        $output = new Buffer(fopen('php://stdout', 'w'), $loop);
        $parameters = $this->parseParameters($input);

        $context = $project->createContext($loop, $output, $parameters);

        return $context->run($name)->then(function () {
            echo 'done';
            return 0;
        }, function (\Exception $ex) {
            echo $ex->getMessage(), "\n";
            return $ex->getCode() ?: 1;
        });
    }

    /**
     * @return ProjectInterface
     * @throws \RuntimeException
     */
    protected function findProject($taskfile = null)
    {
        $taskfile = $taskfile ?: $this->findTaskfile();

        $project = require $taskfile;

        if (!($project instanceof ProjectInterface)) {
            throw new \RuntimeException("Taskfile must return an instance of Task\\ProjectInterface");
        }

        return $project;
    }

    protected function findTaskfile()
    {
        $search = ['./Taskfile', './Taskfile.php', './taskfile.php'];

        $taskfile = array_reduce($search, function ($carry, $path) {
            return $carry ?: realpath($path);
        });

        if (!$taskfile) {
            throw new \RuntimeException('No Taskfile found at ' . implode(',', $search));
        }

        return $taskfile;
    }

    protected function parseParameters(InputInterface $input)
    {
        $option = $input->getOption('parameter') ? $input->getOption('parameter') : [];

        $parameters = new ArrayCollection();

        foreach ($option as $parameter) {
            list($name, $value) = explode('=', $parameter);
            $parameters->set($name, $value);
        }

        return $parameters;
    }


}
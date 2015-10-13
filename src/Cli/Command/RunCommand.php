<?php

namespace Task\Cli\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Cli\Output\ConsoleOutput;
use Task\Context\Context;
use Task\ProjectInterface;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Run a task')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('parameter', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->findProject($input);

        $name = $input->getArgument('name');

        if (!$project->hasTask($name)) {
            throw new \InvalidArgumentException('Task not found');
        }

        $output = new ConsoleOutput($output);
        $parameters = $this->parseParameters($input);

        $context = $project->createContext($output, $parameters);

        $context->run($name);
    }

    /**
     * @return ProjectInterface
     * @throws \RuntimeException
     */
    protected function findProject(InputInterface $input)
    {
        $project = require $this->findTaskfile($input);

        if (!($project instanceof ProjectInterface)) {
            throw new \UnexpectedValueException("Taskfile must return an instance of Task\\ProjectInterface");
        }

        return $project;
    }

    protected function findTaskfile(InputInterface $input)
    {
        $search = ['./Taskfile', './Taskfile.php', './taskfile.php'];

        if ($path = $input->getOption('taskfile')) {
            $search = [ltrim($path, '=')];
        }

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
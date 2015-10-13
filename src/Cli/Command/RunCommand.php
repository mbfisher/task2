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
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('parameter', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->findProject();

        $name = $input->getArgument('name');
        $parameters = $this->parseParameters($input);

        $context = $project->createContext(new ConsoleOutput($output), $parameters);

        $context->run($name);
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
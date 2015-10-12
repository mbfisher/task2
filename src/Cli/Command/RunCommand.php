<?php

namespace Task\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Task\ProjectInterface;

class RunCommand extends Command
{
    /**
     * @var ProjectInterface
     */
    private $project;

    /**
     * RunCommand constructor.
     * @param ProjectInterface $project
     */
    public function __construct(ProjectInterface $project)
    {
        parent::__construct('run');

        $this->project = $project;
    }

    /**
     * @return ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }



    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $task = $this->getProject()->getTask($name);
        $dependencies = $this->getProject()->resolveDependencies($name);

        foreach ($dependencies as $dependency) {

        }
    }
}
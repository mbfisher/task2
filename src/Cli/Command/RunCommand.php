<?php

namespace Task\Cli\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Cli\Output\ConsoleOutput;
use Task\Context;
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
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('parameter', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $parameters = $this->parseParameters($input);

        $context = new Context($this->getProject(), new ConsoleOutput($output), $parameters);

        $context->run($name);
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
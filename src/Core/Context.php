<?php

namespace Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Output\OutputInterface;

class Context implements ContextInterface
{
    /**
     * @var ProjectInterface
     */
    private $project;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Collection
     */
    private $parameters;

    public function __construct(ProjectInterface $project, OutputInterface $output, $parameters = [])
    {
        $this->project = $project;
        $this->output = $output;

        if ($parameters instanceof Collection) {
            $this->parameters = $parameters;
        } elseif (is_array($parameters)) {
            $this->parameters = new ArrayCollection($parameters);
        } else {
            throw new \InvalidArgumentException('Parameters must be an array or Collection');
        }
    }

    /**
     * @return ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function getParameter($name)
    {
        return $this->parameters->get($name);
    }

    public function run($name)
    {
        $task = $this->getProject()->getTask($name);
        $dependencies = $this->getProject()->resolveDependencies($name);

        foreach ($dependencies as $dependency) {
            $dependency->run($this);
        }

        $task->run($this);
    }
}
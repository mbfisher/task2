<?php

namespace Task\Context;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;
use Task\ProjectInterface;
use Task\TaskInterface;

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
    private $plugins;

    /**
     * @var Collection
     */
    private $parameters;

    public function __construct(ContextBuilder $builder)
    {
        $this->project = $builder->getProject();
        $this->output = $builder->getOutput();
        $this->parameters = $builder->getParameters() ?: new ArrayCollection();
        $this->plugins = $builder->getPlugins() ?: new ArrayCollection();
    }

    public function run($name)
    {
        $task = $this->getProject()->getTask($name);
        $dependencies = $this->resolveDependencies($name);

        foreach ($dependencies as $dependency) {
            $this->getOutput()->write(sprintf('> Running %s as dependency of %s', $dependency->getName(), $name));
            $dependency->run($this);
        }

        $this->getOutput()->write("> Running $name");
        $task->run($this);
    }

    /**
     * @param $name
     * @return TaskInterface[]
     */
    public function resolveDependencies($name)
    {
        return array_map([$this->getProject(), 'getTask'], $this->getProject()->getTaskDependencies($name));
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

    /**
     * @return Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getParameter($name)
    {
        return $this->parameters->get($name);
    }

    /**
     * @return Collection
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param $name
     * @return PluginInterface|null
     */
    public function getPlugin($name)
    {
        return $this->plugins->get($name);
    }
}
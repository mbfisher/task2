<?php

namespace Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Context\Context;
use Task\Context\ContextBuilder;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;

class Project implements ProjectInterface
{
    private $name;

    /**
     * @var Collection
     */
    private $tasks;

    /**
     * @var Collection
     */
    private $dependencies;

    /**
     * @var Collection
     */
    private $contextPlugins;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->tasks = new ArrayCollection();
        $this->dependencies = new ArrayCollection();
        $this->contextPlugins = new ArrayCollection();
    }

    public function addTask()
    {
        $definition = DefinitionFactory::create(func_get_args());
        $this->tasks->set($definition->getTask()->getName(), $definition);
    }

    /**
     * @param OutputInterface $output
     * @param Collection|null $parameters
     * @return Context
     */
    public function createContext(OutputInterface $output, Collection $parameters = null)
    {
        $builder = new ContextBuilder();

        $builder->setProject($this);
        $builder->setOutput($output);
        $builder->setParameters($parameters ?: new ArrayCollection());
        $builder->setPlugins($this->getContextPlugins());

        return $builder->getResult();
    }

    public function plugContext(PluginInterface $plugin, $name = null)
    {
        $this->contextPlugins->set($name ?: $plugin->getName(), $plugin);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param $name
     * @return TaskInterface|null
     */
    public function getTask($name)
    {
        return $this->tasks->get($name);
    }

    public function hasTask($name)
    {
        return $this->tasks->containsKey($name);
    }

    /**
     * @return mixed
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @param $name
     * @return array
     */
    public function getTaskDependencies($name)
    {
        return $this->dependencies->get($name) ?: [];
    }

    /**
     * @return Collection
     */
    public function getContextPlugins()
    {
        return $this->contextPlugins;
    }
}

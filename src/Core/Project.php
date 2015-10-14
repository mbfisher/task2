<?php

namespace Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Context\Context;
use Task\Context\ContextBuilder;
use Task\Definition\Definition;
use Task\Definition\ClosureDefinitionFactory;
use Task\Definition\DefinitionFactoryInterface;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;

class Project implements ProjectInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var DefinitionFactoryInterface
     */
    private $definitionFactory;

    /**
     * @var Collection
     */
    private $tasks;

    /**
     * @var Collection
     */
    private $contextPlugins;

    /**
     * @param $name
     * @param DefinitionFactoryInterface|null $definitionFactory
     */
    public function __construct($name, DefinitionFactoryInterface $definitionFactory = null)
    {
        $this->name = $name;
        $this->definitionFactory = $definitionFactory ?: new ClosureDefinitionFactory();

        $this->tasks = new ArrayCollection();
        $this->dependencies = new ArrayCollection();
        $this->contextPlugins = new ArrayCollection();
    }

    public function addTask()
    {
        $arguments = func_get_args();

        if ($arguments[0] instanceof TaskInterface) {
            $definition = new Definition($arguments[0], count($arguments) > 1 ? $arguments[1] : []);
        } else {
            $definition = $this->getDefinitionFactory()->create($arguments);
        }

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
     * @return DefinitionFactoryInterface
     */
    public function getDefinitionFactory()
    {
        return $this->definitionFactory;
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
        return $this->hasTask($name) ? $this->tasks->get($name)->getTask() : null;
    }

    public function hasTask($name)
    {
        return $this->tasks->containsKey($name);
    }

    public function getTaskDefinition($name)
    {
        if (!$this->tasks->containsKey($name)) {
            throw new \InvalidArgumentException('Task "' . $name .'" not found');
        }

        return $this->tasks->get($name);
    }

    /**
     * @return Collection
     */
    public function getContextPlugins()
    {
        return $this->contextPlugins;
    }
}

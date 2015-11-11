<?php

namespace Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Stream\WritableStreamInterface;
use Task\Context\Context;
use Task\Context\ContextBuilder;
use Task\Definition\Definition;
use Task\Definition\ClosureDefinitionFactory;
use Task\Definition\DefinitionFactoryInterface;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;
use Task\Plugin\PluginReference;

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
     * @var array
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
        $this->contextPlugins = [];
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
     * @param WritableStreamInterface $output
     * @param Collection|null $parameters
     * @param LoopInterface|null $loop
     * @return Context
     */
    public function createContext(LoopInterface $loop, WritableStreamInterface $output, Collection $parameters = null)
    {
        $builder = new ContextBuilder();

        $builder->setProject($this);
        $builder->setLoop($loop);
        $builder->setOutput($output);
        $builder->setParameters($parameters ?: new ArrayCollection());
        $builder->setPlugins($this->getContextPlugins());

        return $builder->getResult();
    }

    public function plugContext($plugin, $name = null)
    {
        $this->contextPlugins[] = new PluginReference($plugin, $name);
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
     * @return array
     */
    public function getContextPlugins()
    {
        return $this->contextPlugins;
    }
}

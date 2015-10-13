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

    /**
     * addTask(TaskInterface $task);
     * addTask($name, Closure $work);
     * addTask($name, $description, Closure $work);
     * addTask($name, $description, array $dependencies, Closure $work);
     */
    public function addTask()
    {
        $args = func_get_args();

        if (count($args) < 1) {
            throw new \InvalidArgumentException('You must pass at least one argument to Project#addTask');
        }

        if (count($args) === 1 && $args[0] instanceof TaskInterface) {
            $this->doAddTask($args[0]);
            return $this;
        }

        # Work is the last arg
        $work = array_pop($args);

        if (!($work instanceof \Closure)) {
            throw new \InvalidArgumentException('Work must be callable');
        }

        # Name is the first arg
        $name = array_shift($args);
        $description = null;

        $dependencies = [];
        if (!empty($args)) {
            if (count($args) === 2) {
                $description = $args[0];
                $dependencies = $args[1];
            } elseif (is_string($args[0])) {
                $description = array_shift($args);
            } else {
                $dependencies = array_shift($args);
            }
        }

        if (!is_array($dependencies)) {
            throw new \InvalidArgumentException('Dependencies must be an array');
        }

        $task = new ClosureTask($name, $description, $work);
        $this->doAddTask($task, $dependencies);

        return $this;
    }

    private function doAddTask(TaskInterface $task, $dependencies = [])
    {
        $this->tasks->set($task->getName(), $task);

        if ($dependencies) {
            $this->dependencies->set($task->getName(), $dependencies);
        }
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

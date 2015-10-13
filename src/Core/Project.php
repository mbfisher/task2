<?php

namespace Task;

class Project implements ProjectInterface
{
    private $tasks;
    private $dependencies;

    /**
     * addTask($name, $work);
     * addTask($name, $description, $work);
     * addTask($name, $description, array $dependencies, $work);
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

        if (!is_callable($work)) {
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
        $this->tasks[$task->getName()] = $task;

        if ($dependencies) {
            $this->dependencies[$task->getName()] = $dependencies;
        }
    }

    public function getTasks()
    {
        return $this->tasks;
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
        return $this->dependencies[$name] ?: [];
    }

    public function getTask($name)
    {
        return $this->tasks[$name];
    }

    /**
     * @param $name
     * @return TaskInterface[]
     */
    public function resolveDependencies($name)
    {
        return array_map([$this, 'getTask'], $this->getTaskDependencies($name));
    }
}

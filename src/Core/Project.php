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

        $builder = new TaskBuilder();

        # Work is the last arg
        $builder->setWork(array_pop($args));
        # Name is the first arg
        $builder->setName(array_shift($args));

        $dependencies = [];

        if (!empty($args)) {
            if (count($args) === 2) {
                $builder->setDescription($args[0]);
                $dependencies = $args[1];
            } elseif (is_string($args[0])) {
                $builder->setDescription(array_shift($args));
            } else {
                $dependencies = array_shift($args);
            }
        }

        if (!is_array($dependencies)) {
            throw new \InvalidArgumentException('Dependencies must be an array');
        }

        $task = $builder->getResult();
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

    public function getTaskDependencies($name)
    {
        return $this->dependencies[$name];
    }

    public function getTask($name)
    {
        return $this->tasks[$name];
    }

    public function resolveDependencies($name)
    {
        return array_map($this->getTaskDependencies($name), [$this, 'getTask']);
    }
}

<?php

namespace Task\Definition;

use Task\ClosureTask;
use Task\TaskInterface;

class ClosureDefinitionFactory
{
    /**
     * create(TaskInterface $task);
     * create($name, Closure $work);
     * create($name, $description, Closure $work);
     * create($name, $description, array $dependencies, Closure $work);
     *
     * @return ClosureTask|TaskInterface
     */
    public function create(array $arguments)
    {
        if (count($arguments) < 2) {
            throw new \InvalidArgumentException('You must pass at least one argument to Project#addTask');
        }

        # Work is the last arg
        $work = array_pop($arguments);

        if (!($work instanceof \Closure)) {
            throw new \InvalidArgumentException('Work must be callable');
        }

        # Name is the first arg
        $name = array_shift($arguments);
        $description = null;

        $dependencies = [];
        if (!empty($arguments)) {
            if (count($arguments) === 2) {
                $description = $arguments[0];
                $dependencies = $arguments[1];
            } elseif (is_string($arguments[0])) {
                $description = array_shift($arguments);
            } else {
                $dependencies = array_shift($arguments);
            }
        }

        if (!is_array($dependencies)) {
            throw new \InvalidArgumentException('Dependencies must be an array');
        }

        $task = new ClosureTask($name, $description, $work);
        return new Definition($task, $dependencies);
    }
}
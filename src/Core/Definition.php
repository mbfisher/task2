<?php

namespace Task;

class Definition
{
    /**
     * @var TaskInterface
     */
    private $task;
    /**
     * @var array
     */
    private $dependencies;

    /**
     * Definition constructor.
     * @param TaskInterface $task
     * @param array $dependencies
     */
    public function __construct(TaskInterface $task, array $dependencies = [])
    {
        $this->task = $task;
        $this->dependencies = $dependencies;
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }
}
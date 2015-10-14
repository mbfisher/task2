<?php

namespace Task;

use Task\Context\ContextInterface;

class CompositeTask implements TaskInterface
{
    private $name;
    private $description;
    private $tasks;

    /**
     * CompositeTask constructor.
     * @param $name
     * @param $description
     * @param $tasks
     */
    public function __construct($name, $description, array $tasks)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tasks = $tasks;
    }

    public function run(ContextInterface $context)
    {
        foreach ($this->getTasks() as $task) {
            $context->run($task);
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }


}
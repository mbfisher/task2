<?php

namespace Task;

class Task implements TaskInterface
{
    private $name;
    private $work;
    private $description;

    /**
     * Task constructor.
     */
    public function __construct(TaskBuilder $builder)
    {
        $this->name = $builder->getName();
        $this->work = $builder->getWork();
        $this->description = $builder->getDescription();
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
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
}
<?php

namespace Task;

use Doctrine\Common\Collections\Collection;

abstract class Group
{
    /**
     * @var Collection
     */
    private $tasks;

    protected function configure()
    {
        throw new \LogicException('You must override the configure() method in the concrete group class.');
    }

    protected function addTask()
    {
        $definition = DefinitionFactory::create(func_get_args());
        $this->tasks->set($definition->getTask()->getName(), $definition);
    }
}
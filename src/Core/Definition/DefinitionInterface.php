<?php

namespace Task\Definition;

use Task\TaskInterface;

interface DefinitionInterface
{
    /**
     * @return TaskInterface
     */
    public function getTask();

    /**
     * @return array
     */
    public function getDependencies();
}
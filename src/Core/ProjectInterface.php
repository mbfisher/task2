<?php

namespace Task;

interface ProjectInterface
{
    public function addTask();

    /**
     * @param $name
     * @return TaskInterface
     */
    public function getTask($name);

    /**
     * @param $name
     * @return TaskInterface[]
     */
    public function resolveDependencies($name);
}
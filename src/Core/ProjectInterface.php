<?php

namespace Task;

interface ProjectInterface
{
    public function addTask();
    public function getTask($name);
}
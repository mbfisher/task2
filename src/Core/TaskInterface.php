<?php

namespace Task;

interface TaskInterface
{
    public function getName();
    public function getDescription();
    public function run(ContextInterface $context);
}
<?php

namespace Task;

use Task\Context\ContextInterface;

interface TaskInterface
{
    public function getName();
    public function getDescription();
    public function run(ContextInterface $context);
}
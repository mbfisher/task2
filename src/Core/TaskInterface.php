<?php

namespace Task;

use React\Promise\PromiseInterface;
use Task\Context\ContextInterface;

interface TaskInterface
{
    public function getName();
    public function getDescription();

    /**
     * @param ContextInterface $context
     * @return PromiseInterface
     */
    public function run(ContextInterface $context);
}
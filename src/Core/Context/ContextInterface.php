<?php

namespace Task\Context;

use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use Task\Output\OutputInterface;

interface ContextInterface
{
    /**
     * @return LoopInterface
     */
    public function getLoop();

    public function getParameter($name);

    /**
     * @return OutputInterface
     */
    public function getOutput();

    /**
     * @param $name
     * @return PromiseInterface
     */
    public function run($name);
}
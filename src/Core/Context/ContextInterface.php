<?php

namespace Task\Context;

use React\EventLoop\LoopInterface;
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

    public function run($name);
}
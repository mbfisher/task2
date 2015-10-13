<?php

namespace Task;

use Task\Output\OutputInterface;

interface ContextInterface
{
    public function getParameter($name);

    /**
     * @return OutputInterface
     */
    public function getOutput();

    public function run($name);
}
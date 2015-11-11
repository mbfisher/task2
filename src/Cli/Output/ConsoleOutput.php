<?php

namespace Task\Cli\Output;

use Evenement\EventEmitter;
use Task\Output\OutputInterface;
use Symfony\Component\Console\Output\OutputInterface as ConsoleOutputInterface;

class ConsoleOutput extends EventEmitter implements OutputInterface
{
    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    /**
     * @return ConsoleOutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    public function isWritable()
    {
        // TODO: Implement isWritable() method.
    }

    public function write($data)
    {
        echo $data, "\n";
    }

    public function end($data = null)
    {
        // TODO: Implement end() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}
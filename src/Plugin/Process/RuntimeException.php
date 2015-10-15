<?php

namespace Task\Plugin\Process;

use Symfony\Component\Process\Process;

class RuntimeException extends \RuntimeException
{
    /**
     * @var Process
     */
    private $process;

    /**
     * RuntimeException constructor.
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;

        parent::__construct($process->getErrorOutput(), $process->getExitCode());
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    public function getCommandLine()
    {
        return $this->getProcess()->getCommandLine();
    }

    public function getExitCode()
    {
        return $this->getProcess()->getExitCode();
    }

    public function getOutput()
    {
        return $this->getProcess()->getOutput();
    }

    public function getErrorOutput()
    {
        return $this->getProcess()->getErrorOutput();
    }
}

<?php

namespace Task\Plugin;

use React\ChildProcess\Process;
use React\EventLoop\Timer\Timer;
use Task\Plugin\Process\ProcessHandle;

class ProcessPlugin extends AbstractPlugin
{
    public function getName()
    {
        return 'process';
    }

    /**
     * @param $command
     * @return ProcessHandle
     */
    public function run($command)
    {
        $process = new Process($command);

        $this->getContext()->getLoop()->addTimer(0.001, function (Timer $timer) use ($process) {
            $process->start($timer->getLoop());
        });

        return new ProcessHandle($process);
    }
}
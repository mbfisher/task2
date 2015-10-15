<?php

namespace Task\Plugin;

use React\ChildProcess\Process;
use React\EventLoop\Timer\Timer;
use React\Promise\Deferred;

class ProcessPlugin extends AbstractPlugin
{
    public function getName()
    {
        return ['process', 'ps'];
    }

    public function run(Process $process)
    {
        $deferred = new Deferred();

        $this->getContext()->getLoop()->addTimer(0.001, function (Timer $timer) use ($process, $deferred) {
            $process->on('exit', function ($exitCode, $termSignal) use ($deferred) {
                $deferred->resolve($exitCode);
            });

            $process->start($timer->getLoop());

            $process->stdout->on('data', function ($output) {
                echo 'data', $output;
            });
        });
    }
}

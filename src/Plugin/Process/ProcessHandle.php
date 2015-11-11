<?php

namespace Task\Plugin\Process;

use Evenement\EventEmitterTrait;
use React\ChildProcess\Process;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Promise\PromisorInterface;
use React\Stream\DuplexStreamInterface;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

class ProcessHandle implements PromiseInterface, PromisorInterface, DuplexStreamInterface
{
    use EventEmitterTrait;

    /**
     * @var Process;
     */
    private $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    public function isReadable()
    {
        return $this->process->isRunning() ? $this->process->stdout->isReadable() : false;
    }

    public function pause()
    {
    }

    public function resume()
    {
    }

    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this->process->stdout, $dest, $options);

        return $dest;
    }

    public function close()
    {
        $this->process->close();
    }

    /**
     * @return PromiseInterface
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $deferred = new Deferred();

        $this->process->on('exit', function ($exitCode, $termSignal) use ($onFulfilled, $onRejected, $deferred) {
            if ($exitCode > 0) {
                $onRejected(new \RuntimeException(null, $exitCode));
                $deferred->reject($exitCode);
            } else {
                $onFulfilled($exitCode);
                $deferred->resolve($exitCode);
            }
        });

        return $deferred->promise();
    }

    /**
     * @return PromiseInterface
     */
    public function promise()
    {
        return $this->then();
    }

    public function isWritable()
    {
        return $this->process->isRunning() ? $this->process->stdin->isWritable() : false;
    }

    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new \RuntimeException('Process is not writable');
        }

        $this->process->stdin->write($data);
    }

    public function end($data = null)
    {

    }
}
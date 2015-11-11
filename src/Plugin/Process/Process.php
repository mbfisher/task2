<?php

namespace Task\Plugin\Process;

use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Promise\PromisorInterface;
use React\Stream\DuplexStreamInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

class Process extends \React\ChildProcess\Process implements PromiseInterface, PromisorInterface, DuplexStreamInterface
{
    public function isReadable()
    {
        return $this->isRunning() ? $this->stdout->isReadable() : false;
    }

    public function pause()
    {
    }

    public function resume()
    {
    }

    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this, $dest, $options);

        return $dest;
    }

    public function isWritable()
    {
        return $this->isRunning() ? $this->stdin->isWritable() : false;
    }

    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new \RuntimeException('Process is not writable');
        }

        $this->stdin->write($data);
    }

    public function end($data = null)
    {
    }

    /**
     * @return PromiseInterface
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $deferred = new Deferred();

        $this->on('exit', function ($exitCode, $termSignal) use ($onFulfilled, $onRejected, $deferred) {
            if ($exitCode > 0) {
                $onRejected($exitCode);
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
}
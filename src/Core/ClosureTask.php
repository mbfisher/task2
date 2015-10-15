<?php

namespace Task;

use React\Promise\PromiseInterface;
use Task\Context\ContextInterface;
use React\Promise\Deferred;

class ClosureTask implements TaskInterface
{
    private $name;
    private $description;
    /**
     * @var \Closure
     */
    private $work;

    /**
     * @param $name
     * @param $description
     * @param \Closure $work
     */
    public function __construct($name, $description, \Closure $work)
    {
        $this->name = $name;
        $this->description = $description;
        $this->work = $work;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return callable
     */
    public function getWork()
    {
        return $this->work;
    }

    public function run(ContextInterface $context)
    {
        $deferred = new Deferred();

        $closure = \Closure::bind($this->getWork(), $context);

        try {
            $result = call_user_func($closure);

            if ($result instanceof PromiseInterface) {
                return $result;
            } else {
                $deferred->resolve($result);
                return $deferred->promise();
            }
        } catch (\Exception $ex) {
            $deferred->reject($ex);
            return $deferred->promise();
        }
    }
}
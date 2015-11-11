<?php

namespace Task;

use Doctrine\Common\Collections\Collection;
use React\EventLoop\LoopInterface;
use React\Stream\WritableStreamInterface;
use React\Tests\Stream\WritableStreamTest;
use Task\Context\ContextInterface;
use Task\Definition\DefinitionInterface;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;

interface ProjectInterface
{
    /**
     * @param $name
     * @return TaskInterface
     */
    public function getTask($name);

    /**
     * @param $name
     * @return bool
     */
    public function hasTask($name);

    /**
     * @param $name
     * @return DefinitionInterface
     */
    public function getTaskDefinition($name);

    /**
     * @param PluginInterface|callable $plugin
     * @param null $name
     * @void
     */
    public function plugContext($plugin, $name = null);

    /**
     * @param LoopInterface $loop
     * @param WritableStreamInterface $output
     * @param Collection|null $parameters
     * @return ContextInterface
     */
    public function createContext(LoopInterface $loop, WritableStreamInterface $output, Collection $parameters = null);
}
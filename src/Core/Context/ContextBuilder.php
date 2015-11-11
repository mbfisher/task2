<?php

namespace Task\Context;

use Doctrine\Common\Collections\Collection;
use React\EventLoop\LoopInterface;
use React\Stream\WritableStreamInterface;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;
use Task\ProjectInterface;
use Psr\Log\LoggerInterface;

class ContextBuilder
{
    /**
     * @var ProjectInterface
     */
    private $project;
    /**
     * @var LoopInterface
     */
    private $loop;
    /**
     * @var WritableStreamInterface
     */
    private $output;
    /**
     * @var Collection
     */
    private $parameters;
    /**
     * @var array
     */
    private $plugins;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function getResult()
    {
        return new Context($this);
    }

    /**
     * @return ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param ProjectInterface $project
     * @return ContextBuilder
     */
    public function setProject(ProjectInterface $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return LoopInterface
     */
    public function getLoop()
    {
        return $this->loop;
    }

    /**
     * @param LoopInterface $loop
     */
    public function setLoop(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * @return WritableStreamInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param WritableStreamInterface $output
     * @return ContextBuilder
     */
    public function setOutput(WritableStreamInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param Collection $parameters
     * @return ContextBuilder
     */
    public function setParameters(Collection $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return ContextBuilder
     */
    public function addParameter($name, $value)
    {
        $this->parameters->set($name, $value);

        return $this;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param array $plugins
     * @return ContextBuilder
     */
    public function setPlugins(array $plugins)
    {
        $this->plugins = $plugins;

        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return ContextBuilder
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }


}
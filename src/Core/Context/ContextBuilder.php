<?php

namespace Task\Context;

use Doctrine\Common\Collections\Collection;
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
     * @var OutputInterface
     */
    private $output;
    /**
     * @var Collection
     */
    private $parameters;
    /**
     * @var Collection
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
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     * @return ContextBuilder
     */
    public function setOutput(OutputInterface $output)
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
     * @return Collection
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param Collection $plugins
     * @return ContextBuilder
     */
    public function setPlugins(Collection $plugins)
    {
        $this->plugins = $plugins;

        return $this;
    }

    /**
     * @param PluginInterface $plugin
     * @param null $name
     * @return ContextBuilder
     */
    public function addPlugin(PluginInterface $plugin, $name = null)
    {
        $this->plugins->set($name ?: $plugin->getName(), $plugin);

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
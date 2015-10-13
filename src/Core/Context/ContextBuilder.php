<?php

namespace Task\Context;

use Doctrine\Common\Collections\Collection;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;
use Task\ProjectInterface;

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
     */
    public function setProject(ProjectInterface $project)
    {
        $this->project = $project;
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
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
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
     */
    public function setParameters(Collection $parameters)
    {
        $this->parameters = $parameters;
    }

    public function addParameter($name, $value)
    {
        $this->parameters->set($name, $value);
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
     */
    public function setPlugins(Collection $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @param PluginInterface $plugin
     * @param null $name
     */
    public function addPlugin(PluginInterface $plugin, $name = null)
    {
        $this->plugins->set($name ?: $plugin->getName(), $plugin);
    }
}
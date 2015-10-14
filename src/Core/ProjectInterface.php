<?php

namespace Task;

use Doctrine\Common\Collections\Collection;
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
     * @param PluginInterface $plugin
     * @param null $name
     * @void
     */
    public function plugContext(PluginInterface $plugin, $name = null);

    /**
     * @param OutputInterface $output
     * @param Collection|null $parameters
     * @return ContextInterface
     */
    public function createContext(OutputInterface $output, Collection $parameters = null);
}
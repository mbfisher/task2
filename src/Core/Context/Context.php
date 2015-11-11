<?php

namespace Task\Context;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Task\Definition\DefinitionInterface;
use Task\Output\OutputInterface;
use Task\Plugin\PluginInterface;
use Task\Plugin\PluginReference;
use Task\ProjectInterface;
use Task\TaskInterface;

class Context implements ContextInterface
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
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Collection
     */
    private $plugins;

    /**
     * @var Collection
     */
    private $parameters;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ContextBuilder $builder)
    {
        $this->project = $builder->getProject();
        $this->loop = $builder->getLoop();
        $this->output = $builder->getOutput();
        $this->parameters = $builder->getParameters() ?: new ArrayCollection();

        $this->plugins = $this->createPlugins($builder->getPlugins());

        $this->logger = $builder->getLogger() ?: new NullLogger();
    }

    /**
     * @param PluginReference[] $references
     * @return Collection
     */
    private function createPlugins(array $references)
    {
        $plugins = new ArrayCollection();

        foreach ($references as $reference) {
            $plugin = $reference->createPlugin($this);
            $plugins->set($reference->getName() ?: $plugin->getName(), $plugin);
        }

        return $plugins;
    }

    public function run($name)
    {
        $definition = $this->getProject()->getTaskDefinition($name);

        $dependencies = $this->resolveDependencies($definition);

        foreach ($dependencies as $dependency) {
            $this->getOutput()->write('> Running dependency ' . $dependency->getName());
            $dependency->run($this);
        }

        $task = $definition->getTask();
        $this->getOutput()->write('> Running task ' . $task->getName());

        $promise = $task->run($this);
        $result = $promise->then(function () use ($task) {
            $this->getOutput()->write('> Finished ' . $task->getName());
        }, function (\Exception $ex) {
            $this->getLoop()->stop();
            throw $ex;
        });

        $this->getLoop()->run();

        return $result;
    }

    /**
     * @param DefinitionInterface $definition
     * @return TaskInterface[]
     */
    public function resolveDependencies(DefinitionInterface $definition, $recursive = false)
    {
        $logger = $this->getLogger();

        $logger->debug('Resolving dependencies for "' . $definition->getTask()->getName() . '"', ['dependencies' => $definition->getDependencies()]);

        $result = [];
        foreach (array_reverse($definition->getDependencies()) as $dependency) {
            $dependencyDefinition = $this->getProject()->getTaskDefinition($dependency);
            $result = array_merge($result, [$dependency], $this->resolveDependencies($dependencyDefinition, true));
        }

        $logger->debug('Dependency resolution pass', ['dependencies' => $result]);

        if ($recursive) {
            return $result;
        } else {
            $result = array_unique(array_reverse($result));
            return array_values(array_map([$this->getProject(), 'getTask'], $result));
        }
    }

    /**
     * @return ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return LoopInterface
     */
    public function getLoop()
    {
        return $this->loop;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getParameter($name)
    {
        return $this->parameters->get($name);
    }

    /**
     * @return Collection
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param $name
     * @return PluginInterface|null
     */
    public function getPlugin($name)
    {
        if (!$this->plugins->containsKey($name)) {
            throw new \InvalidArgumentException('Plugin "' . $name . '" not found');
        }

        return $this->plugins->get($name);
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
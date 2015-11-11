<?php

namespace Task\Plugin;

use Task\Context\ContextInterface;

class PluginReference
{
    /**
     * @var PluginInterface|callable
     */
    private $plugin;
    /**
     * @var string|null
     */
    private $name;

    /**
     * PluginReference constructor.
     * @param callable|PluginInterface $plugin
     * @param null|string $name
     */
    public function __construct($plugin, $name)
    {
        $this->plugin = $plugin;
        $this->name = $name;
    }

    /**
     * @param ContextInterface $context
     * @return PluginInterface
     */
    public function createPlugin(ContextInterface $context)
    {
        return is_callable($this->plugin) ? call_user_func($this->plugin, $context) : $this->plugin;
    }

    /**
     * @return callable|PluginInterface
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }
}
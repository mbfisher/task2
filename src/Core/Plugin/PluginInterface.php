<?php

namespace Task\Plugin;

use Task\Context\ContextInterface;

interface PluginInterface
{
    /**
     * @return string|array
     */
    public function getName();
    public function setContext(ContextInterface $context);
}
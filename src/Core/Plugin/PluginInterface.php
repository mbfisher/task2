<?php

namespace Task\Plugin;

use Task\Context\ContextInterface;

interface PluginInterface
{
    public function getName();
    public function setContext(ContextInterface $context);
}
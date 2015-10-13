<?php

namespace Task\Plugin;

interface PluginInterface
{
    public function getName();
    public function __invoke();
}
<?php

namespace Task\Definition;

interface DefinitionFactoryInterface
{
    /**
     * @param array $arguments
     * @return DefinitionInterface
     */
    public function create(array $arguments);
}
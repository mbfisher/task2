<?php

namespace Task\Plugin\DependencyInjection;

class InjectorDefinitionFactory
{
    private $resolver;

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function create(array $arguments)
    {

    }
}
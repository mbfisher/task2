<?php

namespace Task\Plugin;

use Task\Context\ContextInterface;

abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * AbstractPlugin constructor.
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    public static function factory()
    {
        return function (ContextInterface $context) {
            return new static($context);
        };
    }

    /**
     * @return ContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }
}
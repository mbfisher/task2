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
     * @param ContextInterface $context
     */
    public function setContext(ContextInterface $context)
    {
        if ($this->context) {
            throw new \RuntimeException(sprintf('Plugin "%s" already has a context', $this->getName()));
        }

        $this->context = $context;
    }

    /**
     * @return ContextInterface
     */
    public function getContext()
    {
        if (!$this->context) {
            throw new \RuntimeException('Context has not been provided to "%s" plugin yet');
        }

        return $this->context;
    }
}
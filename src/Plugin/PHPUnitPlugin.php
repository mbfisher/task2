<?php

namespace Task\Plugin;

use Task\Plugin\PHPUnit\Command;

class PHPUnitPlugin extends ProcessPlugin
{
    private $prefix;

    public function __construct($prefix = null)
    {
        $this->prefix = $prefix ?: './vendor/bin/phpunit';
    }

    public function getName()
    {
        return 'phpunit';
    }

    public function getCommand()
    {
        return new Command($this->prefix);
    }
}

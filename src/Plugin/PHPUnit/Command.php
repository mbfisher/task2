<?php

namespace Task\Plugin\PHPUnit;

use Symfony\Component\Process\ProcessBuilder;

class Command extends ProcessBuilder
{
    private $testCase;
    private $testFile;
    private $setup = false;

    public function __construct($prefix)
    {
        parent::__construct();
        $this->setPrefix($prefix);
    }

    public function getProcess()
    {
        if (!$this->setup) {
            if ($this->testCase) {
                $this->add($this->testCase);
            }

            if ($this->testFile) {
                $this->add($this->testFile);
            }

            $this->setup = true;
        }

        return parent::getProcess();
    }

    public function setTestCase($testCase)
    {
        $this->testCase = $testCase;
        return $this;
    }

    public function setTestFile($testFile)
    {
        $this->testFile = $testFile;
        return $this;
    }

    public function useColors()
    {
        return $this->add('--colors');
    }

    public function setBootstrap($bootstrap)
    {
        return $this->add('--bootstrap')->add($bootstrap);
    }

    public function setConfiguration($configuration)
    {
        return $this->add('--configuration')->add($configuration);
    }

    public function addCoverage($coverage, $output)
    {
        return $this->add("--coverage-$coverage")->add($output);
    }

    public function setIniValue($key, $value)
    {
        return $this->add('-d')->add("$key=$value");
    }

    public function useDebug()
    {
        return $this->add('--debug');
    }

    public function setFilter($filter)
    {
        return $this->add('--filter')->add($filter);
    }

    public function setTestsuite($testsuite)
    {
        return $this->add('--testsuite')->add($testsuite);
    }

    public function addGroups(array $groups)
    {
        return $this->add('--group')->add(implode(',', $groups));
    }

    public function excludeGroups(array $groups)
    {
        return $this->add('--exclude-group')->add(implode(',', $groups));
    }

    public function addTestSuffixes(array $testSuffixes)
    {
        return $this->add('--test-suffix')->add(implode(',', $testSuffixes));
    }

    public function setIncludePath($includePath)
    {
        return $this->add('--include-path')->add($includePath);
    }

    public function setPrinter($printer)
    {
        return $this->add('--printer')->add($printer);
    }

    public function useTap()
    {
        return $this->add('--tap');
    }
}

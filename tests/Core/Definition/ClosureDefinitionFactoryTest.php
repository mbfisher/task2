<?php

namespace Task\Definition;

class ClosureDefinitionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ClosureDefinitionFactory
     */
    protected function getSubject()
    {
        return new ClosureDefinitionFactory();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithoutArguments()
    {
        $this->getSubject()->create([]);
    }

    public function testCreateNameWork()
    {
        $factory = $this->getSubject();
        $definition = $factory->create(['foo', $work = function () {}]);
        $this->assertInstanceOf('Task\Definition\Definition', $definition);

        $task = $definition->getTask();
        $this->assertInstanceOf('Task\ClosureTask', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEmpty($task->getDescription());
        $this->assertEmpty($definition->getDependencies());
    }

    public function testCreateNameDescriptionWork()
    {
        $factory = $this->getSubject();
        $definition = $factory->create(['foo', 'test', $work = function () {}]);
        $this->assertInstanceOf('Task\Definition\Definition', $definition);

        $task = $definition->getTask();
        $this->assertInstanceOf('Task\ClosureTask', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEquals('test', $task->getDescription());
        $this->assertEmpty($definition->getDependencies());
    }

    public function testCreateNameDescriptionDependenciesWork()
    {
        $factory = $this->getSubject();
        $definition = $factory->create(['foo', 'test', ['bar'], $work = function () {}]);
        $this->assertInstanceOf('Task\Definition\Definition', $definition);

        $task = $definition->getTask();
        $this->assertInstanceOf('Task\ClosureTask', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEquals('test', $task->getDescription());
        $this->assertEquals(['bar'], $definition->getDependencies());
    }
}

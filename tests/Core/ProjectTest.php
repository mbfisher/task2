<?php

namespace Task;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    protected function getSubject()
    {
        return new Project('test');
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddTaskWithoutArguments()
    {
        $this->getSubject()->addTask();
    }

    public function testAddTaskInterface()
    {
        $task = $this->prophesize('Task\TaskInterface');
        $task->getName()->willReturn('foo');

        $project = $this->getSubject();
        $project->addTask($task->reveal());

        $this->assertSame($task->reveal(), $project->getTasks()['foo']);
    }

    public function testAddNameWork()
    {
        $project = $this->getSubject();
        $project->addTask('foo', $work = function () {});

        $task = $project->getTasks()['foo'];
        $this->assertInstanceOf('Task\ClosureTask', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEmpty($task->getDescription());
        $this->assertEmpty($project->getDependencies());
    }

    public function testAddNameDescriptionWork()
    {
        $project = $this->getSubject();
        $project->addTask('foo', 'test', $work = function () {});

        $task = $project->getTasks()['foo'];
        $this->assertInstanceOf('Task\ClosureTask', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEquals('test', $task->getDescription());
        $this->assertEmpty($project->getDependencies());
    }

    public function testAddNameDescriptionDependenciesWork()
    {
        $project = $this->getSubject();
        $project->addTask('foo', 'test', ['bar'], $work = function () {});

        $task = $project->getTasks()['foo'];
        $this->assertInstanceOf('Task\ClosureTask', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEquals('test', $task->getDescription());
        $this->assertEquals(['foo' => ['bar']], $project->getDependencies()->toArray());
    }
}

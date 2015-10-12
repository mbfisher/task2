<?php

namespace Task;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddTaskWithoutArguments()
    {
        $project = new Project();
        $project->addTask();
    }

    public function testAddTaskInterface()
    {
        $task = $this->prophesize('Task\TaskInterface');
        $task->getName()->willReturn('foo');

        $project = new Project();
        $project->addTask($task->reveal());

        $this->assertSame($task->reveal(), $project->getTasks()['foo']);
    }

    public function testAddNameWork()
    {
        $project = new Project();
        $project->addTask('foo', $work = function () {});

        $task = $project->getTasks()['foo'];
        $this->assertInstanceOf('Task\Task', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEmpty($task->getDescription());
        $this->assertEmpty($project->getDependencies());
    }

    public function testAddNameDescriptionWork()
    {
        $project = new Project();
        $project->addTask('foo', 'test', $work = function () {});

        $task = $project->getTasks()['foo'];
        $this->assertInstanceOf('Task\Task', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEquals('test', $task->getDescription());
        $this->assertEmpty($project->getDependencies());
    }

    public function testAddNameDescriptionDependenciesWork()
    {
        $project = new Project();
        $project->addTask('foo', 'test', ['bar'], $work = function () {});

        $task = $project->getTasks()['foo'];
        $this->assertInstanceOf('Task\Task', $task);
        $this->assertEquals('foo', $task->getName());
        $this->assertEquals($work, $task->getWork());
        $this->assertEquals('test', $task->getDescription());
        $this->assertEquals(['foo' => ['bar']], $project->getDependencies());
    }
}

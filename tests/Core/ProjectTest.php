<?php

namespace Task;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    protected function getSubject()
    {
        return new Project('test');
    }

    public function testAddTask()
    {
        $task = $this->prophesize('Task\TaskInterface');
        $task->getName()->willReturn('foo');

        $project = $this->getSubject();
        $project->addTask($task->reveal());

        $definition = $project->getTaskDefinition('foo');
        $this->assertNotEmpty($definition);
        $this->assertSame($task->reveal(), $definition->getTask());
        $this->assertEmpty($definition->getDependencies());
    }
}

<?php

namespace Task\Context;

use Monolog\Logger;
use Task\ProjectInterface;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    protected function getSubject($project = null, $output = null)
    {
        $builder = (new ContextBuilder())
            ->setProject($project ?: $this->prophesize('Task\ProjectInterface')->reveal())
            ->setOutput($output ?: $this->prophesize('Task\Output\OutputInterface')->reveal());

        if (getenv('DEBUG')) {
            $builder->setLogger(new Logger('test'));
        }

        return new Context($builder);
    }

    public function testResolveDependencies()
    {
        $taskOne = $this->prophesize('Task\TaskInterface');
        $taskOne->getName()->willReturn('one');
        $taskDefOne = $this->prophesize('Task\Definition\DefinitionInterface');
        $taskDefOne->getTask()->willReturn($taskOne);
        $taskDefOne->getDependencies()->willReturn(['two', 'three']);

        $taskTwo = $this->prophesize('Task\TaskInterface');
        $taskTwo->getName()->willReturn('two');
        $taskDefTwo = $this->prophesize('Task\Definition\DefinitionInterface');
        $taskDefTwo->getTask()->willReturn($taskTwo);
        $taskDefTwo->getDependencies()->willReturn([]);

        $taskThree = $this->prophesize('Task\TaskInterface');
        $taskThree->getName()->willReturn('three');
        $taskDefThree = $this->prophesize('Task\Definition\DefinitionInterface');
        $taskDefThree->getTask()->willReturn($taskThree);
        $taskDefThree->getDependencies()->willReturn(['four']);

        $taskFour = $this->prophesize('Task\TaskInterface');
        $taskFour->getName()->willReturn('four');
        $taskDefFour = $this->prophesize('Task\Definition\DefinitionInterface');
        $taskDefFour->getTask()->willReturn($taskFour);
        $taskDefFour->getDependencies()->willReturn(['two']);

        $project = $this->prophesize('Task\ProjectInterface');
        $project->getTaskDefinition('one')->willReturn($taskDefOne);
        $project->getTaskDefinition('two')->willReturn($taskDefTwo);
        $project->getTaskDefinition('three')->willReturn($taskDefThree);
        $project->getTaskDefinition('four')->willReturn($taskDefFour);
        $project->getTask('one')->willReturn($taskOne);
        $project->getTask('two')->willReturn($taskTwo);
        $project->getTask('three')->willReturn($taskThree);
        $project->getTask('four')->willReturn($taskFour);

        $context = $this->getSubject($project->reveal());
        $result = $context->resolveDependencies($taskDefOne->reveal());
        $names = array_map(function ($task) {
            return $task->getName();
        }, $result);

        $this->assertEquals(['two', 'four', 'three'], $names);
    }
}

<?php

namespace Task\Plugin;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Task\Output\OutputInterface;
use Task\Plugin\Process\RuntimeException;

class ProcessPlugin extends AbstractPlugin
{
    public function getName()
    {
        return ['process', 'ps'];
    }

    public function createProcess()
    {
        return new ProcessBuilder();
    }

    /**
     * @param string|Process|ProcessBuilder $process
     * @param OutputInterface|null $output
     * @return string
     */
    public function run($process, OutputInterface $output = null)
    {
        if (is_string($process)) {
            $process = new Process($process);
        } elseif ($process instanceof ProcessBuilder) {
            $process = $process->getProcess();
        } elseif (!($process instanceof Process)) {
            throw new \InvalidArgumentException('You must pass a string, Process or ProcessBuilder to ProcessPlugin#run');
        }

        if ($output) {
            $process->run(function ($type, $data) use ($output) {
                $output->write($data);
            });
        } else {
            $process->run();
        }

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process);
        }

        return $process->getOutput();
    }
}

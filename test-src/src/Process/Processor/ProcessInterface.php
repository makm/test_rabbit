<?php

namespace WorkerProcesses\Process\Processor;

use WorkerProcesses\Process\Task\TaskInterface;

/**
 * Interface ProcessInterface
 * @package WorkerProcesses\Process\Processor
 */
interface ProcessInterface
{
    /**
     * @param TaskInterface $task
     * @return bool
     */
    public function process(TaskInterface $task): bool;

    /**
     * @return string
     */
    public function getSupportedTaskClassName(): string;
}

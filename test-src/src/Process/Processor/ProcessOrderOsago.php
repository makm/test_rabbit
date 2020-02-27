<?php

namespace WorkerProcesses\Process\Processor;

use WorkerProcesses\Process\Task\TaskInterface;
use WorkerProcesses\Process\Task\TaskOrderOsago;

/**
 * Class ProcessOrderCredit
 * @package WorkerProcesses\Process\Processor
 */
class ProcessOrderOsago implements ProcessInterface
{
    /**
     * @param TaskInterface $task
     * @return bool
     */
    public function process(TaskInterface $task): bool
    {
//        sleep(2);

        var_export($task->getParameters());

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTaskClassName(): string
    {
        return TaskOrderOsago::class;
    }
}

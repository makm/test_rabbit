<?php

namespace WorkerProcesses\Process\Processor;

use WorkerProcesses\Process\Task\TaskInterface;
use WorkerProcesses\Process\Task\TaskOrderRefinance;

/**
 * Class ProcessOrderRefinance
 * @package WorkerProcesses\Process\Processor
 */
class ProcessOrderRefinance implements ProcessInterface
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
        return TaskOrderRefinance::class;
    }
}

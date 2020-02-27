<?php

namespace WorkerProcesses\Process\Processor;

use WorkerProcesses\Process\Task\TaskInterface;
use WorkerProcesses\Process\Task\TaskOrderRefinance;

/**
 * Class ProcessOrderRefinance
 * @package WorkerProcesses\Process\Processor
 */
class ProcessOrderRefinance extends ProcessAbstract
{
    /**
     * @param TaskInterface $task
     * @return bool
     */
    public function process(TaskInterface $task): bool
    {
        sleep(TEST_PROCESS_SLEEP_TIME);
        $this->logger->info(
            \sprintf(
                '%s Finish work with params %s',
                __CLASS__,
                \var_export($task->getParameters(), true)
            )
        );

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

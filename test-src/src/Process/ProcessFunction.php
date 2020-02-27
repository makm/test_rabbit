<?php

namespace WorkerProcesses\Process;


use WorkerProcesses\Exception\InvalidArgumentValueException;
use WorkerProcesses\Process\Processor\ProcessInterface;

/**
 * Class ProcessFunction
 * @package WorkerProcesses\Process
 */
class ProcessFunction
{
    /**
     * @var array
     */
    private array $queuesProcesses = [];

    /**
     * ProcessFunction constructor.
     * @param array $queuesProcesses
     */
    public function __construct(array $queuesProcesses)
    {
        $this->queuesProcesses = $queuesProcesses;
    }

    /**
     * @param $queueName
     * @param array $tasks
     */
    public function __invoke($queueName, array $tasks)
    {
        $process = $this->queuesProcesses[$queueName] ?? null;
        if (!$process instanceof ProcessInterface) {
            throw new InvalidArgumentValueException(
                \sprintf('Unknown queue name %s', $queueName)
            );
        }

        foreach ($tasks as $task) {
            $process->process($task);
        }
    }

}

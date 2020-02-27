<?php
namespace WorkerProcesses\Process\Task;

/**
 * Class TaskOrderCredit
 * @package WorkerProcesses\Process\Task
 */
class TaskOrderCredit implements TaskInterface
{
    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * Task constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}

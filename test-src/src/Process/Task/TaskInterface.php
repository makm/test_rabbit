<?php

namespace WorkerProcesses\Process\Task;

/**
 * Interface TaskInterface
 * @package WorkerProcesses\Process\Task
 */
interface TaskInterface
{
    /**
     * @return array
     */
    public function getParameters(): array;
}

<?php

namespace WorkerProcesses\Process\Processor;


use Psr\Log\LoggerInterface;

/**
 * Class ProcessAbstract
 * @package WorkerProcesses\Process\Processor
 */
abstract class ProcessAbstract implements ProcessInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ProcessAbstract constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}

<?php

namespace WorkerProcesses\Process;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use WorkerProcesses\Process\Processor\ProcessInterface;

/**
 * Class AppContainer
 */
class WorkerApplication
{
    /**
     * @var AMQPStreamConnection
     */
    private AMQPStreamConnection $connection;

    /**
     * @var AMQPChannel
     */
    private AMQPChannel $channel;

    /**
     * @var array
     */
    private array $queuesProcesses = [];

    /**
     * @var string
     */
    private string $exchange;

    /**
     * @var TaskProducer
     */
    private TaskProducer $taskProducer;

    /**
     * WorkerApplication constructor.
     * @param AMQPStreamConnection $connection
     * @param string $exchange
     */
    public function __construct(AMQPStreamConnection $connection, string $exchange)
    {
        $this->connection = $connection;
        $this->channel = $connection->channel();
        $this->channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
        $this->exchange = $exchange;
        $this->taskProducer = new TaskProducer($this->channel, $this->exchange);
    }

    /**
     * @param string $taskClass
     * @return false|string
     */
    private function getQueueNameByTaskClass(string $taskClass)
    {
        return substr(strrchr($taskClass, "\\"), 1);
    }

    /**
     * @param ProcessInterface $process
     * @return WorkerApplication
     */
    public function addProcess(ProcessInterface $process): self
    {
        $class = $process->getSupportedTaskClassName();
        $queueName = $this->getQueueNameByTaskClass($class);

        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->channel->queue_bind($queueName, $this->exchange, $queueName);
        $this->queuesProcesses[$queueName] = $process;

        return $this;
    }

    /**
     * @return array
     */
    private function getQueues(): array
    {
        return array_keys($this->queuesProcesses);
    }

    /**
     * @return TaskProducer
     */
    public function getTaskProducer(): TaskProducer
    {
        return $this->taskProducer;
    }

    /**
     * @param $priorityQueueName
     * @param int $maxBatchCount
     * @return TaskConsumer
     */
    public function createTaskConsumer($priorityQueueName, $maxBatchCount = 20): TaskConsumer
    {
        $taskConsumer = new TaskConsumer(
            $this->channel,
            $this->getQueues(),
            $priorityQueueName,
            new ProcessFunction($this->queuesProcesses)
        );
        $taskConsumer->setMaxBatchCount($maxBatchCount);

        return $taskConsumer;
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}

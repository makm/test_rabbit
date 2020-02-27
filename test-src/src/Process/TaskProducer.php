<?php

namespace WorkerProcesses\Process;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use WorkerProcesses\Process\Task\ProcessFunction;
use WorkerProcesses\Process\Task\TaskInterface;

/**
 * Class TaskConsumer
 * @package WorkerProcesses\Process
 */
class TaskProducer
{
    /**
     * @var AMQPChannel
     */
    private AMQPChannel $channel;

    /**
     * @var string
     */
    private string $exchange;

    /**
     * TaskConsumer constructor.
     * @param AMQPChannel $channel
     * @param string $exchange
     */
    public function __construct(AMQPChannel $channel, string $exchange)
    {
        $this->channel = $channel;
        $this->exchange = $exchange;
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
     * @param TaskInterface $task
     * @return void
     */
    public function pushTask(TaskInterface $task): void
    {
        $queueName = $this->getQueueNameByTaskClass(get_class($task));
        $msg = new AMQPMessage(\serialize($task));
        $this->channel->basic_publish($msg, $this->exchange, $queueName);
    }
}

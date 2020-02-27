<?php

namespace WorkerProcesses\Process;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use WorkerProcesses\Exception\InvalidArgumentValueException;
use WorkerProcesses\Process\Task\TaskInterface;

/**
 * Class TaskConsumer
 * @package WorkerProcesses\Process
 */
class TaskConsumer
{
    /**
     * @var AMQPChannel
     */
    private AMQPChannel $channel;

    /**
     * @var array
     */
    private array $secondaryPriorityQueues = [];

    /**
     * @var string
     */
    private string $priorityQueueName;

    /**
     * @var ProcessFunction
     */
    private ProcessFunction $processFunction;

    /**
     * @var integer
     */
    private int $maxBatchCount = 5;

    /**
     * @var AMQPMessage[]
     */
    private array $messages = [];

    /**
     * TaskConsumer constructor.
     * @param AMQPChannel $channel
     * @param array $queues
     * @param string $priorityQueueName
     * @param ProcessFunction $processFunction
     */
    public function __construct(
        AMQPChannel $channel,
        array $queues,
        string $priorityQueueName,
        ProcessFunction $processFunction
    ) {
        $this->channel = $channel;
        $priorityQueueKey = array_search($priorityQueueName, $queues, true);

        if ($priorityQueueKey === false) {
            throw new InvalidArgumentValueException(
                \sprintf('Unknown queue name %s', $priorityQueueName)
            );
        }

        unset($queues[$priorityQueueKey]);
        $this->secondaryPriorityQueues = $queues;
        $this->priorityQueueName = $priorityQueueName;
        $this->processFunction = $processFunction;
    }

    /**
     * @param int $maxBatchCount
     * @return TaskConsumer
     */
    public function setMaxBatchCount(int $maxBatchCount): TaskConsumer
    {
        $this->maxBatchCount = $maxBatchCount;

        return $this;
    }

    /**
     * @return string|null
     */
    private function getNextSecondary(): ?string
    {
        if (empty($this->secondaryPriorityQueues)) {
            return null;
        }

        $rand = array_rand($this->secondaryPriorityQueues);

        return $this->secondaryPriorityQueues[$rand];
    }

    /**
     * @param $queueName
     */
    private function processAndFlush($queueName): void
    {
        $tasks = [];
        foreach ($this->messages as $message) {
            $tasks[] = $this->convertMessageToTask($message);
        }

        call_user_func($this->processFunction, $queueName, $tasks);

        foreach ($this->messages as $message) {
            $this->channel->basic_ack($message->getDeliveryTag());
        }
        $this->messages = [];
    }

    /**
     * @param AMQPMessage $message
     * @return TaskInterface
     */
    private function convertMessageToTask(AMQPMessage $message): TaskInterface
    {
        return \unserialize($message->getBody());
    }

    /**
     * @param string|null $queueName
     * @throws \ErrorException
     */
    public function work(string $queueName = null): void
    {
        if ($queueName === null) {
            $queueName = $this->priorityQueueName;
        }

        $addMessage = function (AMQPMessage $AMQPMessage) use ($queueName) {
            $this->messages[] = $AMQPMessage;
            if ((count($this->messages) % $this->maxBatchCount) === 0) {
                $this->processAndFlush($queueName);
            }
        };

        while (true) {
            $this->channel->basic_consume(
                $queueName,
                '',
                false,
                false,
                false,
                false,
                $addMessage
            );

            while ($this->channel->is_consuming()) {
                try {
                    $this->channel->wait(null, false, 1);
                } catch (AMQPTimeoutException $AMQPTimeoutException) {
                    $this->channel->basic_cancel('');
                    break;
                }
            }

            // need to process and flush remains
            if ($this->messages) {
                $this->processAndFlush($queueName);
            }

            //Try to work with secondary queues if current queue is not priority (recursive)
            if ($queueName !== $this->priorityQueueName) {
                return;
            }

            if ($secondaryQueue = $this->getNextSecondary()) {
                $this->work($secondaryQueue);
            }
        }
    }
}

<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use WorkerProcesses\Process\Processor\ProcessOrderCredit;
use WorkerProcesses\Process\Processor\ProcessOrderKasco;
use WorkerProcesses\Process\Processor\ProcessOrderOsago;
use WorkerProcesses\Process\Processor\ProcessOrderRefinance;
use WorkerProcesses\Process\WorkerApplication;

require_once __DIR__.'/../vendor/autoload.php';

define('AMQP_DEBUG', getenv('TEST_AMQP_DEBUG') !== false ? (bool)getenv('TEST_AMQP_DEBUG') : false);

$exchange = 'worker_orders_router';
$application = new WorkerApplication(
    new AMQPStreamConnection('test_rabbitmq', 5672, 'guest', 'guest', '/'),
    $exchange
);

$application
    ->addProcess(new ProcessOrderCredit())
    ->addProcess(new ProcessOrderKasco())
    ->addProcess(new ProcessOrderOsago())
    ->addProcess(new ProcessOrderRefinance());

return $application;

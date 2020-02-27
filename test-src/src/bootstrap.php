<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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

$logger = new Logger('test');
$logger->pushHandler(new StreamHandler(__DIR__.'/processes.log'), false);

$application
    ->addProcess(new ProcessOrderCredit($logger))
    ->addProcess(new ProcessOrderKasco($logger))
    ->addProcess(new ProcessOrderOsago($logger))
    ->addProcess(new ProcessOrderRefinance($logger));

return $application;

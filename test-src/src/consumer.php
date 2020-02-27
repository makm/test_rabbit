<?php

require_once __DIR__.'/bootstrap.php';

if (empty($argv[1])) {
    die('set priority queue name');
}

define('TEST_PROCESS_SLEEP_TIME', 2);
$queueName = $argv[1];
$taskConsumer = $application->createTaskConsumer($queueName, 5);
$taskConsumer->work();

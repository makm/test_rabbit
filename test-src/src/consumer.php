<?php

require_once __DIR__.'/bootstrap.php';

if (empty($argv[1])) {
    die('set priority queue name');
}

define('TEST_PROCESS_SLEEP_TIME', 2); // sleep for test
define('MAX_BATCH_COUNT', 5); // count messages per batch (in group)
$queueName = $argv[1];
$taskConsumer = $application->createTaskConsumer($queueName, MAX_BATCH_COUNT);
$taskConsumer->work();

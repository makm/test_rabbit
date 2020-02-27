<?php

require_once __DIR__.'/bootstrap.php';

if (empty($argv[1])) {
    die('set priority queue name');
}
$queueName = $argv[1];
$taskConsumer = $application->createTaskConsumer($queueName, 5);
$taskConsumer->work();

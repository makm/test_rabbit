<?php

use WorkerProcesses\Process\Task\TaskOrderCredit;
use WorkerProcesses\Process\Task\TaskOrderKasco;
use WorkerProcesses\Process\Task\TaskOrderOsago;
use WorkerProcesses\Process\Task\TaskOrderRefinance;

require_once __DIR__.'/bootstrap.php';

$taskClasses = [
    TaskOrderCredit::class,
    TaskOrderKasco::class,
    TaskOrderOsago::class,
    TaskOrderRefinance::class,
];


for ($i = 0; $i < 10000; $i++) {
    $class = $taskClasses[array_rand($taskClasses)];
    $task = new $class(['some-value' => random_int(1, 999999)]);
    $application->getTaskProducer()->pushTask($task);
}

die('Done');

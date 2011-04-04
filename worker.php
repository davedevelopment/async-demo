<?php

require 'functions.php';

function cacheData(GearmanJob $job) {
    $n = $job->workload();
    echo '[Worker] Starting to prime cache ' . PHP_EOL;
    $mc = new Memcache();
    $mc->connect('localhost');
    $mc->set('data', getData($job), 0, 300);
    echo '[Worker] Finished priming cache ' . PHP_EOL;
}

$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction('data', 'cacheData');

while ($worker->work()) {
    if (GEARMAN_SUCCESS != $worker->returnCode()) {
        echo "Worker failed: " . $worker->error() . PHP_EOL;
    }
}


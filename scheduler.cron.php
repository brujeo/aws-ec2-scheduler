<?php

require 'bootstrap.php';

use Brujeo\AWS\EC2\Scheduler;
use Symfony\Component\Yaml\Yaml;

$tasks = Yaml::parse(
    file_get_contents(BASE_PATH . '/config/schedule.yml')
);

$scheduler = new Scheduler($tasks);
$scheduler->run();
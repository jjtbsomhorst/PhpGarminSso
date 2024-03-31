<?php

use garmin\sso\GarminClient;

require_once __DIR__ . '/vendor/autoload.php';

$test = new GarminClient();

$test->username('j.somhorst@gmail.com')
    ->password('')
    ->login();

echo count(
$test->getActivities()
);



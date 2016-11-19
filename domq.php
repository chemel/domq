#!/usr/bin/php
<?php

require __DIR__.'/vendor/autoload.php';

use Alc\Domq;

define('MAX_FILE_SIZE', 12600000);

$domq = new Domq();
$domq->run();


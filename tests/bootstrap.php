<?php

$autoloader = realpath(__DIR__ . '/../vendor/autoload.php');
if (!$autoloader) {
    throw new \RuntimeException('The autoloader could not be located. Please run composer install!');
}

require $autoloader;

define('FIXTURE_DIR', realpath(__DIR__ . '/PhproTest/AnnotatedCacheBundle/Fixture'));
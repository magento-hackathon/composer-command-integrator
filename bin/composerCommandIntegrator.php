#!/usr/bin/env php
<?php


if ((!@include __DIR__.'/../../../autoload.php') && (!@include __DIR__.'/../vendor/autoload.php')) {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}

$composer =\Composer\Factory::create(new  \Composer\IO\NullIO());


$application = new \MagentoHackathon\Composer\Command\Slot($composer);
$application->run();


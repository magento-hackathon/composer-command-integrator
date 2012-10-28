#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';


$composer =\Composer\Factory::create(new  \Composer\IO\NullIO());


$application = new \MagentoHackathon\Composer\Command\Slot($composer);
$application->run();


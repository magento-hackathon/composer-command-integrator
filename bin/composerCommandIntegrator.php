#!/usr/bin/env php
<?php


if ((!@include __DIR__.'/../../../autoload.php') && (!@include __DIR__.'/../vendor/autoload.php')) {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}

$consoleIO = new \Composer\IO\ConsoleIO(
    new \Symfony\Component\Console\Input\ArgvInput(),
    new \Symfony\Component\Console\Output\ConsoleOutput(),
    new \Symfony\Component\Console\Helper\HelperSet(
        array(
            new \Symfony\Component\Console\Helper\DialogHelper(),
            new \Symfony\Component\Console\Helper\FormatterHelper(),
            new \Symfony\Component\Console\Helper\ProgressHelper()
        )
    )

);
$nullIO = new  \Composer\IO\NullIO();

$composer = \Composer\Factory::create($consoleIO);


$application = new \MagentoHackathon\Composer\Command\Slot($composer);
$application->run();


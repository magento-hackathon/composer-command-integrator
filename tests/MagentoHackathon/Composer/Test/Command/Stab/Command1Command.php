<?php

namespace MagentoHackathon\Composer\Test\Command\Stab;

use Symfony\Component\Console\Command\Command;

/**
 * Class Command1Command is a Command stab for tests
 *
 * @package MagentoHackathon\Composer\Test\Command\Stab
 */
class Command1Command extends Command
{
    protected function configure()
    {
        $this->setName('test:1');
    }
}
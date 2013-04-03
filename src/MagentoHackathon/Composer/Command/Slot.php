<?php

namespace MagentoHackathon\Composer\Command;

use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Composer\IO\IOInterface;
use Composer\IO\ConsoleIO;
use Composer\Util\ErrorHandler;

/**
 * Class Slot
 * Collects the commands defined in the "extra['composer-command-registry']" node
 * in "magento-module" extensions of the current project
 *
 * @package MagentoHackathon\Composer\Command
 */
class Slot extends \Composer\Console\Application
{
    /**
     * @param $composer
     */
    public function __construct($composer)
    {
        $this->composer = $composer;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCommands()
    {
        $commands = array(new HelpCommand(), new ListCommand());
        $repository = $this->getComposer()->getRepositoryManager()->getLocalRepository();

        foreach ($repository->getPackages() as $package) {
            $extra = $package->getExtra();
            if (isset($extra['composer-command-registry'])) {
                foreach ($extra['composer-command-registry'] as $packageCommand) {
                    if (class_exists($packageCommand)) {
                        $commands[] = new $packageCommand;
                    } else {
                        throw new \Exception('Command ' . $packageCommand . ' not found');
                    }
                }
            }
        }

        return $commands;
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        return parent::doRun($input, $output);
    }
}

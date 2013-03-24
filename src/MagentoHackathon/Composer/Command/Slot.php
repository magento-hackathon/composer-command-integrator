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

class Slot extends \Composer\Console\Application
{
    public function __construct( $composer ){

        $this->setComposer($composer);
        parent::__construct();

    }

    public function getDefaultCommands(){

        $commands = array( new ListCommand());
        
        $repository = $this->getComposer()->getRepositoryManager()->getLocalRepository();
        foreach( $repository->getPackages() as $package ){

            $extra = $package->getExtra();
            if( isset($extra['composer-command-registry']) ){
                foreach($extra['composer-command-registry'] as $packageCommand ){
                    //var_dump($packageCommand);
                    $commands[] = new $packageCommand;
                }
            }
        }


        return $commands;


    }

    public function setComposer($composer){
        $this->composer = $composer;
    }

    public function doRun(InputInterface $input, OutputInterface $output){

        //$this->io = new ConsoleIO($input, $output, $this->getHelperSet());
        //var_dump($this->io);
        //$this->getComposer();
        parent::doRun( $input, $output);
    }
}

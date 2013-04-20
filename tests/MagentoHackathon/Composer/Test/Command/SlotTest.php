<?php

namespace MagentoHackathon\Composer\Test\Command;

use Composer\Composer;
use Composer\Config;
use MagentoHackathon\Composer\Command\Slot;

/**
 * Class SlotTest
 * Test Composer command integrator
 * It allows to register own commands in composer environment
 *
 */
class SlotTest extends \PHPUnit_Framework_TestCase
{
    protected $composer;
    protected $config;
    protected $dm;
    protected $repository;
    protected $repositoryManager;
    protected $io;
    protected $packages = array();
    protected $slotInstance;

    /**
     * @return Slot
     */
    protected function getSlotInstance()
    {
        if (empty($this->slotInstance)) {
            $this->initComposer();
            $this->slotInstance = new Slot($this->composer);
        }

        return $this->slotInstance;
    }


    /**
     * Register package in local repository of scanned packages by Composer
     * should be called before initComposer to register packages
     *
     * @param $package
     */
    protected function registerPackage($package)
    {
        $this->packages[] = $package;
    }

    /**
     * Init very minimum Composer environment to work with "Slot"
     */
    protected function initComposer()
    {
        $this->composer = new Composer();
        $this->config = new Config();
        $this->composer->setConfig($this->config);
        $this->composer->setPackage($this->createPackageMock());

        $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->composer->setDownloadManager($this->dm);

        $this->repositoryManager = $this->getMockBuilder('Composer\Repository\RepositoryManager')
            ->disableOriginalConstructor()
            ->getMock();

        $repository = $this->getMock('Composer\Repository\InstalledRepositoryInterface');
        $repository->expects($this->any())
            ->method('getPackages')
            ->will($this->returnValue($this->packages));
        $this->repositoryManager
            ->expects($this->any())
            ->method('getLocalRepository')
            ->will($this->returnValue($repository));

        $this->composer->setRepositoryManager($this->repositoryManager);

    }

    /**
     * Create package Mock
     *
     * @param array $extraData
     * @return \Composer\Package\RootPackage
     */
    protected function createPackageMock($extraData = array())
    {
        $package = $this->getMockBuilder('Composer\Package\RootPackage')
                ->setConstructorArgs(array(md5(rand()), '1.0.0.0', '1.0.0'))
                ->getMock();

        $package->expects($this->any())
                ->method('getExtra')
                ->will($this->returnValue($extraData));

        return $package;
    }

    /**
     * Test if the "Slot" can be initialized and works without extra data without exceptions
     */
    public function testInitSlot()
    {
        $this->assertInstanceOf('\MagentoHackathon\Composer\Command\Slot', $this->getSlotInstance());
    }

    /**
     * Test if "Slot" collects commands defined in composer-command-registry
     */
    public function testCollectCommands()
    {
        // Arrange
        $this->registerPackage(
            $this->createPackageMock(
                array(
                     'composer-command-registry' => array(
                         "\\MagentoHackathon\\Composer\\Test\\Command\\Stab\\Command1Command",
                     )
                )
            )
        );

        // Act
        $commands = $this->getSlotInstance()->getDefaultCommands();

        // Assert default commands (Help, List) + Stab Command
        $this->assertEquals(3, count($commands));
    }

    /**
     * Test if the "Slot" can work correct with empty arrays of commands
     */
    public function testIfThePackageHasNoCommands()
    {
        // Arrange
        $this->registerPackage(
            $this->createPackageMock(
                array(
                     'composer-command-registry' => array(),
                )
            )
        );

        // Act
        $commands = $this->getSlotInstance()->getDefaultCommands();

        // Assert default commands (Help, List) + Stab Command
        $this->assertEquals(2, count($commands));
    }

    /**
     * Test "Slot" can catch errors if command not exist
     * TODO think about possibility to skip not existed commands
     *
     * @expectedException \Exception
     */
    public function testExceptionNonExistedCommands()
    {
        // Arrange
        $this->registerPackage(
            $this->createPackageMock(
                array(
                     'composer-command-registry' => array(
                         "\\MagentoHackathon\\Composer\\Test\\Command\\Stab\\Command2Command",
                     )
                )
            )
        );

        // Act, Assert
        $this->getSlotInstance();
    }
}

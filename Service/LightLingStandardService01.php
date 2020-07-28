<?php


namespace Ling\Light_LingStandardService\Service;


use Ling\Light\ServiceContainer\LightServiceContainerInterface;
use Ling\Light_PluginInstaller\PluginInstaller\PluginInstallerInterface;

/**
 * The LightLingStandardService01 class.
 */
class LightLingStandardService01 implements PluginInstallerInterface
{

    /**
     * This property holds the container for this instance.
     * @var LightServiceContainerInterface
     */
    protected $container;

    /**
     * This property holds the options for this instance.
     * @var array
     */
    protected $options;




    /**
     * Builds the LightLingStandardService01 instance.
     */
    public function __construct()
    {
        $this->options = [];
        $this->container = null;
    }



    /**
     * Sets the container.
     *
     * @param LightServiceContainerInterface $container
     */
    public function setContainer(LightServiceContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Sets the options.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * @implementation
     */
    public function install()
    {

    }

    /**
     * @implementation
     */
    public function isInstalled(): bool
    {

        return false;
    }

    /**
     * @implementation
     */
    public function uninstall()
    {

    }

    /**
     * @implementation
     */
    public function getDependencies(): array
    {
        return [];
    }

}
<?php

namespace Dami\Cli;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

use Dami\DependencyInjection\DamiExtension;
use Rentgen\DependencyInjection\RentgenExtension;
use Rentgen\DependencyInjection\Compiler\ListenerPass;

class Dami
{
    private $container;

    public function __construct()
    {
        $container = new ContainerBuilder();

        $extensions = array(
            new RentgenExtension(),
            new DamiExtension(),
        );
        foreach ($extensions as $extension) {
            $container->registerExtension($extension);
            $container->loadFromExtension($extension->getAlias());
        }
        $container->compile();

        $this->container = $container;
    }

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function getContainer()
    {
        return $this->container;
    }
}

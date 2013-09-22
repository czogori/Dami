<?php

namespace Dami;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

use Rentgen\RentgenExtension;
use Rentgen\ListenerPass;

class Dami
{
	private $container;	

	public function __construct()
	{
		$container = new ContainerBuilder();		
		
		$fileLocator = new FileLocator(getcwd());
        $configFile = $fileLocator->locate('config.yml');  
        $config = Yaml::parse($configFile);

        $currentEnvironment = $config['environments']['current_environment'];
        
        $connectionConfig = $config['environments'][$currentEnvironment];

        $extensions = array(
        	new RentgenExtension($connectionConfig),
        	new DamiExtension()
        );
        foreach ($extensions as $extension) {
	        $container->registerExtension($extension);
	        $container->loadFromExtension($extension->getAlias());
        }
        $container->addCompilerPass(new ListenerPass(), PassConfig::TYPE_AFTER_REMOVING);
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


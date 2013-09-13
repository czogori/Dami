<?php

namespace Dami;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

use Rentgen\ListenerPass;

class DamiExtension implements ExtensionInterface
{    
    public function load(array $configs, ContainerBuilder $container)
    {       

    	$loader = new YamlFileLoader($container, new FileLocator(array(__DIR__)));
		$locator = new FileLocator(__DIR__);
		$yamlUserFiles = $locator->locate('services.yml', null, false);
		foreach ($yamlUserFiles as $yamlUserFile) {
			$loader->load($yamlUserFile);	
		}        

		$fileLocator = new FileLocator(getcwd());
        $configFile = $fileLocator->locate('config.yml');  
        $config = Yaml::parse($configFile);
        
        $migrationsDirectory = str_replace('@@DAMI_DIRECTORY@@', getcwd(), $config['migrations']);


        $definition = new Definition('Dami\Migration\MigrationFiles');
        $definition->setArguments(array($migrationsDirectory, new Reference('schema_table')));
        $container->setDefinition('migration_files', $definition); 
        
        
        //$container->addCompilerPass(new ListenerPass(), PassConfig::TYPE_AFTER_REMOVING);
    }

    public function getAlias()
    {
        return 'dami';
    }

    public function getXsdValidationBasePath()
	{
	    return false;
	}

	public function getNamespace()
	{
	    return 'http://www.example.com/symfony/schema/';
	}
}
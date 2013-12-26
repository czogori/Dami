<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareCommand extends Command
{
    private $container;

    /**
     * Constructor.
     * 
     * @param string             $name      Command name.
     * @param ContainerInterface $container Container instance.
     */
    public function __construct($name, ContainerInterface $container)
    {
        parent::__construct($name);
        $this->container = $container;
    }

     /**
     * Gets container instance.
     * 
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }
}

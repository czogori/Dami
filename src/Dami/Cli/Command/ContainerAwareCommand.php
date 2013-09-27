<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareCommand extends Command
{
    private $container;

    public function __construct($name = null, ContainerInterface $container)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function getContainer()
    {
        return $this->container;
    }
}

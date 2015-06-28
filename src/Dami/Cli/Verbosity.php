<?php

namespace Dami\Cli;

use Symfony\Component\Console\Output\OutputInterface;

class Verbosity
{
    private $verbosity = OutputInterface::VERBOSITY_NORMAL;

    public function change($verbosity)
    {
        $this->verbosity = $verbosity;
    }

    public function isVerbose()
    {
        return $this->verbosity > OutputInterface::VERBOSITY_NORMAL;
    }
}

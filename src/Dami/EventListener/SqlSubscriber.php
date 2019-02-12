<?php

namespace Dami\EventListener;

use Rentgen\Event\SqlEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Dami\Cli\Verbosity;

class SqlSubscriber implements EventSubscriberInterface
{
    private $verbosity;

    public function __construct(Verbosity $verbosity)
    {
        $this->verbosity = $verbosity;
    }

    public function sqlExecuted(SqlEvent $event)
    {
        if ($this->verbosity->isVerbose()) {
            echo sprintf("\n%s\n", $event->getSql());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'rentgen.sql_executed' => 'sqlExecuted'
        ];
    }
}

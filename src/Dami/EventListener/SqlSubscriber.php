<?php

namespace Dami\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Rentgen\Event\TableEvent;
use Dami\Cli\Verbosity;

class SqlSubscriber implements EventSubscriberInterface
{
    private $verbosity;

    public function __construct(Verbosity $verbosity)
    {
        $this->verbosity = $verbosity;
    }

    public function sqlExecuted(TableEvent $event)
    {
        if ($this->verbosity->isVerbose()) {
            echo "\n" . $event->getSql();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'table.create' => 'sqlExecuted',
            'table.drop' => 'sqlExecuted'
        ];
    }
}

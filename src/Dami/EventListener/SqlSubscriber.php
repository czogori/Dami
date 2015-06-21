<?php

namespace Dami\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Rentgen\Event\TableEvent;

class SqlSubscriber implements EventSubscriberInterface
{
    public function sqlExecuted(TableEvent $event)
    {
        echo $event->getSql();
    }

    public static function getSubscribedEvents()
    {
        return [
            'table.create' => 'sqlExecuted',
            'table.drop' => 'sqlExecuted'
        ];
    }
}

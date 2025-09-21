<?php

namespace App\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class DumpConsoleEventsListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'dump',
            ConsoleEvents::ERROR => 'dump',
            ConsoleEvents::TERMINATE => 'dump',
        ];
    }

    public function dump(ConsoleEvent $event): void
    {
//        var_dump('Console event fired: ' . $event::class);
    }
}

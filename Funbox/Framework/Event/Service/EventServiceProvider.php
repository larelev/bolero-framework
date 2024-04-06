<?php

namespace Funbox\Framework\Event\Service;

use App\events\ContentLengthListener;
use App\Events\InternalErrorListener;
use Funbox\Framework\Dbal\Events\SaveEvent;
use Funbox\Framework\Event\EventDispatcher;
use Funbox\Framework\Http\Event\ResponseEvent;
use Funbox\Framework\Service\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLengthListener::class,
        ],
        SaveEvent::class => [
        ],

    ];

    public function __construct(private EventDispatcher $dispatcher)
    {
    }

    public function register(): void
    {
        // TODO: Implement register() method.
        foreach ($this->listen as $eventClass => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->dispatcher->addListener($eventClass, new $listener);
            }
        }
    }
}

<?php

namespace Bolero\Framework\Event\Service;

use App\Events\ContentLengthListener;
use App\Events\InternalErrorListener;
use Bolero\Framework\Dbal\Events\SaveEvent;
use Bolero\Framework\Event\EventDispatcher;
use Bolero\Framework\Http\Event\ResponseEvent;
use Bolero\Framework\Service\ServiceProviderInterface;

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

    public function __construct(private readonly EventDispatcher $dispatcher)
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

<?php

declare(strict_types=1);

namespace Src\Shared\App\Listeners;

use Src\Shared\App\Events\TestEvent;

class TestListener
{
    /**
     * Handle the event.
     */
    public function handle(TestEvent $event): void
    {
    }
}

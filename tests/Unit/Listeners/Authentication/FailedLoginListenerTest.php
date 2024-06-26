<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Listeners\Authentication\FailedLoginListener;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * FailedLoginListenerTest test class
 */
#[CoversClass(FailedLoginListener::class)]
class FailedLoginListenerTest extends TestCase
{
    #[Test]
    public function test_FailedLoginListener_listen_to_Failed_event()
    {
        Event::fake();

        Event::assertListening(
            Failed::class,
            FailedLoginListener::class
        );
    }
}

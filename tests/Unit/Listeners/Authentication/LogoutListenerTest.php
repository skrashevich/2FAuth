<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Listeners\Authentication\LogoutListener;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * LogoutListenerTest test class
 */
#[CoversClass(LogoutListener::class)]
class LogoutListenerTest extends TestCase
{
    #[Test]
    public function test_LogoutListener_listen_to_Logout_event()
    {
        Event::fake();

        Event::assertListening(
            Logout::class,
            LogoutListener::class
        );
    }
}

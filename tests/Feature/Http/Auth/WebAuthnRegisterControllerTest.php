<?php

namespace Tests\Feature\Http\Auth;

use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Requests\WebauthnAttestationRequest;
use App\Http\Requests\WebauthnAttestedRequest;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Laragear\WebAuthn\Enums\UserVerification;
use Laragear\WebAuthn\JsonTransport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * WebAuthnRegisterControllerTest test class
 */
#[CoversClass(WebAuthnRegisterController::class)]
class WebAuthnRegisterControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_uses_attestation_with_fast_registration_request() : void
    {
        Config::set('webauthn.user_verification', UserVerification::DISCOURAGED);

        $request = $this->mock(WebauthnAttestationRequest::class);

        $request->expects('fastRegistration')->andReturnSelf();
        $request->expects('toCreate')->andReturn(new JsonTransport);

        $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/webauthn/register/options')
            ->assertOk();
    }

    #[Test]
    public function test_uses_attestation_with_secureRegistration_request() : void
    {
        Config::set('webauthn.user_verification', UserVerification::REQUIRED);

        $request = $this->mock(WebauthnAttestationRequest::class);

        $request->expects('secureRegistration')->andReturnSelf();
        $request->expects('toCreate')->andReturn(new JsonTransport);

        $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/webauthn/register/options')
            ->assertOk();
    }

    #[Test]
    public function test_register_uses_attested_request() : void
    {
        $request = $this->mock(WebauthnAttestedRequest::class);

        $request->expects('save')->andReturn();
        $request->expects('user')->andReturn($this->user);

        $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/webauthn/register')
            ->assertNoContent();
    }
}

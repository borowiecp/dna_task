<?php

namespace Tests\Feature;

use App\Models\Merchant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class MerchantControllerTest extends TestCase {
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        Merchant::query()->delete();
    }

    public function test_should_create_merchant() {
        // given
        $merchantName = 'DNA';
        $payload = ['name' => $merchantName];

        // when
        $response = $this->json('post', 'api/merchants', $payload)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->json();

        // then
        $storedMerchant = Merchant::query()->where('merchantId', $response['merchant_id'])->first();
        self::assertEquals($merchantName, $storedMerchant->name);
    }
}

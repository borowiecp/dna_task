<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        User::query()->delete();
        Merchant::query()->delete();
        Account::query()->delete();
    }

    public function test_should_save_user() {
        // given
        $fullName = 'John Doe';
        $email = 'john.doe@digitalnewagency.com';

        $payload = [
            'fullName' => $fullName,
            'email' => $email,
        ];

        // when
        $response = $this->json('post', 'api/users', $payload)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->json();

        // then
        $storedUser = User::query()->where('userId', $response['userId'])->first();
        self::assertEquals($fullName, $storedUser->fullName);
        self::assertEquals($email, $storedUser->email);

        $storedAccount = Account::query()->where('userId', $response['userId'])->first();
        self::assertEquals(0.0, $storedAccount->balance);
        self::assertNotNull($storedAccount->accountId);
    }
}

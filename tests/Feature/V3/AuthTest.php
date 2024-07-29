<?php

namespace Tests\Feature\V3;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
//    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_v3_register()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'name'        => "Ahmed Abdelkader",
            'email'       => "abdelkaderx3@gmail.com",
            'phone'       => "+201022420397",
            'gender'      => "Male",
        ];

        $response = $this->postJson('api/v3/auth/register', $attributes);

        $response->assertSuccessful();
        $this->assertDatabaseHas('users', array_except($attributes, 'gender'));

        $response->assertJsonStructure([
            'message',
            'success',
            'data' => [
                    'is_otp_sent',
            ],
        ]);

        $user = User::latest()->first();

        $this->assertInstanceOf(UserProfile::class, $user->profile);
    }

    public function test_v3_verify_OTP()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'phone'       => "+201022420397",
            'otp'         => "1111",
        ];

        $response = $this->postJson('api/v3/auth/verify-otp', $attributes);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'message',
            'success',
            'data' => [
                    'id',
                    'name',
                    'phone',
                    'profile',
                    'token',
                    // Add other expected fields here
            ],
        ]);
    }
}

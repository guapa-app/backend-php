<?php

namespace App\Services;

use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Models\Admin;
use DB;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Log;
use Spatie\Permission\Models\Role;

/**
 * Authentication service.
 */
class AuthService
{
    private $adminRepository;
    private $tokenUrl;
    private $sendOtpUrl = 'https://verificationapi-v1.sinch.com/verification/v1/verifications';

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->tokenUrl = config('app.url') . '/oauth/token';
    }

    public function authenticate(array $data): ?array
    {
        $data = array_merge([
            'client_id' => config('cosmo.password_client_id'),
            'client_secret' => config('cosmo.password_client_secret'),
            'scope' => '*',
        ], $data);

        try {
            $res = Http::asForm()->post($this->tokenUrl, $data);

            if ($res->status() != 200) {
                return null;
            }

            return $res->json();
        } catch (Exception $e) {
            return null;
        }
    }

    public function logout($user): void
    {
        // Get user access token
        $accessToken = $user->token();
        // Revoke refresh token token
        DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update([
            'revoked' => true,
        ]);
        // Revoke access token
        $accessToken->revoke();
    }

    public function setupAdminAccount(): Admin
    {
        // Check if there are any admin accounts
        // If not create the default account
        $admin = $this->adminRepository->getFirst();

        $email = config('cosmo.admin_email');
        $password = config('cosmo.admin_password');

        if (!$admin && isset($email, $password)) {
            // Create the first admin account
            $admin = $this->adminRepository->create([
                'name' => 'Admin',
                'email' => $email,
                'password' => $password,
            ]);

            try {
                $superadminRole = Role::create(['guard_name' => 'admin', 'name' => 'superadmin']);
                $adminRole = Role::create(['guard_name' => 'admin', 'name' => 'admin']);
                Role::create(['guard_name' => 'admin', 'name' => 'moderator']);
                Role::create(['guard_name' => 'api', 'name' => 'patient']);
                Role::create(['guard_name' => 'api', 'name' => 'doctor']);
                Role::create(['guard_name' => 'api', 'name' => 'manager']);
            } catch (Exception $e) {
                // Default roles already created
            }

            $admin->assignRole('superadmin');
        }

        return $admin;
    }

    public function sendSinchOtp(string $phone)
    {
        $http = new Client;

        $sinchUsername = config('cosmo.sinch_username');
        $sinchPassword = config('cosmo.sinch_password');

        $response = $http->post($this->sendOtpUrl, [
            'body' => json_encode([
                'identity' => [
                    'type' => 'number',
                    'endpoint' => $phone,
                ],
                'method' => 'sms',
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($sinchUsername . ':' . $sinchPassword),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function verifySinchOtp(string $phone, string $otp): bool
    {
        $http = new Client;

        $sinchUsername = config('cosmo.sinch_username');
        $sinchPassword = config('cosmo.sinch_password');

        try {
            $response = $http->put($this->sendOtpUrl . '/number/' . $phone, [
                'body' => json_encode([
                    'sms' => [
                        'code' => $otp,
                    ],
                    'method' => 'sms',
                ]),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($sinchUsername . ':' . $sinchPassword),
                ],
            ]);

            $result = json_decode((string) $response->getBody(), true);
            Log::error(json_encode($result));

            return is_array($result) && isset($result['status']) && $result['status'] === 'SUCCESSFUL';
        } catch (Exception $e) {
            // Report the error or do anything with it
            return false;
        }
    }
}

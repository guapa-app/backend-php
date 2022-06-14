<?php

namespace App\Grants;

use App\Helpers\Common;
use App\Services\AuthService;
use Hamedov\PassportGrants\PassportGrant;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

class Sinch extends PassportGrant
{
	/**
	 * Unique string that identifies this grant.
	 * and will be used as grant_type during access
	 * token requests.
	 * @var string
	 */
    protected $identifier = 'sinch_verify';

    /**
     * Required parameters in access token request
     * The parameters used for authentication instead of
     * username and password.
     * @var array
     */
    protected $authParams = [
    	'phone_number', 'otp',
    ];

    /**
     *  Retrieve a user by the given auth parameters.
     *
     * @param \Illuminate\Database\Eloquent\Model  $model The model being authenticated
     * @param array  $authParams Request parameters used to authenticate the user
     * @param string $guard          The guard used for authentication
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface  $clientEntity
     *
     * @return \Laravel\Passport\Bridge\User|null
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    protected function getUserEntityByAuthParams(Model $model, $authParams,
        $guard, $grantType, ClientEntityInterface $clientEntity)
    {
        // It is hard to get a valid firebase jwt token while testing
        // So we will override verification while testing
        $isTesting = config('app.env') === 'testing';
        if ($isTesting) {
            // Check if testing requires authentication to fail
            if ($authParams['jwt_token'] === 'wrongjwttoken' || $authParams['otp'] === 'wrongotp') {
                // Return to cause authentication to fail with 401
                return;
            }
        }

        $authService = app(AuthService::class);

        $isOtpCorrect = $isTesting || $authService->verifySinchOtp($authParams['phone_number'], $authParams['otp']);

        if (!$isOtpCorrect) {
            return;
        }

        // Get user with provided phone number
        $user = $model->whereIn('phone', Common::getPhoneVariations($authParams['phone_number']))->first();

        if ($user) {
            if ($user->status != 'Active') {
                throw new RuntimeException('Your account is closed.');
            }
        } else {
            $user = $this->createNewUser($model, $authParams['phone_number']);
        }

        return new User($user->getAuthIdentifier());
    }

    /**
     * Create new user using phone number
     */
    public function createNewUser($model, $phone)
    {
        return $model->create([
            'phone' => $phone,
            'phone_verified_at' => now()->toDateTimeString(),
        ]);
    }
}

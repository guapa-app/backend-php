<?php

namespace App\Grants;

use App\Helpers\Common;
use Firebase\Auth\Token\Verifier;
use Hamedov\PassportGrants\PassportGrant;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class FirebasePhone extends PassportGrant
{
    /**
     * Unique string that identifies this grant.
     * and will be used as grant_type during access
     * token requests.
     * @var string
     */
    protected $identifier = 'firebase_phone';

    /**
     * Required parameters in access token request
     * The parameters used for authentication instead of
     * username and password.
     * @var array
     */
    protected $authParams = [
        'phone_number', 'jwt_token',
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
    protected function getUserEntityByAuthParams(
        Model $model,
        $authParams,
        $guard,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        // Do your logic to authenticate the user
        // Such as contacting facebook server to validate
        // user facebook access token.
        // Return false or void if authentication fails.
        // This will throw OAuthServerException.
        $projectId = config('cosmo.firebase_project_id');

        // Get user profile from facebook using provided token
        $verifier = new Verifier($projectId);

        // It is hard to get a valid firebase jwt token while testing
        // So we will override verification while testing
        if (config('app.env') === 'testing') {
            // Check if testing requires authentication to fail
            if ($authParams['jwt_token'] === 'wrongjwttoken') {
                // Return to cause authentication to fail with 401
                return;
            }

            // Otherwise fake the data that should be returned from firebase
            $data = $request_params;
        } else {
            $verifiedIdToken = $verifier->verifyIdToken($authParams['jwt_token']);

            // Get data stored in the token
            $data = $verifiedIdToken->getClaims();
        }

        if (!isset($data['phone_number']) || $data['phone_number'] != $authParams['phone_number']) {
            return;
        }

        // Get user with provided phone number
        $user = $model->whereIn('phone', Common::getPhoneVariations($data['phone_number']))->first();

        if ($user) {
            if ($user->status != 'Active') {
                throw new RuntimeException('Your account is closed.');
            }
        } else {
            $user = $this->createNewUser($model, $data['phone_number']);
        }

        return new User($user->getAuthIdentifier());
    }

    /**
     * Create new user using phone number.
     */
    public function createNewUser($model, $phone)
    {
        return $model->create([
            'phone' => $phone,
            'phone_verified_at' => now()->toDateTimeString(),
        ]);
    }
}

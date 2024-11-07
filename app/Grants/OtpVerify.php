<?php

namespace App\Grants;

use App\Helpers\Common;
use App\Services\SMSService;
use Hamedov\PassportGrants\PassportGrant;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

class OtpVerify extends PassportGrant
{
    /**
     * Unique string that identifies this grant.
     * and will be used as grant_type during access
     * token requests.
     * @var string
     */
    protected $identifier = 'otp_verify';

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
     * @param Model $model The model being authenticated
     * @param array  $authParams Request parameters used to authenticate the user
     * @param string $guard          The guard used for authentication
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     *
     * @return User|null
     * @throws OAuthServerException
     */
    protected function getUserEntityByAuthParams(Model $model, $authParams, $guard, $grantType, ClientEntityInterface $clientEntity)
    {
        $smsService = app(SMSService::class);

        $isOtpCorrect = $smsService->verifyOtp($authParams['phone_number'], $authParams['otp']);

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

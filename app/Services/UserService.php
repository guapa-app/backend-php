<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use App\Contracts\FcmNotifiable;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Device;
use Carbon\Carbon;

/**
 * User service
 */
class UserService
{
	private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * Create new user with relations
	 * 
	 * @param  array  $data
	 * @return \App\Models\User
	 */
	public function create(array $data): User
	{
		// Create user
    	$user = $this->userRepository->create($data);

    	// Assign patient role
		$user->assignRole('patient');

    	// Update profile
    	if (isset($data['profile'])) {
    		$this->updateProfile($user, (array) $data['profile']);
    	}

    	if (isset($data['password'])) {
    		$this->setPassword($user, $data['password']);
    	}

    	return $user;
	}

	public function update($id, array $data): User
	{
		// Update user data
		$user = $this->userRepository->update($id, $data);

		// Update profile
    	if (isset($data['profile'])) {
    		$this->updateProfile($user, (array) $data['profile']);
    	}

    	// Update password
    	$this->updatePassword($user, $data);

    	return $user;
	}

	/**
	 * Update user address
	 * @param  User   $user
	 * @param  array $data
	 * @return \App\Models\User
	 */
	public function updateAddress(User $user, array $data) : User
	{
		$address = $user->address()->updateOrCreate([
			'addressable_id' => $user->id,
			'addressable_type' => $user->getMorphClass(),
		], $data);

		$user->load('address');

		return $user;
	}

	/**
	 * Update user profile
	 * @param  User   $user
	 * @param  array  $data
	 * @return \App\Models\User
	 */
	public function updateProfile(User $user, array $data): User
	{
		// Update user settings
		if (isset($data['settings'])) {
			$data['settings'] = json_encode($data['settings']);
		}

		if (isset($data['about'])) {
			$data['about'] = strip_tags($data['about']);
		}

		$profile = $user->profile()->updateOrCreate([
			'user_id' => $user->id,
		], $data);

		// Update user photo
		$photoData = Arr::only($data, ['photo']);
		$this->updatePhoto($profile, $photoData);

		$user->setRelation('profile', $profile);

		return $user;
	}

	public function updatePhoto(UserProfile $profile, array $data): UserProfile
	{
		if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
			$profile->addMedia($data['photo'])->toMediaCollection('avatars');
		} elseif ($this->userRepository->isAdmin() && ! isset($data['photo'])) {
			// Delete all profile media
			// As there is only one collection - avatars
			$profile->media()->delete();
		}

		return $profile;
	}

	public function updatePassword(User $user, array $data) : User
	{
		$currentUser = auth()->user();

		if ( ! isset($data['password'], $currentUser)) {
			return $user;
		}

		if ( ! isset($data['oldpassword']) && ! isset($data['reset_token'])) {
			$message = $currentUser->isAdmin() ?
				'Your admin password is incorrect' :
				'Please provide old password or reset token';

			throw ValidationException::withMessages([
        		'oldpassword' => $message,
        	]);
		}

		// Check for old password or admin password for admins
		if (isset($data['oldpassword'])) {
			if ( ! \Hash::check($data['oldpassword'], $currentUser->password)) {
				$message = $currentUser->isAdmin() ?
					'Your admin password is incorrect' :
					'Old password is incorrect';

				throw ValidationException::withMessages([
	        		'oldpassword' => $message,
	        	]);
			}
		}

		if (isset($data['reset_token'])) {
			// Check reset token
			$token = \DB::table(config('auth.passwords.users.table'))
				->where([
					'token' => $data['reset_token'],
					'email' => auth()->user()->email,
				])->first();

			if ( ! $token || now()->subMinutes(15)->gt(Carbon::parse($token->created_at))) {
				throw ValidationException::withMessages([
	        		'reset_token' => 'The reset token is invalid or has expired',
	        	]);
			}
		}

		$this->setPassword($user, $data['password']);

		return $user;
	}

	public function setPassword(User $user, $password): User
	{
		// Set user password
		$user->password = \Hash::make($password);
		$user->save();
		return $user;
	}

	public function addDevice(FcmNotifiable $user, array $data): Device
	{
		return $user->devices()->updateOrCreate([
			'user_id' => $user->id,
			'user_type' => $user->getMorphClass(),
			'guid' => $data['guid'],
		], $data);
	}
}

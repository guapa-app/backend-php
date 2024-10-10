<?php

namespace App\Services\V3_1;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\ReferralCodeUsage;
use App\Services\LoyaltyPointsService;

class ReferralCodeService
{
    public function __construct(
        public LoyaltyPointsService $loyaltyPointsService,
    ) {}

    /**
     * createReferralCodeUsage
     *
     * @param  User $invitee
     * @param  string $referral_code
     * @return void
     */
    public function createReferralCodeUsage(User $invitee, string $referral_code)
    {
        // Check if a referral code was provided
        if (!empty($referral_code)) {
            $inviter = UserProfile::where('referral_code', $referral_code)->first();

            // Log the invitation in referral_code_usages
            ReferralCodeUsage::create([
                'user_id' => $inviter->user_id, // The inviter's profile
                'invitee_id' => $invitee->id, // The new user (invitee)
            ]);

            // Allocate points to both the inviter and invitee
            $this->loyaltyPointsService->addFriendRegistrationsPoints($inviter->user, $invitee);
        }
    }
}

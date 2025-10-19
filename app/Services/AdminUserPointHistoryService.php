<?php

namespace App\Services;

use App\Models\AdminUserPointHistory;


class AdminUserPointHistoryService
{

    function addHistory($userId, $adminId, $action, $points, $reason)
    {
        AdminUserPointHistory::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'action' => $action,
            'points' => $points,
            'reason' => $reason,
        ]);
    }
}

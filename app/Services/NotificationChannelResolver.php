<?php

namespace App\Services;

use App\Models\NotificationSetting;

class NotificationChannelResolver
{
    public function resolve(string $module, ?int $adminId = null): array
    {
        // 1. Check for admin-specific setting
        $setting = NotificationSetting::where('notification_module', $module)
            ->where('admin_id', $adminId)
            ->first();
        if ($setting) {
            return [$setting->channels];
        }

        // 2. Check for super admin (global) setting
        $setting = NotificationSetting::where('notification_module', $module)
            ->whereNull('admin_id')
            ->first();
        if ($setting) {
            return [$setting->channels];
        }

        // 3. Auto-create setting with smart default and return it
        $defaultChannel = $this->getSmartDefault($module);

        // Create global setting for this module
        NotificationSetting::create([
            'notification_module' => $module,
            'admin_id' => null,
            'channels' => $defaultChannel,
            'created_by_super_admin' => true,
        ]);

        return [$defaultChannel];
    }

    /**
     * Determine smart default channel based on module pattern
     */
    protected function getSmartDefault(string $module): string
    {
        // SMS patterns
        if (str_contains($module, 'sms') || str_contains($module, 'otp')) {
            return 'sms';
        }

        // Email patterns
        if (str_contains($module, 'mail') || str_contains($module, 'email')) {
            return 'mail';
        }

        // WhatsApp patterns
        if (str_contains($module, 'whatsapp') || str_contains($module, 'campaign')) {
            return 'whatsapp';
        }

        // Firebase patterns (real-time/urgent notifications)
        if (
            str_contains($module, 'order') || str_contains($module, 'urgent') ||
            str_contains($module, 'new-') || str_contains($module, 'update-')
        ) {
            return 'firebase';
        }

        // In-app patterns (community, messages, tickets)
        if (
            str_contains($module, 'community') || str_contains($module, 'message') ||
            str_contains($module, 'comment') || str_contains($module, 'ticket') ||
            str_contains($module, 'support') || str_contains($module, 'review')
        ) {
            return 'in_app';
        }

        // Default fallback
        return 'in_app';
    }
}

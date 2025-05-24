# Notification System Documentation

## Overview

This system manages notification preferences and sending for all modules in the application. Notification settings are stored in the database and used to determine which channels to use for each module and admin. All notifications are sent to an external service for delivery.

---

## Database Structure

**Table:** `notification_settings`

| Field                  | Type                | Description                                 |
|------------------------|---------------------|---------------------------------------------|
| id                     | bigint (auto inc)   | Primary key                                 |
| module                 | string              | Notification module/type (enum value)       |
| admin_id               | unsignedBigInteger  | Admin user id (nullable for global)         |
| channels               | json                | Array of channels (e.g., ["sms", "mail"])   |
| created_by_super_admin | boolean             | True if set by super admin                  |
| created_at             | timestamp           |                                             |
| updated_at             | timestamp           |                                             |

- **channels**: JSON array, e.g., `["database", "firebase", "whatsapp", "mail", "sms"]`
- **module**: String, matches enum in `NotificationTypeEnum`
- **admin_id**: Null for global (super admin) settings

---

## Enum/Module/Channel Naming

- **Modules**: Defined in `App\Enums\NotificationTypeEnum`
    - Example values: `new-order`, `new-offer`, `message`, `sms-otp`
- **Channels**: Hardcoded in Filament resource and used in external service
    - Example: `database`, `firebase`, `whatsapp`, `mail`, `sms`

---

## Managing Settings

### Super Admin
- Can create/edit global settings for each module (admin_id = null)
- Can also create settings for specific admins if needed

### Admin
- Can view global settings
- Can create/edit their own settings (admin_id = their id)
- Their settings override the global default for themselves

---

## Notification Sending Flow

1. **Trigger**: An event (e.g., new order, OTP) triggers a notification.
2. **Resolve Channels**: `NotificationChannelResolver` checks for admin-specific setting, then global, then fallback.
3. **Send**: `ExternalNotificationService` sends the notification data and channels to the external notification service via HTTP API.
4. **External Service**: Handles delivery, logging, and actual sending.

---

## Authentication for External Service

- Use a service token or API key in `.env` and `config/services.php`:
    ```env
    EXTERNAL_NOTIFICATION_SERVICE_TOKEN=your_token_here
    ```
- When sending a notification, include this token in the HTTP headers:
    ```php
    $response = Http::withToken(config('services.external_notification.token'))
        ->post(config('services.external_notification.endpoint'), $data);
    ```
- The external service should validate this token before processing the request.

---

## Use Cases

- **Super Admin sets global defaults** for all modules.
- **Admin overrides** a setting for themselves.
- **All notification sending** is routed through the external service.
- **Fallback**: If no admin-specific setting, use global; if no global, use hardcoded fallback (e.g., `["database"]`).

---

## Example: Sending a Notification

```php
app(\App\Services\ExternalNotificationService::class)->send([
    'module' => 'sms-otp',
    'title' => 'Your OTP Code',
    'summary' => 'Use this code to verify your phone.',
    'data' => ['otp' => 123456],
    'recipient_id' => $user->id,
    'channels' => app(\App\Services\NotificationChannelResolver::class)->resolve('sms-otp', $user->id),
]);
```

---

## How to Add New Modules/Channels

- **Add a module**: Add a new case to `NotificationTypeEnum`.
- **Add a channel**: Add to the channels list in the Filament resource and ensure the external service supports it.
- **Seed defaults**: Update the seeder if you want a default for the new module.

---

## Seeder and Default Behavior

- The seeder (`DatabaseSeeder.php`) ensures every module has a global default setting.
- Run `php artisan db:seed` to populate defaults.
- Admins will see these defaults and can override them as needed.

---

## Best Practices & Caveats

- Always keep `NotificationTypeEnum` and the Filament channels list in sync with your business logic and external service.
- Use the Filament UI for easy management of settings.
- Protect the external notification service with proper authentication.
- Test new modules/channels end-to-end after adding.

---

## Extension Points

- Add more notification modules by extending the enum.
- Add more channels as your business grows.
- Integrate with more external services as needed.

---

## Contact

For further questions or to extend the system, contact the backend team or refer to this documentation. 
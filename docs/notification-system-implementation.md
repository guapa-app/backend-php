# Notification System Implementation Guide

## Overview

This document describes the complete implementation of the configurable notification system that routes all notifications through an external service based on admin preferences.

---

## ✅ **Completed Implementation**

### 1. **Database Structure**
- **Table**: `notification_settings`
- **Columns**: 
  - `notification_module` (string) - The notification type/module
  - `admin_id` (nullable int) - Specific admin ID or null for global
  - `channels` (string) - Single channel: 'in_app', 'firebase', 'whatsapp', 'mail', 'sms'
  - `created_by_super_admin` (boolean)
  - Unique constraint on [notification_module, admin_id]

### 2. **Core Services**

#### **NotificationChannelResolver**
- **Location**: `app/Services/NotificationChannelResolver.php`
- **Features**:
  - Three-tier resolution: Admin-specific → Global → Auto-create with smart defaults
  - **Smart defaults** based on module patterns:
    - SMS patterns ('sms', 'otp') → `sms`
    - Email patterns ('mail', 'email') → `mail` 
    - WhatsApp patterns ('whatsapp', 'campaign') → `whatsapp`
    - Firebase patterns ('order', 'new-', 'update-') → `firebase`
    - In-app patterns ('community', 'message', 'comment', 'ticket') → `in_app`
    - Default fallback → `in_app`
  - **Auto-creation**: Automatically creates global settings for new modules

#### **UnifiedNotificationService**
- **Location**: `app/Services/UnifiedNotificationService.php`
- **Features**:
  - Single entry point for all notification sending
  - Automatic channel resolution using admin context
  - Support for single and multiple recipients
  - Integration with ExternalNotificationService

#### **NotificationMigrationHelper**
- **Location**: `app/Services/NotificationMigrationHelper.php`
- **Features**:
  - Pre-built methods for common notification types
  - Easy migration from Laravel notifications to unified system
  - Consistent data formatting

### 3. **Module Management**

#### **Enhanced NotificationTypeEnum**
- **Location**: `app/Enums/NotificationTypeEnum.php`
- **Modules**: 16 predefined modules including:
  - Core: new-order, new-offer, message, sms-otp
  - Community: comments, community, new-review, new-like
  - Support: user-ticket, support-message
  - System: general, push-notifications
  - Products: new-product, new-procedure
  - Updates: update-order, update-consultation

#### **Dynamic Module Support**
- System accepts **any module name**, not just predefined enums
- Auto-creates settings with smart defaults for new modules
- Supports dynamic module patterns (e.g., 'new-{productType}')

### 4. **Filament Admin Interface**

#### **NotificationSettingResource**
- **Location**: `app/Filament/Admin/Resources/AdminSetting/NotificationSettingResource.php`
- **Features**:
  - Super admins: See all settings, manage global and admin-specific
  - Normal admins: See global settings + their own, create personal overrides
  - Single channel selection per module (dropdown)
  - Proper permissions and query filtering

### 5. **API Endpoints**
- **Routes**: `routes/v1/api/notification_settings.php`
- **Controller**: `app/Http/Controllers/Api/NotificationSettingController.php`
- **Features**:
  - CRUD operations for notification settings
  - Permission-based access (admins can only manage their own)
  - RESTful API design

### 6. **Default Settings**
- **Seeder**: `database/seeders/NotificationSettingsSeeder.php`
- **Coverage**: All 16 modules with appropriate default channels
- **Migration**: Converted existing JSON arrays to single channel strings

---

## 🎯 **Usage Examples**

### **Basic Notification Sending**
```php
// Using UnifiedNotificationService
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'new-order',
    title: 'New Order Received',
    summary: 'You have a new order from customer',
    recipientId: $vendor->id,
    data: ['order_id' => $order->id]
);
```

### **Using Migration Helper**
```php
// For common notification types
$helper = app(\App\Services\NotificationMigrationHelper::class);
$helper->sendOrderNotification($order, $vendor, $isUpdate = false);
$helper->sendProductNotification($product, $user);
$helper->sendReviewNotification($order, $vendor);
```

### **Multiple Recipients**
```php
$unifiedService->sendToMultiple(
    module: 'new-offer',
    title: 'Special Offer',
    summary: 'Check out our latest offer',
    recipientIds: [1, 2, 3, 4],
    data: ['offer_id' => $offer->id]
);
```

---

## 🔧 **Configuration Management**

### **Super Admin Capabilities**
- Create/edit global defaults for any module
- Create/edit settings for specific admins
- View all notification settings
- Manage system-wide notification preferences

### **Normal Admin Capabilities**
- View global settings (read-only)
- Create personal overrides for any module
- Only see and manage their own settings
- Settings override global defaults for their notifications

### **Priority System**
1. **Admin-specific setting** (highest priority)
2. **Global setting** (medium priority)  
3. **Auto-created smart default** (fallback)

---

## 📊 **Smart Default Logic**

The system automatically determines appropriate channels based on module patterns:

| Pattern | Examples | Default Channel |
|---------|----------|----------------|
| SMS/OTP | 'sms-otp', 'phone-verification' | `sms` |
| Email | 'email-notification', 'mail-digest' | `mail` |
| WhatsApp | 'whatsapp-campaign', 'wa-reminder' | `whatsapp` |
| Firebase | 'new-order', 'update-status' | `firebase` |
| In-App | 'comments', 'messages', 'tickets' | `in_app` |

---

## 🚀 **Migration Strategy**

### **Phase 1: Infrastructure (✅ Complete)**
- Database structure and migrations
- Core services and channel resolution
- Filament admin interface
- Default settings seeding

### **Phase 2: Service Integration (✅ In Progress)**
- UnifiedNotificationService implementation
- Migration helper utilities
- Updated CommentObserver and DatabaseNotificationRepository

### **Phase 3: Notification Class Migration (📋 Next Steps)**
- Update existing Laravel notification classes to use unified service
- Replace hardcoded `via()` methods with resolver
- Implement migration helper usage

### **Phase 4: Testing and Validation**
- Comprehensive testing of all notification types
- Validation of channel resolution logic
- Performance testing of external service integration

---

## 🔐 **Security and Permissions**

### **Access Control**
- Admin authentication required for all operations
- Permission method: `canManageNotificationSettings()`
- Super admin detection: `isSuperAdmin()` method

### **Data Protection**
- Admin-specific settings are isolated
- Global settings protected from unauthorized modification
- Audit trail via `created_by_super_admin` flag

---

## 📈 **Performance Considerations**

### **Caching Strategy**
- NotificationChannelResolver results could be cached
- Database queries optimized with proper indexing
- External service calls are fire-and-forget

### **Auto-Creation**
- New modules automatically get settings on first use
- Prevents system failures for undefined modules
- Smart defaults ensure appropriate channel selection

---

## 🛠️ **Maintenance**

### **Adding New Modules**
1. Add to `NotificationTypeEnum` (optional)
2. Run seeder to create default setting
3. Use in notification calls immediately

### **Adding New Channels**
1. Update channel options in Filament resource
2. Update smart default patterns if needed
3. Configure external service to handle new channel

### **Monitoring**
- External service logs all delivery attempts
- Database tracks all notification settings changes
- Admin interface provides visibility into configuration

---

## ✅ **Next Steps**

1. **Complete Migration**: Update remaining notification classes
2. **Testing**: Comprehensive testing of all notification flows
3. **Documentation**: Update API documentation with new endpoints
4. **Performance**: Implement caching for channel resolution
5. **Monitoring**: Set up alerts for external service failures 
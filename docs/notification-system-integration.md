# Notification System Integration Guide

## Overview

This document provides complete integration specifications for the configurable notification system. All notifications are routed through an external service based on admin-configurable channel preferences.

---

## 🏗️ **System Architecture**

### **Flow Diagram**
```
Application Event → UnifiedNotificationService → ChannelResolver → ExternalNotificationService → External API
                                                      ↓
                                              NotificationSettings Database
```

### **Core Components**
1. **UnifiedNotificationService**: Single entry point for all notifications
2. **NotificationChannelResolver**: Determines appropriate channel based on admin settings
3. **ExternalNotificationService**: Handles HTTP API calls to external service
4. **NotificationSettings**: Database configuration per module/admin

---

## 📊 **Notification Flow by Type**

### **1. Order Notifications**

#### **New Order Flow**
```php
// Trigger: New order created
$order = Order::create($orderData);

// Notification sent to vendor
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'new-order',
    title: 'New Order Received',
    summary: "New order #{$order->id} from {$order->user->name}",
    recipientId: $order->vendor_id,
    data: [
        'order_id' => $order->id,
        'customer_name' => $order->user->name,
        'total_amount' => $order->total,
        'items_count' => $order->items->count()
    ]
);
```

#### **Order Update Flow**
```php
// Trigger: Order status changed
$order->update(['status' => 'confirmed']);

// Notification sent to customer
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'update-order',
    title: 'Order Status Updated',
    summary: "Your order #{$order->id} is now {$order->status}",
    recipientId: $order->user_id,
    data: [
        'order_id' => $order->id,
        'new_status' => $order->status,
        'vendor_name' => $order->vendor->name
    ]
);
```

### **2. Product/Service Notifications**

#### **New Product Flow**
```php
// Trigger: Product created/published
$product = Product::create($productData);

// Notification sent to followers/interested users
$followers = $product->vendor->followers;
foreach ($followers as $follower) {
    app(\App\Services\UnifiedNotificationService::class)->send(
        module: 'new-product',
        title: 'New Product Available',
        summary: "{$product->vendor->name} added a new product: {$product->title}",
        recipientId: $follower->id,
        data: [
            'product_id' => $product->id,
            'vendor_id' => $product->vendor_id,
            'product_title' => $product->title,
            'product_image' => $product->image?->url
        ]
    );
}
```

### **3. Offer Notifications**

#### **New Offer Flow**
```php
// Trigger: Special offer created
$offer = Offer::create($offerData);

// Notification sent to target audience
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'new-offer',
    title: $offer->product->title,
    summary: "Special offer: {$offer->discount_string} off on {$offer->product->title}",
    recipientId: $user->id,
    data: [
        'offer_id' => $offer->id,
        'product_id' => $offer->product_id,
        'discount' => $offer->discount_string,
        'valid_until' => $offer->valid_until,
        'product_image' => $offer->product->image?->url
    ]
);
```

### **4. Community Notifications**

#### **Comments Flow**
```php
// Trigger: New comment on post
// Observer: CommentObserver::created()
$comment = Comment::create($commentData);

// Notification sent to post owner
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'comments',
    title: 'New Comment',
    summary: "تم إضافة تعليق جديد على منشورك من قبل {$comment->user->name}",
    recipientId: $comment->post->user_id,
    data: [
        'comment_id' => $comment->id,
        'post_id' => $comment->post_id,
        'commenter_name' => $comment->user->name
    ]
);
```

#### **Reviews Flow**
```php
// Trigger: Order reviewed
$review = Review::create($reviewData);

// Notification sent to vendor
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'new-review',
    title: 'New Review Received',
    summary: "تم تقييم الطلب رقم {$review->order_id}",
    recipientId: $review->order->vendor_id,
    data: [
        'review_id' => $review->id,
        'order_id' => $review->order_id,
        'rating' => $review->rating,
        'customer_name' => $review->order->user->name
    ]
);
```

### **5. Support Notifications**

#### **Support Ticket Flow**
```php
// Trigger: New support message/reply
$supportMessage = SupportMessage::create($messageData);

// Notification sent to relevant party
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'support-message',
    title: 'Support Message',
    summary: 'You have a new reply to your support ticket',
    recipientId: $supportMessage->recipient_id,
    data: [
        'support_message_id' => $supportMessage->id,
        'ticket_id' => $supportMessage->ticket_id,
        'sender_name' => $supportMessage->sender->name
    ]
);
```

### **6. SMS/OTP Notifications**

#### **OTP Flow**
```php
// Trigger: OTP verification required
$otp = generateOTP($user);

// Notification sent via SMS
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'sms-otp',
    title: 'Verification Code',
    summary: "Your verification code is: {$otp}",
    recipientId: $user->id,
    data: [
        'otp_code' => $otp,
        'expires_at' => now()->addMinutes(5),
        'phone_number' => $user->phone
    ]
);
```

---

## 🔧 **Channel Configuration by Module**

### **Default Channel Matrix**

| Module | Default Channel | Use Case |
|--------|----------------|-----------|
| `new-order` | `firebase` | Real-time vendor notifications |
| `update-order` | `firebase` | Urgent status updates |
| `new-offer` | `firebase` | Time-sensitive offers |
| `new-product` | `firebase` | Product announcements |
| `comments` | `in_app` | Community interactions |
| `new-review` | `in_app` | Review notifications |
| `support-message` | `in_app` | Support communications |
| `sms-otp` | `sms` | Security verification |
| `message` | `in_app` | Chat/messaging |
| `general` | `in_app` | Default fallback |

### **Channel Capabilities**

| Channel | Best For | Response Time | Delivery Method |
|---------|----------|---------------|-----------------|
| `firebase` | Urgent, real-time | Instant | Push notification |
| `whatsapp` | Campaigns, reminders | Near-instant | WhatsApp message |
| `sms` | OTP, critical alerts | Near-instant | SMS text |
| `mail` | Newsletters, reports | Minutes | Email |
| `in_app` | Community, support | Next app open | In-app notification |

---

## 🚀 **External Service API Integration**

### **Endpoint Specification**

#### **Base Configuration**
```php
// config/services.php
'external_notification' => [
    'endpoint' => env('EXTERNAL_NOTIFICATION_ENDPOINT'),
    'token' => env('EXTERNAL_NOTIFICATION_TOKEN'),
    'timeout' => env('EXTERNAL_NOTIFICATION_TIMEOUT', 30),
],
```

#### **Environment Variables**
```env
EXTERNAL_NOTIFICATION_ENDPOINT=https://your-notification-service.com/api/notifications
EXTERNAL_NOTIFICATION_TOKEN=your_secure_api_token
EXTERNAL_NOTIFICATION_TIMEOUT=30
```

### **Request Format**

#### **HTTP Request**
```http
POST /api/notifications
Content-Type: application/json
Authorization: Bearer {token}

{
    "module": "new-order",
    "title": "New Order Received",
    "summary": "New order #12345 from John Doe",
    "recipient_id": 123,
    "channels": ["firebase"],
    "data": {
        "order_id": 12345,
        "customer_name": "John Doe",
        "total_amount": 150.00,
        "items_count": 3
    }
}
```

#### **Batch Request (Multiple Recipients)**
```http
POST /api/notifications/batch
Content-Type: application/json
Authorization: Bearer {token}

{
    "module": "new-offer",
    "title": "Special Offer",
    "summary": "50% off on selected items",
    "recipient_ids": [123, 456, 789],
    "channels": ["firebase"],
    "data": {
        "offer_id": 567,
        "discount": "50%",
        "valid_until": "2024-12-31"
    }
}
```

### **Response Format**

#### **Success Response**
```json
{
    "success": true,
    "message": "Notification sent successfully",
    "delivery_id": "uuid-delivery-tracking-id",
    "timestamp": "2024-01-15T10:30:00Z"
}
```

#### **Error Response**
```json
{
    "success": false,
    "error": "Invalid recipient",
    "error_code": "INVALID_RECIPIENT",
    "message": "Recipient ID 123 not found"
}
```

### **Error Handling**

#### **Network Errors**
```php
// ExternalNotificationService handles retries and failures gracefully
try {
    $response = Http::timeout(30)
        ->withToken($token)
        ->post($endpoint, $payload);
    
    return $response->successful();
} catch (RequestException $e) {
    // Log error but don't fail the application
    Log::error('Notification service unavailable', [
        'error' => $e->getMessage(),
        'payload' => $payload
    ]);
    return false;
}
```

---

## 🎯 **Usage Patterns**

### **Pattern 1: Direct Service Usage**
```php
// For simple notifications
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'notification-type',
    title: 'Title',
    summary: 'Summary', 
    recipientId: $userId,
    data: ['key' => 'value']
);
```

### **Pattern 2: Migration Helper Usage**
```php
// For common notification types
$helper = app(\App\Services\NotificationMigrationHelper::class);

// Order notifications
$helper->sendOrderNotification($order, $vendor);
$helper->sendOrderNotification($order, $customer, $isUpdate = true);

// Product notifications
$helper->sendProductNotification($product, $user);

// Review notifications
$helper->sendReviewNotification($order, $vendor);
```

### **Pattern 3: Bulk Notifications**
```php
// For campaigns and announcements
$unifiedService = app(\App\Services\UnifiedNotificationService::class);

$results = $unifiedService->sendToMultiple(
    module: 'campaign-announcement',
    title: 'New Feature Launch',
    summary: 'Check out our latest feature',
    recipientIds: $targetUserIds,
    data: ['feature_name' => 'Advanced Search']
);
```

### **Pattern 4: Observer Integration**
```php
// In model observers
class OrderObserver
{
    public function created(Order $order)
    {
        app(\App\Services\UnifiedNotificationService::class)->send(
            module: 'new-order',
            title: 'New Order',
            summary: "Order #{$order->id} received",
            recipientId: $order->vendor_id,
            data: ['order_id' => $order->id]
        );
    }
    
    public function updated(Order $order)
    {
        if ($order->wasChanged('status')) {
            app(\App\Services\UnifiedNotificationService::class)->send(
                module: 'update-order',
                title: 'Order Updated',
                summary: "Order #{$order->id} status: {$order->status}",
                recipientId: $order->user_id,
                data: [
                    'order_id' => $order->id,
                    'new_status' => $order->status
                ]
            );
        }
    }
}
```

---

## ⚙️ **Configuration Management**

### **Admin Interface Usage**

#### **Super Admin Tasks**
1. **Set Global Defaults**: Configure default channels for all modules
2. **Override Admin Settings**: Create specific settings for individual admins
3. **Monitor Configuration**: View all notification settings across the system

#### **Normal Admin Tasks**
1. **Personal Preferences**: Override global settings for their own notifications
2. **Module Selection**: Choose preferred channels for each notification type
3. **View Global Settings**: See system defaults (read-only)

### **API Management**

#### **Get Settings**
```http
GET /api/v1/notification-settings
Authorization: Bearer {admin_token}
```

#### **Update Setting**
```http
PUT /api/v1/notification-settings/{id}
Content-Type: application/json
Authorization: Bearer {admin_token}

{
    "channels": "whatsapp"
}
```

#### **Create Setting**
```http
POST /api/v1/notification-settings
Content-Type: application/json
Authorization: Bearer {admin_token}

{
    "notification_module": "custom-module",
    "channels": "firebase"
}
```

---

## 🔄 **Dynamic Module Support**

### **Auto-Creation Logic**
When a new module is used, the system automatically:

1. **Analyzes Module Name**: Uses pattern matching for smart defaults
2. **Creates Global Setting**: Adds entry to notification_settings table
3. **Returns Appropriate Channel**: Provides immediate functionality

### **Smart Default Patterns**
```php
// Pattern examples and their defaults
'sms-verification' → 'sms'
'email-newsletter' → 'mail'  
'whatsapp-campaign' → 'whatsapp'
'new-appointment' → 'firebase'
'community-reply' → 'in_app'
'urgent-alert' → 'firebase'
```

### **Adding New Modules**
No configuration required! Simply use any module name:

```php
// This will automatically create a setting with smart default
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'subscription-renewal',  // New module
    title: 'Subscription Renewal',
    summary: 'Your subscription expires soon',
    recipientId: $user->id
);
```

---

## 📱 **Platform-Specific Implementations**

### **Mobile Apps (Firebase)**
```json
{
    "notification": {
        "title": "New Order Received",
        "body": "New order #12345 from John Doe"
    },
    "data": {
        "module": "new-order",
        "order_id": "12345",
        "click_action": "OPEN_ORDER_DETAILS"
    }
}
```

### **WhatsApp Integration**
```json
{
    "to": "+1234567890",
    "type": "template",
    "template": {
        "name": "order_notification",
        "language": { "code": "en" },
        "components": [
            {
                "type": "body",
                "parameters": [
                    { "type": "text", "text": "12345" },
                    { "type": "text", "text": "John Doe" }
                ]
            }
        ]
    }
}
```

### **SMS Integration**
```json
{
    "to": "+1234567890",
    "message": "Your verification code is: 123456. Valid for 5 minutes.",
    "sender_id": "YourApp"
}
```

### **Email Integration**
```json
{
    "to": "user@example.com",
    "subject": "New Order Received",
    "template": "order_notification",
    "variables": {
        "order_id": "12345",
        "customer_name": "John Doe",
        "total_amount": "150.00"
    }
}
```

---

## 🛡️ **Security & Authentication**

### **API Security**
- **Bearer Token Authentication**: Secure API key for external service
- **Request Validation**: Payload validation and sanitization
- **Rate Limiting**: Prevent abuse and ensure service stability

### **Data Privacy**
- **Minimal Data Transfer**: Only essential data sent to external service
- **No Sensitive Data**: PII and sensitive information filtered out
- **Audit Trail**: All notification requests logged for compliance

---

## 🔍 **Testing & Monitoring**

### **Testing Strategies**

#### **Unit Testing**
```php
// Test channel resolution
public function test_channel_resolution()
{
    $resolver = app(NotificationChannelResolver::class);
    $channels = $resolver->resolve('sms-otp', null);
    $this->assertEquals(['sms'], $channels);
}

// Test unified service
public function test_notification_sending()
{
    Http::fake([
        'notification-service.com/*' => Http::response(['success' => true])
    ]);
    
    $result = app(UnifiedNotificationService::class)->send(
        'test-module', 'Test', 'Test message', 1
    );
    
    $this->assertTrue($result);
}
```

#### **Integration Testing**
```php
// Test complete flow
public function test_order_notification_flow()
{
    $order = Order::factory()->create();
    
    Http::fake();
    
    // This should trigger notification
    event(new OrderCreated($order));
    
    Http::assertSent(function ($request) {
        return $request->data()['module'] === 'new-order';
    });
}
```

### **Health Checks**
```php
// Check external service availability
Route::get('/health/notifications', function () {
    $response = Http::timeout(5)->get($endpoint . '/health');
    return response()->json([
        'external_service' => $response->successful() ? 'healthy' : 'down'
    ]);
});
```

---

## 📋 **Implementation Checklist**

### **Phase 1: Setup ✅**
- [x] Database migrations and models
- [x] Core services implementation
- [x] Admin interface (Filament)
- [x] Default settings seeding

### **Phase 2: Integration**
- [ ] Configure external service endpoint
- [ ] Update existing notification classes
- [ ] Implement in all relevant controllers
- [ ] Add observer integrations

### **Phase 3: Testing**
- [ ] Unit tests for all services
- [ ] Integration tests for notification flows
- [ ] Load testing for high-volume scenarios
- [ ] Error handling verification

### **Phase 4: Deployment**
- [ ] Production environment configuration
- [ ] External service setup and testing
- [ ] Performance monitoring setup
- [ ] Documentation for support team

---

## 🚀 **Quick Start Integration**

```php
// 1. Configure external service
// Set environment variables in .env

// 2. Start sending notifications immediately
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'your-module',
    title: 'Your Title',
    summary: 'Your message',
    recipientId: $userId,
    data: ['any' => 'additional data']
);

// 3. Let admins configure their preferences
// They can access /admin/notification-settings

// 4. Monitor and adjust as needed
// System auto-creates settings for new modules
```

The system is designed to work immediately with minimal configuration while providing maximum flexibility for customization and scaling. 
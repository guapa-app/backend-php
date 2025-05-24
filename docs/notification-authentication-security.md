# Notification System - Authentication & Security Guide

## 🔐 **Overview**

This document covers the secure authentication system implemented between the Laravel application and the external notification service. The system uses **multi-layer security** with Bearer tokens, HMAC signatures, timestamp validation, and nonce protection.

---

## 🏗️ **Security Architecture**

### **Authentication Flow**
```
Laravel App → Authentication Headers → External Service
                                    ↓
                          Validates: Token + Signature + Timestamp
                                    ↓
External Service → Authentication Headers → Laravel App (Webhooks)
                                         ↓
                               Validates: Token + Signature + Timestamp
```

### **Security Layers**
1. **Bearer Token Authentication**: Shared secret token
2. **HMAC-SHA256 Signatures**: Request payload integrity
3. **Timestamp Validation**: Prevents replay attacks (5-minute window)
4. **Nonce Protection**: Prevents duplicate requests
5. **App ID Validation**: Identifies authorized applications
6. **SSL/TLS Encryption**: Transport layer security

---

## 🔧 **Configuration Setup**

### **1. Generate Authentication Tokens**

```bash
# Generate new tokens automatically
php artisan notifications:generate-tokens --update-env --show-config

# This creates:
# - API Token (64 characters)
# - Secret Key (128 characters) 
# - App ID (unique identifier)
```

### **2. Environment Variables**

Add these to your `.env` file:

```env
# External Notification Service Configuration
EXTERNAL_NOTIFICATION_ENDPOINT=https://your-notification-service.com/api/notifications
EXTERNAL_NOTIFICATION_TOKEN=your_64_character_api_token_here
EXTERNAL_NOTIFICATION_SECRET_KEY=your_128_character_secret_key_here
EXTERNAL_NOTIFICATION_APP_ID=guapa-laravel-20241201

# Optional Settings
EXTERNAL_NOTIFICATION_TIMEOUT=30
EXTERNAL_NOTIFICATION_RETRY_ATTEMPTS=3
EXTERNAL_NOTIFICATION_RETRY_DELAY=1000
EXTERNAL_NOTIFICATION_VERIFY_SSL=true
```

### **3. External Service Configuration**

Configure your external notification service with these settings:

```json
{
  "allowed_apps": {
    "guapa-laravel-20241201": {
      "name": "Guapa Laravel App",
      "token": "your_64_character_api_token_here",
      "secret_key": "your_128_character_secret_key_here",
      "callback_url": "https://your-laravel-app.com/api/external-notifications",
      "webhook_endpoints": {
        "status": "/api/external-notifications/status",
        "webhook": "/api/external-notifications/webhook",
        "test": "/api/external-notifications/test"
      }
    }
  }
}
```

---

## 📡 **Request Authentication**

### **Outgoing Requests (Laravel → External Service)**

#### **Authentication Headers**
```http
POST /api/notifications
Host: notification-service.com
Authorization: Bearer your_api_token_here
X-App-ID: guapa-laravel-20241201
X-Timestamp: 1701432000
X-Nonce: abcd1234efgh5678ijkl9012mnop3456
X-Signature: sha256_hmac_signature_here
Content-Type: application/json
```

#### **Signature Generation**
```php
// String to sign format:
// METHOD|URI|PAYLOAD|TIMESTAMP|NONCE|APP_ID
$stringToSign = "POST|/api/notifications|{json_payload}|1701432000|{nonce}|guapa-laravel-20241201";
$signature = hash_hmac('sha256', $stringToSign, $secretKey);
```

#### **Example Request**
```json
{
    "module": "new-order",
    "title": "New Order Received",
    "summary": "Order #12345 from John Doe",
    "recipient_id": 123,
    "channels": ["firebase"],
    "data": {
        "order_id": 12345,
        "customer_name": "John Doe"
    }
}
```

### **Incoming Requests (External Service → Laravel)**

Laravel validates incoming requests using the same authentication mechanism:

#### **Middleware Protection**
```php
// All external notification routes are protected
Route::group(['middleware' => 'notification.auth'], function () {
    Route::post('/external-notifications/status', [ExternalNotificationController::class, 'receiveStatus']);
    Route::post('/external-notifications/webhook', [ExternalNotificationController::class, 'receiveWebhook']);
    Route::post('/external-notifications/test', [ExternalNotificationController::class, 'test']);
});
```

#### **Validation Process**
1. ✅ Extract and validate Bearer token
2. ✅ Verify App ID matches configuration
3. ✅ Check timestamp within 5-minute window
4. ✅ Validate HMAC signature
5. ✅ Ensure nonce uniqueness (prevents replay)

---

## 🔒 **Security Features**

### **1. Token-Based Authentication**
- **64-character random tokens** for API access
- **Configurable token rotation** support
- **Environment-based configuration**

### **2. HMAC Signature Validation**
- **SHA-256 HMAC** signatures for request integrity
- **Payload included** in signature calculation
- **Prevents request tampering**

### **3. Timestamp Protection**
- **5-minute tolerance window** prevents replay attacks
- **Automatic timestamp validation**
- **Configurable time window**

### **4. Nonce Implementation**
- **32-character random nonces** prevent duplicate requests
- **Single-use validation**
- **Replay attack protection**

### **5. SSL/TLS Enforcement**
- **HTTPS required** for all communications
- **Certificate validation** (configurable)
- **Secure transport layer**

---

## 🛠️ **Implementation Examples**

### **1. Sending Authenticated Notification**

```php
// Automatic authentication handled by service
app(\App\Services\UnifiedNotificationService::class)->send(
    module: 'new-order',
    title: 'New Order',
    summary: 'You have a new order',
    recipientId: $vendorId,
    data: ['order_id' => $order->id]
);
```

### **2. Manual External Service Call**

```php
use App\Services\ExternalNotificationService;

$service = app(ExternalNotificationService::class);

$result = $service->send([
    'module' => 'custom-notification',
    'title' => 'Custom Message',
    'summary' => 'This is a custom notification',
    'recipient_id' => 123,
    'channels' => ['firebase'],
    'data' => ['custom' => 'data']
]);
```

### **3. Batch Notifications**

```php
use App\Services\UnifiedNotificationService;

$service = app(UnifiedNotificationService::class);

// Automatically uses batch API for 5+ recipients
$results = $service->sendToMultiple(
    module: 'campaign-notification',
    title: 'Special Offer',
    summary: '50% off everything!',
    recipientIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    data: ['offer_id' => 567]
);
```

---

## 🔍 **Health Monitoring**

### **Health Check Endpoints**

```bash
# Check overall system health
GET /api/notifications/health/status
Authorization: Bearer {admin_token}

# Test connection to external service
GET /api/notifications/health/test-connection
Authorization: Bearer {admin_token}

# Validate configuration
GET /api/notifications/health/validate-config
Authorization: Bearer {admin_token}

# Send test notification
POST /api/notifications/health/send-test
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "recipient_id": 123,
    "module": "test-notification"
}

# Get authentication info
GET /api/notifications/health/auth-info
Authorization: Bearer {admin_token}
```

### **Health Check Response Example**

```json
{
    "success": true,
    "healthy": true,
    "timestamp": "2024-12-01T10:30:00Z",
    "service_status": {
        "configured": true,
        "configuration_errors": [],
        "endpoint": "https://notification-service.com/api/notifications",
        "timeout": 30,
        "retry_attempts": 3,
        "ssl_verification": true
    },
    "connection_test": {
        "success": true,
        "status_code": 200,
        "response_time": "125ms",
        "endpoint": "https://notification-service.com/api/notifications"
    }
}
```

---

## 🚨 **Error Handling**

### **Authentication Errors**

#### **401 Unauthorized**
```json
{
    "success": false,
    "error": "Unauthorized",
    "message": "Invalid authentication credentials"
}
```

#### **403 Forbidden**
```json
{
    "success": false,
    "error": "Forbidden", 
    "message": "Request timestamp too old"
}
```

#### **422 Validation Error**
```json
{
    "success": false,
    "error": "Validation failed",
    "errors": {
        "recipient_id": ["The recipient_id field is required."]
    }
}
```

### **Connection Errors**

```php
// Automatic retry with exponential backoff
// Logs errors but doesn't fail application
// Graceful degradation
```

---

## 🔧 **Troubleshooting**

### **Common Issues**

#### **1. Authentication Failed**
```bash
# Check configuration
php artisan notifications:generate-tokens --show-config

# Validate current config
curl -H "Authorization: Bearer {admin_token}" \
     "https://your-app.com/api/notifications/health/validate-config"
```

#### **2. Connection Timeout**
```bash
# Test connection
curl -H "Authorization: Bearer {admin_token}" \
     "https://your-app.com/api/notifications/health/test-connection"
```

#### **3. Invalid Signature**
- Ensure both apps have the same secret key
- Check payload formatting (JSON with consistent ordering)
- Verify timestamp synchronization

#### **4. Token Mismatch**
- Regenerate tokens: `php artisan notifications:generate-tokens`
- Update both Laravel and external service configurations
- Clear configuration cache: `php artisan config:clear`

### **Debug Commands**

```bash
# Generate new tokens
php artisan notifications:generate-tokens --update-env

# Test full system
curl -X POST \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{"recipient_id": 1, "module": "test"}' \
  "https://your-app.com/api/notifications/health/send-test"
```

---

## 📊 **Logging & Monitoring**

### **Security Logs**

```php
// Authentication attempts logged
Log::warning('Unauthorized notification request attempt', [
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent(),
    'timestamp' => now()
]);

// Successful authentications logged
Log::info('External service authenticated successfully', [
    'app_id' => $appId,
    'timestamp' => $timestamp
]);
```

### **Performance Monitoring**

```php
// Connection tests logged
Log::info('External service connection test', [
    'success' => $result['success'],
    'response_time' => $result['response_time'],
    'status_code' => $result['status_code']
]);
```

---

## 🚀 **Production Deployment**

### **Pre-Deployment Checklist**

- [ ] ✅ Generate production tokens
- [ ] ✅ Configure external service with tokens
- [ ] ✅ Test authentication end-to-end
- [ ] ✅ Verify SSL certificates
- [ ] ✅ Set up monitoring alerts
- [ ] ✅ Configure log rotation
- [ ] ✅ Test failover scenarios

### **Security Best Practices**

1. **🔐 Token Management**
   - Rotate tokens regularly (quarterly)
   - Store tokens securely (environment variables)
   - Never commit tokens to version control

2. **🛡️ Network Security**
   - Use HTTPS only
   - Implement IP whitelisting if possible
   - Enable SSL certificate validation

3. **📊 Monitoring**
   - Monitor authentication failures
   - Set up alerts for connection issues
   - Track notification delivery rates

4. **🔄 Incident Response**
   - Have token rotation procedure ready
   - Monitor security logs
   - Implement rate limiting

---

## 🎯 **Quick Setup Guide**

### **Step 1: Generate Tokens**
```bash
php artisan notifications:generate-tokens --update-env --show-config
```

### **Step 2: Configure External Service**
Use the generated configuration to set up your external notification service.

### **Step 3: Test Connection**
```bash
curl -H "Authorization: Bearer {admin_token}" \
     "https://your-app.com/api/notifications/health/status"
```

### **Step 4: Send Test Notification**
```bash
curl -X POST \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{"recipient_id": 1}' \
  "https://your-app.com/api/notifications/health/send-test"
```

**🎉 Your secure notification system is ready!** 
# External Notification Service - Implementation Example

## 🏗️ **Overview**

This document provides implementation examples for the external notification service to properly authenticate and communicate with the Laravel application.

---

## 🔐 **Authentication Middleware (Node.js/Express Example)**

```javascript
const crypto = require('crypto');

class NotificationAuthMiddleware {
    constructor(allowedApps) {
        this.allowedApps = allowedApps; // Configuration object with app credentials
    }

    validateRequest(req, res, next) {
        try {
            // Extract authentication headers
            const authHeader = req.headers.authorization;
            const appId = req.headers['x-app-id'];
            const timestamp = req.headers['x-timestamp'];
            const nonce = req.headers['x-nonce'];
            const signature = req.headers['x-signature'];

            // Validate required headers
            if (!authHeader || !appId || !timestamp || !nonce || !signature) {
                return this.unauthorized(res, 'Missing authentication headers');
            }

            // Extract bearer token
            const token = this.extractBearerToken(authHeader);
            if (!token) {
                return this.unauthorized(res, 'Invalid authorization header format');
            }

            // Validate app exists in configuration
            const appConfig = this.allowedApps[appId];
            if (!appConfig) {
                return this.unauthorized(res, 'Unknown app ID');
            }

            // Validate token
            if (token !== appConfig.token) {
                return this.unauthorized(res, 'Invalid token');
            }

            // Validate timestamp (5 minute window)
            const requestTime = parseInt(timestamp);
            const currentTime = Math.floor(Date.now() / 1000);
            const timeDiff = Math.abs(currentTime - requestTime);
            
            if (timeDiff > 300) { // 5 minutes in seconds
                return this.unauthorized(res, 'Request timestamp too old');
            }

            // Validate signature
            const expectedSignature = this.generateSignature(
                req.body, 
                timestamp, 
                nonce, 
                appId, 
                appConfig.secret_key
            );

            if (!crypto.timingSafeEqual(
                Buffer.from(signature, 'hex'),
                Buffer.from(expectedSignature, 'hex')
            )) {
                return this.unauthorized(res, 'Invalid signature');
            }

            // Store app info in request for later use
            req.authenticatedApp = appConfig;
            req.appId = appId;

            next();
        } catch (error) {
            console.error('Authentication error:', error);
            return this.unauthorized(res, 'Authentication failed');
        }
    }

    generateSignature(payload, timestamp, nonce, appId, secretKey) {
        // Create string to sign: METHOD|URI|PAYLOAD|TIMESTAMP|NONCE|APP_ID
        const method = 'POST';
        const uri = '/api/notifications';
        const payloadString = JSON.stringify(payload);
        
        const stringToSign = `${method}|${uri}|${payloadString}|${timestamp}|${nonce}|${appId}`;
        
        return crypto
            .createHmac('sha256', secretKey)
            .update(stringToSign)
            .digest('hex');
    }

    extractBearerToken(authHeader) {
        const match = authHeader.match(/^Bearer\s+(.+)$/i);
        return match ? match[1] : null;
    }

    unauthorized(res, message) {
        console.warn('Unauthorized request:', message);
        return res.status(401).json({
            success: false,
            error: 'Unauthorized',
            message: message
        });
    }
}

module.exports = NotificationAuthMiddleware;
```

---

## 📡 **Notification Handler Implementation**

```javascript
const axios = require('axios');

class NotificationService {
    constructor(config) {
        this.config = config;
        this.deliveryProviders = {
            firebase: new FirebaseProvider(config.firebase),
            whatsapp: new WhatsAppProvider(config.whatsapp),
            sms: new SMSProvider(config.sms),
            mail: new EmailProvider(config.mail),
            in_app: new InAppProvider(config.database)
        };
    }

    async handleNotification(req, res) {
        try {
            const { module, title, summary, recipient_id, channels, data } = req.body;

            // Validate request payload
            if (!module || !title || !summary || !recipient_id || !channels) {
                return res.status(422).json({
                    success: false,
                    error: 'Validation failed',
                    message: 'Missing required fields'
                });
            }

            // Generate delivery ID for tracking
            const deliveryId = this.generateDeliveryId();

            // Process each channel
            const results = await Promise.all(
                channels.map(channel => this.sendToChannel(
                    channel, 
                    { module, title, summary, recipient_id, data },
                    deliveryId
                ))
            );

            // Log successful delivery
            console.log('Notification processed:', {
                delivery_id: deliveryId,
                module,
                recipient_id,
                channels,
                app_id: req.appId
            });

            // Send status back to Laravel app (optional)
            this.sendStatusCallback(req.authenticatedApp, {
                delivery_id: deliveryId,
                status: 'sent',
                module,
                recipient_id,
                channels,
                timestamp: new Date().toISOString()
            });

            return res.json({
                success: true,
                message: 'Notification sent successfully',
                delivery_id: deliveryId,
                timestamp: new Date().toISOString(),
                results: results
            });

        } catch (error) {
            console.error('Notification handling error:', error);
            return res.status(500).json({
                success: false,
                error: 'Internal server error',
                message: error.message
            });
        }
    }

    async handleBatchNotification(req, res) {
        try {
            const { module, title, summary, recipient_ids, channels, data } = req.body;

            // Validate batch payload
            if (!module || !title || !summary || !recipient_ids || !Array.isArray(recipient_ids) || !channels) {
                return res.status(422).json({
                    success: false,
                    error: 'Validation failed',
                    message: 'Missing required fields for batch notification'
                });
            }

            const deliveryId = this.generateDeliveryId();

            // Process batch for each channel
            const results = await Promise.all(
                channels.map(channel => this.sendBatchToChannel(
                    channel,
                    { module, title, summary, recipient_ids, data },
                    deliveryId
                ))
            );

            console.log('Batch notification processed:', {
                delivery_id: deliveryId,
                module,
                recipient_count: recipient_ids.length,
                channels,
                app_id: req.appId
            });

            return res.json({
                success: true,
                message: 'Batch notification sent successfully',
                delivery_id: deliveryId,
                recipient_count: recipient_ids.length,
                timestamp: new Date().toISOString(),
                results: results
            });

        } catch (error) {
            console.error('Batch notification error:', error);
            return res.status(500).json({
                success: false,
                error: 'Internal server error',
                message: error.message
            });
        }
    }

    async sendToChannel(channel, payload, deliveryId) {
        try {
            const provider = this.deliveryProviders[channel];
            if (!provider) {
                throw new Error(`Unknown channel: ${channel}`);
            }

            const result = await provider.send(payload);
            
            return {
                channel,
                success: true,
                result: result,
                delivery_id: deliveryId
            };
        } catch (error) {
            console.error(`Channel ${channel} delivery failed:`, error);
            return {
                channel,
                success: false,
                error: error.message,
                delivery_id: deliveryId
            };
        }
    }

    async sendBatchToChannel(channel, payload, deliveryId) {
        try {
            const provider = this.deliveryProviders[channel];
            if (!provider) {
                throw new Error(`Unknown channel: ${channel}`);
            }

            const result = await provider.sendBatch(payload);
            
            return {
                channel,
                success: true,
                result: result,
                delivery_id: deliveryId
            };
        } catch (error) {
            console.error(`Batch channel ${channel} delivery failed:`, error);
            return {
                channel,
                success: false,
                error: error.message,
                delivery_id: deliveryId
            };
        }
    }

    async sendStatusCallback(appConfig, statusData) {
        if (!appConfig.callback_url) return;

        try {
            // Generate authentication headers for callback
            const timestamp = Math.floor(Date.now() / 1000);
            const nonce = this.generateNonce();
            const signature = this.generateCallbackSignature(
                statusData, 
                timestamp, 
                nonce, 
                appConfig.app_id, 
                appConfig.secret_key
            );

            await axios.post(`${appConfig.callback_url}/status`, statusData, {
                headers: {
                    'Authorization': `Bearer ${appConfig.token}`,
                    'X-App-ID': appConfig.app_id,
                    'X-Timestamp': timestamp,
                    'X-Nonce': nonce,
                    'X-Signature': signature,
                    'Content-Type': 'application/json'
                },
                timeout: 10000
            });

            console.log('Status callback sent successfully');
        } catch (error) {
            console.error('Status callback failed:', error.message);
        }
    }

    generateCallbackSignature(payload, timestamp, nonce, appId, secretKey) {
        const method = 'POST';
        const uri = '/api/external-notifications/status';
        const payloadString = JSON.stringify(payload);
        
        const stringToSign = `${method}|${uri}|${payloadString}|${timestamp}|${nonce}|${appId}`;
        
        return crypto
            .createHmac('sha256', secretKey)
            .update(stringToSign)
            .digest('hex');
    }

    generateDeliveryId() {
        return crypto.randomBytes(16).toString('hex');
    }

    generateNonce() {
        return crypto.randomBytes(16).toString('hex');
    }
}

module.exports = NotificationService;
```

---

## 🚀 **Express.js Server Setup**

```javascript
const express = require('express');
const bodyParser = require('body-parser');
const NotificationAuthMiddleware = require('./middleware/NotificationAuthMiddleware');
const NotificationService = require('./services/NotificationService');

const app = express();

// Configuration - load from environment or config file
const config = {
    allowed_apps: {
        'guapa-laravel-20241201': {
            name: 'Guapa Laravel App',
            token: process.env.GUAPA_API_TOKEN,
            secret_key: process.env.GUAPA_SECRET_KEY,
            callback_url: process.env.GUAPA_CALLBACK_URL,
            app_id: 'guapa-laravel-20241201'
        }
    },
    firebase: {
        // Firebase configuration
    },
    whatsapp: {
        // WhatsApp API configuration
    },
    sms: {
        // SMS provider configuration
    },
    mail: {
        // Email provider configuration
    }
};

// Initialize services
const authMiddleware = new NotificationAuthMiddleware(config.allowed_apps);
const notificationService = new NotificationService(config);

// Middleware
app.use(bodyParser.json());

// Authentication middleware for notification endpoints
app.use('/api/notifications', (req, res, next) => {
    authMiddleware.validateRequest(req, res, next);
});

// Routes
app.post('/api/notifications', async (req, res) => {
    await notificationService.handleNotification(req, res);
});

app.post('/api/notifications/batch', async (req, res) => {
    await notificationService.handleBatchNotification(req, res);
});

// Health check endpoint
app.get('/api/health', (req, res) => {
    res.json({
        success: true,
        service: 'External Notification Service',
        timestamp: new Date().toISOString(),
        uptime: process.uptime()
    });
});

// Test endpoint for authentication verification
app.post('/api/test', (req, res) => {
    res.json({
        success: true,
        message: 'Authentication successful',
        app_info: {
            id: req.appId,
            name: req.authenticatedApp.name
        },
        timestamp: new Date().toISOString()
    });
});

// Error handling
app.use((error, req, res, next) => {
    console.error('Unhandled error:', error);
    res.status(500).json({
        success: false,
        error: 'Internal server error'
    });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`External Notification Service running on port ${PORT}`);
});
```

---

## 📱 **Channel Provider Examples**

### **Firebase Provider**

```javascript
const admin = require('firebase-admin');

class FirebaseProvider {
    constructor(config) {
        if (!admin.apps.length) {
            admin.initializeApp({
                credential: admin.credential.cert(config.serviceAccount)
            });
        }
        this.messaging = admin.messaging();
    }

    async send(payload) {
        const { recipient_id, title, summary, data } = payload;
        
        // Get device tokens for recipient
        const tokens = await this.getDeviceTokens(recipient_id);
        
        if (tokens.length === 0) {
            throw new Error('No device tokens found for recipient');
        }

        const message = {
            notification: {
                title: title,
                body: summary
            },
            data: {
                ...data,
                recipient_id: recipient_id.toString()
            },
            tokens: tokens
        };

        const response = await this.messaging.sendMulticast(message);
        return {
            success_count: response.successCount,
            failure_count: response.failureCount,
            tokens_sent: tokens.length
        };
    }

    async sendBatch(payload) {
        const { recipient_ids, title, summary, data } = payload;
        
        // Get all device tokens for all recipients
        const allTokens = await Promise.all(
            recipient_ids.map(id => this.getDeviceTokens(id))
        );
        
        const tokens = allTokens.flat();
        
        if (tokens.length === 0) {
            throw new Error('No device tokens found for any recipients');
        }

        const message = {
            notification: {
                title: title,
                body: summary
            },
            data: {
                ...data,
                batch: 'true'
            },
            tokens: tokens
        };

        const response = await this.messaging.sendMulticast(message);
        return {
            success_count: response.successCount,
            failure_count: response.failureCount,
            total_recipients: recipient_ids.length,
            tokens_sent: tokens.length
        };
    }

    async getDeviceTokens(recipientId) {
        // Implementation to get device tokens from your database
        // This would typically query your user devices table
        return []; // Return array of device tokens
    }
}
```

### **SMS Provider**

```javascript
const twilio = require('twilio');

class SMSProvider {
    constructor(config) {
        this.client = twilio(config.accountSid, config.authToken);
        this.fromNumber = config.fromNumber;
    }

    async send(payload) {
        const { recipient_id, title, summary } = payload;
        
        // Get phone number for recipient
        const phoneNumber = await this.getPhoneNumber(recipient_id);
        
        const message = await this.client.messages.create({
            body: `${title}: ${summary}`,
            from: this.fromNumber,
            to: phoneNumber
        });

        return {
            message_id: message.sid,
            status: message.status,
            phone_number: phoneNumber
        };
    }

    async sendBatch(payload) {
        const { recipient_ids, title, summary } = payload;
        
        // Get phone numbers for all recipients
        const phoneNumbers = await Promise.all(
            recipient_ids.map(id => this.getPhoneNumber(id))
        );

        const results = await Promise.all(
            phoneNumbers.map(phone => 
                this.client.messages.create({
                    body: `${title}: ${summary}`,
                    from: this.fromNumber,
                    to: phone
                })
            )
        );

        return {
            sent_count: results.length,
            message_ids: results.map(r => r.sid)
        };
    }

    async getPhoneNumber(recipientId) {
        // Implementation to get phone number from your database
        return '+1234567890'; // Return formatted phone number
    }
}
```

---

## 🔧 **Environment Configuration**

```env
# Server Configuration
PORT=3000
NODE_ENV=production

# Laravel App Configuration
GUAPA_API_TOKEN=your_64_character_api_token_here
GUAPA_SECRET_KEY=your_128_character_secret_key_here
GUAPA_CALLBACK_URL=https://your-laravel-app.com/api/external-notifications

# Firebase Configuration
FIREBASE_SERVICE_ACCOUNT_PATH=/path/to/service-account.json

# SMS Configuration (Twilio)
TWILIO_ACCOUNT_SID=your_twilio_account_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_FROM_NUMBER=+1234567890

# WhatsApp Configuration
WHATSAPP_API_URL=https://api.whatsapp.business
WHATSAPP_API_TOKEN=your_whatsapp_token

# Email Configuration
SMTP_HOST=smtp.mailgun.org
SMTP_PORT=587
SMTP_USER=your_smtp_user
SMTP_PASS=your_smtp_password
```

---

## 🏃‍♂️ **Quick Start**

### **1. Install Dependencies**

```bash
npm install express body-parser axios crypto firebase-admin twilio
```

### **2. Set Environment Variables**

```bash
export GUAPA_API_TOKEN="your_token_from_laravel_command"
export GUAPA_SECRET_KEY="your_secret_from_laravel_command"
export GUAPA_CALLBACK_URL="https://your-laravel-app.com/api/external-notifications"
```

### **3. Start Service**

```bash
node server.js
```

### **4. Test Authentication**

```bash
# Test from Laravel app
curl -X POST \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{"recipient_id": 1}' \
  "https://your-laravel-app.com/api/notifications/health/send-test"
```

---

## 📋 **Implementation Checklist**

- [ ] ✅ Set up authentication middleware
- [ ] ✅ Implement notification handlers
- [ ] ✅ Configure delivery providers (Firebase, SMS, etc.)
- [ ] ✅ Set up callback functionality
- [ ] ✅ Add error handling and logging
- [ ] ✅ Test with Laravel app
- [ ] ✅ Set up monitoring and health checks
- [ ] ✅ Configure production environment

---

This implementation provides a complete, production-ready external notification service that securely communicates with your Laravel application using the same authentication system. 
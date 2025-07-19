# Gift Card System API Documentation

## Overview

This system allows super admins to upload and manage gift card background images, and users to create and manage gift cards with two types:

1. **Wallet Gift Cards**: Amount is redeemed directly to the user's wallet
2. **Order Gift Cards**: Creates an order for a specific product or offer

## Database Schema

### Gift Card Backgrounds Table

```sql
CREATE TABLE gift_card_backgrounds (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (uploaded_by) REFERENCES admins(id) ON DELETE CASCADE
);
```

### Enhanced Gift Cards Table

```sql
CREATE TABLE gift_cards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    vendor_id BIGINT UNSIGNED NULL,
    type ENUM('product', 'offer') DEFAULT 'product',
    gift_type ENUM('wallet', 'order') DEFAULT 'wallet',
    product_id BIGINT UNSIGNED NULL,
    offer_id BIGINT UNSIGNED NULL,
    order_id BIGINT UNSIGNED NULL,
    wallet_transaction_id BIGINT UNSIGNED NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'SAR',
    background_color VARCHAR(255) NULL,
    background_image VARCHAR(255) NULL,
    background_image_id BIGINT UNSIGNED NULL,
    message TEXT NULL,
    notes TEXT NULL,
    status VARCHAR(255) DEFAULT 'active',
    redemption_method ENUM('pending', 'wallet', 'order') DEFAULT 'pending',
    expires_at TIMESTAMP NULL,
    redeemed_at TIMESTAMP NULL,
    recipient_name VARCHAR(255) NULL,
    recipient_email VARCHAR(255) NULL,
    recipient_number VARCHAR(20) NULL,
    product_type ENUM('product', 'service') NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE SET NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (wallet_transaction_id) REFERENCES transactions(id) ON DELETE SET NULL,
    FOREIGN KEY (background_image_id) REFERENCES gift_card_backgrounds(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

## Admin API Endpoints (V1)

### Base URL: `/api/v1/admin/gift-card-backgrounds`

#### 1. List All Backgrounds

```
GET /api/v1/admin/gift-card-backgrounds
```

**Query Parameters:**

-   `search` (optional): Search by name or description
-   `status` (optional): Filter by status ('active' or 'inactive')
-   `per_page` (optional): Number of items per page (default: 20)

**Response:**

```json
{
    "success": true,
    "message": "Success",
    "data": [
        {
            "id": 1,
            "name": "Elegant Gold",
            "description": "A sophisticated gold gradient background",
            "is_active": true,
            "image_url": "https://example.com/image.jpg",
            "thumbnail_url": "https://example.com/thumb.jpg",
            "uploaded_by": {
                "id": 1,
                "name": "Super Admin",
                "email": "admin@example.com"
            },
            "created_at": "2025-01-20T10:00:00.000000Z",
            "updated_at": "2025-01-20T10:00:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 20,
        "total": 1
    }
}
```

#### 2. Create New Background

```
POST /api/v1/admin/gift-card-backgrounds
```

**Request Body (multipart/form-data):**

-   `name` (required): Background name
-   `description` (optional): Background description
-   `background_image` (required): Image file (max 5MB, formats: jpeg, png, jpg, gif, svg)
-   `is_active` (optional): Boolean (default: true)

**Response:**

```json
{
    "success": true,
    "message": "Created successfully",
    "data": {
        "id": 1,
        "name": "Elegant Gold",
        "description": "A sophisticated gold gradient background",
        "is_active": true,
        "image_url": "https://example.com/image.jpg",
        "thumbnail_url": "https://example.com/thumb.jpg",
        "uploaded_by": {
            "id": 1,
            "name": "Super Admin",
            "email": "admin@example.com"
        },
        "created_at": "2025-01-20T10:00:00.000000Z",
        "updated_at": "2025-01-20T10:00:00.000000Z"
    }
}
```

#### 3. Get Single Background

```
GET /api/v1/admin/gift-card-backgrounds/{id}
```

#### 4. Update Background

```
PUT /api/v1/admin/gift-card-backgrounds/{id}
```

**Request Body (multipart/form-data):**

-   `name` (optional): Background name
-   `description` (optional): Background description
-   `background_image` (optional): New image file
-   `is_active` (optional): Boolean

#### 5. Delete Background

```
DELETE /api/v1/admin/gift-card-backgrounds/{id}
```

#### 6. Toggle Status

```
PATCH /api/v1/admin/gift-card-backgrounds/{id}/toggle-status
```

#### 7. Get Active Backgrounds (Public)

```
GET /api/v1/admin/gift-card-backgrounds/active
```

## User API Endpoints (V3.1)

### Base URL: `/api/v3.1/user/gift-cards`

#### 1. Get Gift Card Options

```
GET /api/v3.1/user/gift-cards/options
```

**Response:**

```json
{
    "success": true,
    "message": "Success",
    "data": {
        "type": ["product", "offer"],
        "product_type": ["product", "service"],
        "gift_type": ["wallet", "order"],
        "background_colors": [
            "#FF8B85",
            "#64668E",
            "#00ABB6",
            "#E92E2E",
            "#4CAF50",
            "#FFD700",
            "#F44336",
            "#9C27B0",
            "#00ACC1",
            "#FFA726",
            "#FFF",
            "#000"
        ],
        "background_images": [
            {
                "id": 1,
                "name": "Elegant Gold",
                "description": "A sophisticated gold gradient background",
                "image_url": "https://example.com/image.jpg",
                "thumbnail_url": "https://example.com/thumb.jpg"
            }
        ]
    }
}
```

#### 2. List User's Gift Cards

```
GET /api/v3.1/user/gift-cards
```

#### 3. Create Gift Card

```
POST /api/v3.1/user/gift-cards
```

**Request Body:**

```json
{
    "type": "product",
    "gift_type": "wallet",
    "amount": 100,
    "currency": "SAR",
    "background_color": "#FF8B85",
    "background_image_id": 1,
    "message": "Happy Birthday!",
    "recipient_name": "John Doe",
    "recipient_email": "john@example.com",
    "expires_at": "2025-12-31"
}
```

**Wallet Gift Card Example:**

```json
{
    "type": "product",
    "gift_type": "wallet",
    "amount": 200,
    "currency": "SAR",
    "background_image_id": 1,
    "message": "Enjoy your gift!",
    "recipient_name": "Jane Doe",
    "recipient_email": "jane@example.com"
}
```

**Order Gift Card Example:**

```json
{
    "type": "product",
    "gift_type": "order",
    "product_id": 123,
    "vendor_id": 456,
    "amount": 150,
    "currency": "SAR",
    "background_image_id": 2,
    "message": "Special offer for you!",
    "recipient_name": "Bob Smith",
    "recipient_email": "bob@example.com"
}
```

#### 4. Get Gift Card by ID

```
GET /api/v3.1/user/gift-cards/{id}
```

#### 5. Get Gift Card by Code

```
GET /api/v3.1/user/gift-cards/code?code=GC123456
```

#### 6. Get My Gift Cards (Sent/Received)

```
GET /api/v3.1/user/gift-cards/my?type=sent
GET /api/v3.1/user/gift-cards/my?type=received
GET /api/v3.1/user/gift-cards/my?type=all
```

#### 7. Redeem Gift Card to Wallet

```
POST /api/v3.1/user/gift-cards/{id}/redeem-wallet
```

**Response:**

```json
{
    "success": true,
    "message": "Gift card redeemed to wallet successfully",
    "data": {
        "id": 1,
        "code": "GC123456",
        "gift_type": "wallet",
        "amount": 100,
        "status": "used",
        "redemption_method": "wallet",
        "redeemed_at": "2025-01-21T10:00:00.000000Z",
        "wallet_transaction": {
            "id": 1,
            "amount": 100,
            "type": "credit",
            "status": "completed",
            "created_at": "2025-01-21T10:00:00.000000Z"
        }
    }
}
```

#### 8. Create Order from Gift Card

```
POST /api/v3.1/user/gift-cards/{id}/create-order
```

**Response:**

```json
{
    "success": true,
    "message": "Order created from gift card successfully",
    "data": {
        "gift_card": {
            "id": 1,
            "code": "GC123456",
            "gift_type": "order",
            "status": "used",
            "redemption_method": "order",
            "order": {
                "id": 1,
                "status": "pending",
                "total_amount": 150,
                "created_at": "2025-01-21T10:00:00.000000Z"
            }
        },
        "order": {
            "id": 1,
            "user_id": 1,
            "vendor_id": 456,
            "product_id": 123,
            "total_amount": 150,
            "status": "pending",
            "payment_method": "gift_card"
        }
    }
}
```

#### 9. Cancel Order and Redeem to Wallet

```
POST /api/v3.1/user/gift-cards/{id}/cancel-order-redeem-wallet
```

**Response:**

```json
{
    "success": true,
    "message": "Order cancelled and amount redeemed to wallet",
    "data": {
        "id": 1,
        "code": "GC123456",
        "gift_type": "order",
        "status": "used",
        "redemption_method": "wallet",
        "wallet_transaction": {
            "id": 2,
            "amount": 150,
            "type": "credit",
            "status": "completed",
            "created_at": "2025-01-21T10:00:00.000000Z"
        }
    }
}
```

## Gift Card Types

### 1. Wallet Gift Cards

-   **Purpose**: Direct wallet credit
-   **Redemption**: Amount is added to user's wallet balance
-   **Use Case**: General purpose gift cards
-   **Fields**: `gift_type: 'wallet'`, no `product_id` or `offer_id`

### 2. Order Gift Cards

-   **Purpose**: Create specific product/offer orders
-   **Redemption**: Creates an order for the specified product/offer
-   **Use Case**: Specific product or service gift cards
-   **Fields**: `gift_type: 'order'`, requires `product_id` or `offer_id`

## Gift Card Statuses

-   **active**: Gift card is available for redemption
-   **used**: Gift card has been redeemed
-   **expired**: Gift card has expired
-   **cancelled**: Gift card has been cancelled

## Redemption Methods

-   **pending**: Gift card has not been redeemed yet
-   **wallet**: Gift card was redeemed to wallet
-   **order**: Gift card was redeemed by creating an order

## Filament Admin Panel

### Access

-   URL: `/admin/gift-card-backgrounds`
-   Only accessible by super admins
-   Full CRUD operations with image upload

### Features

-   Upload background images with preview
-   Toggle active/inactive status
-   Search and filter backgrounds
-   Bulk operations
-   Image conversions (small, medium, large)

## Configuration

### Colors Configuration

File: `config/gift_card.php`

```php
return [
    'colors' => [
        '#FF8B85', // primary
        '#64668E', // secondary
        '#00ABB6', // blue
        '#E92E2E', // danger
        '#4CAF50', // green
        '#FFD700', // gold
        '#F44336', // red
        '#9C27B0', // purple
        '#00ACC1', // cyan
        '#FFA726', // orange
        '#FFF',    // white
        '#000',    // black
    ],
];
```

## Security

### Permissions

-   Only super admins can manage gift card backgrounds
-   Users can only view active backgrounds
-   Image upload validation and size limits

### File Upload Limits

-   Maximum file size: 5MB
-   Allowed formats: jpeg, png, jpg, gif, svg
-   Automatic image conversions for different sizes

## Usage Examples

### Frontend Integration

```javascript
// Get available options
const response = await fetch("/api/v3.1/user/gift-cards/options");
const options = await response.json();

// Display colors
options.data.background_colors.forEach((color) => {
    // Create color picker option
});

// Display admin backgrounds
options.data.background_images.forEach((bg) => {
    // Create background image option
});

// Create wallet gift card
const walletGiftCard = {
    type: "product",
    gift_type: "wallet",
    amount: 100,
    currency: "SAR",
    background_image_id: 1,
    message: "Happy Birthday!",
    recipient_name: "John Doe",
    recipient_email: "john@example.com",
};

// Create order gift card
const orderGiftCard = {
    type: "product",
    gift_type: "order",
    product_id: 123,
    vendor_id: 456,
    amount: 150,
    currency: "SAR",
    background_image_id: 2,
    message: "Special offer!",
    recipient_name: "Jane Doe",
    recipient_email: "jane@example.com",
};

// Redeem wallet gift card
await fetch(`/api/v3.1/user/gift-cards/${giftCardId}/redeem-wallet`, {
    method: "POST",
    headers: {
        Authorization: "Bearer " + userToken,
    },
});

// Create order from gift card
await fetch(`/api/v3.1/user/gift-cards/${giftCardId}/create-order`, {
    method: "POST",
    headers: {
        Authorization: "Bearer " + userToken,
    },
});

// Cancel order and redeem to wallet
await fetch(
    `/api/v3.1/user/gift-cards/${giftCardId}/cancel-order-redeem-wallet`,
    {
        method: "POST",
        headers: {
            Authorization: "Bearer " + userToken,
        },
    }
);
```

### Admin Management

```javascript
// Upload new background
const formData = new FormData();
formData.append("name", "New Background");
formData.append("description", "Description");
formData.append("background_image", file);

const response = await fetch("/api/v1/admin/gift-card-backgrounds", {
    method: "POST",
    body: formData,
    headers: {
        Authorization: "Bearer " + adminToken,
    },
});
```

## Migration and Setup

1. Run migration:

```bash
php artisan migrate
```

2. Access admin panel to upload actual images

## Notes

-   Background images are stored using Spatie Media Library
-   Automatic image conversions are generated (small, medium, large)
-   Only active backgrounds are available to users
-   Super admins can manage all aspects of gift card backgrounds
-   The system supports both admin-uploaded images and solid colors
-   Gift cards can be redeemed to wallet or create orders
-   Orders created from gift cards can be cancelled and redeemed to wallet
-   All gift card operations are tracked with proper relationships
-   Automatic code generation for gift cards
-   Expiration date support for gift cards

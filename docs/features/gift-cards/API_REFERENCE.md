# Gift Card System API Reference

## Overview

This document provides a comprehensive reference for all gift card related API endpoints, including both admin and user APIs.

## Admin API Endpoints (V1)

### Base URL: `/api/v1/admin/gift-card-backgrounds`

#### 1. List All Backgrounds

```http
GET /api/v1/admin/gift-card-backgrounds
```

**Description:** Retrieve all gift card background images with pagination and filtering options.

**Query Parameters:**

-   `search` (optional): Search by name or description
-   `status` (optional): Filter by status ('active' or 'inactive')
-   `per_page` (optional): Number of items per page (default: 20)

**Response:** List of background images with pagination metadata

---

#### 2. Create New Background

```http
POST /api/v1/admin/gift-card-backgrounds
```

**Description:** Upload a new gift card background image (super admin only).

**Request Body (multipart/form-data):**

-   `name` (required): Background name
-   `description` (optional): Background description
-   `background_image` (required): Image file (max 5MB, formats: jpeg, png, jpg, gif, svg)
-   `is_active` (optional): Boolean (default: true)

**Response:** Created background image details

---

#### 3. Get Single Background

```http
GET /api/v1/admin/gift-card-backgrounds/{id}
```

**Description:** Retrieve details of a specific background image.

**Response:** Background image details

---

#### 4. Update Background

```http
PUT /api/v1/admin/gift-card-backgrounds/{id}
```

**Description:** Update an existing background image.

**Request Body (multipart/form-data):**

-   `name` (optional): Background name
-   `description` (optional): Background description
-   `background_image` (optional): New image file
-   `is_active` (optional): Boolean

**Response:** Updated background image details

---

#### 5. Delete Background

```http
DELETE /api/v1/admin/gift-card-backgrounds/{id}
```

**Description:** Delete a background image and its associated media files.

**Response:** Success message

---

#### 6. Toggle Status

```http
PATCH /api/v1/admin/gift-card-backgrounds/{id}/toggle-status
```

**Description:** Toggle the active/inactive status of a background image.

**Response:** Updated status information

---

#### 7. Get Active Backgrounds (Public)

```http
GET /api/v1/admin/gift-card-backgrounds/active
```

**Description:** Retrieve only active background images for public use.

**Response:** List of active background images

---

## User API Endpoints (V3.1)

### Base URL: `/api/v3.1/user/gift-cards`

#### 1. Get Gift Card Options

```http
GET /api/v3.1/user/gift-cards/options
```

**Description:** Retrieve available options for creating gift cards including colors, background images, and types.

**Response:** Available options for gift card creation

---

#### 2. List User's Gift Cards

```http
GET /api/v3.1/user/gift-cards
```

**Description:** Retrieve all gift cards belonging to the authenticated user.

**Query Parameters:**

-   `per_page` (optional): Number of items per page (default: 20)

**Response:** Paginated list of user's gift cards

---

#### 3. Create Gift Card

```http
POST /api/v3.1/user/gift-cards
```

**Description:** Create a new gift card (wallet or order type).

**Request Body:**

```json
{
    "type": "product|offer",
    "gift_type": "wallet|order",
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

**Response:** Created gift card details

---

#### 4. Get Gift Card by ID

```http
GET /api/v3.1/user/gift-cards/{id}
```

**Description:** Retrieve details of a specific gift card by ID.

**Response:** Gift card details with relationships

---

#### 5. Get Gift Card by Code

```http
GET /api/v3.1/user/gift-cards/code?code=GC123456
```

**Description:** Retrieve gift card details by its unique code.

**Query Parameters:**

-   `code` (required): Gift card code

**Response:** Gift card details

---

#### 6. Get My Gift Cards (Sent/Received)

```http
GET /api/v3.1/user/gift-cards/my?type=sent|received|all
```

**Description:** Retrieve gift cards sent by or received by the user.

**Query Parameters:**

-   `type` (optional): Filter by type ('sent', 'received', 'all' - default: 'all')

**Response:** Paginated list of gift cards

---

#### 7. Redeem Gift Card to Wallet

```http
POST /api/v3.1/user/gift-cards/{id}/redeem-wallet
```

**Description:** Redeem a wallet-type gift card by adding the amount to user's wallet.

**Response:** Updated gift card with wallet transaction details

---

#### 8. Create Order from Gift Card

```http
POST /api/v3.1/user/gift-cards/{id}/create-order
```

**Description:** Redeem an order-type gift card by creating an order for the specified product/offer.

**Response:** Created order and updated gift card details

---

#### 9. Cancel Order and Redeem to Wallet

```http
POST /api/v3.1/user/gift-cards/{id}/cancel-order-redeem-wallet
```

**Description:** Cancel an order created from a gift card and redeem the amount to wallet instead.

**Response:** Updated gift card with wallet transaction details

---

## Request/Response Examples

### Create Wallet Gift Card

```http
POST /api/v3.1/user/gift-cards
Content-Type: application/json
Authorization: Bearer {token}

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

**Response:**

```json
{
    "success": true,
    "message": "Created successfully",
    "data": {
        "id": 1,
        "code": "GC123456",
        "type": "product",
        "gift_type": "wallet",
        "amount": "200.00",
        "currency": "SAR",
        "status": "active",
        "redemption_method": "pending",
        "message": "Enjoy your gift!",
        "recipient_name": "Jane Doe",
        "recipient_email": "jane@example.com",
        "created_at": "2025-01-21T10:00:00.000000Z"
    }
}
```

### Create Order Gift Card

```http
POST /api/v3.1/user/gift-cards
Content-Type: application/json
Authorization: Bearer {token}

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

### Redeem to Wallet

```http
POST /api/v3.1/user/gift-cards/1/redeem-wallet
Authorization: Bearer {token}
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
        "amount": "200.00",
        "status": "used",
        "redemption_method": "wallet",
        "redeemed_at": "2025-01-21T10:00:00.000000Z",
        "wallet_transaction": {
            "id": 1,
            "amount": "200.00",
            "type": "credit",
            "status": "completed",
            "created_at": "2025-01-21T10:00:00.000000Z"
        }
    }
}
```

### Create Order from Gift Card

```http
POST /api/v3.1/user/gift-cards/2/create-order
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Order created from gift card successfully",
    "data": {
        "gift_card": {
            "id": 2,
            "code": "GC789012",
            "gift_type": "order",
            "status": "used",
            "redemption_method": "order",
            "order": {
                "id": 1,
                "status": "pending",
                "total_amount": "150.00",
                "created_at": "2025-01-21T10:00:00.000000Z"
            }
        },
        "order": {
            "id": 1,
            "user_id": 1,
            "vendor_id": 456,
            "product_id": 123,
            "total_amount": "150.00",
            "status": "pending",
            "payment_method": "gift_card"
        }
    }
}
```

## Error Responses

### Validation Error

```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "amount": ["The amount field is required."],
        "recipient_email": [
            "The recipient email must be a valid email address."
        ]
    }
}
```

### Not Found Error

```json
{
    "success": false,
    "message": "Gift card not found"
}
```

### Business Logic Error

```json
{
    "success": false,
    "message": "Gift card cannot be redeemed"
}
```

## Status Codes

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request (validation errors)
-   `401` - Unauthorized
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Internal Server Error

## Authentication

All endpoints require authentication:

-   **Admin APIs**: Use admin token with `Authorization: Bearer {admin_token}`
-   **User APIs**: Use user token with `Authorization: Bearer {user_token}`

## Rate Limiting

-   Admin APIs: 60 requests per minute
-   User APIs: 120 requests per minute

## File Upload Limits

-   Maximum file size: 5MB
-   Allowed formats: jpeg, png, jpg, gif, svg
-   Automatic image conversions generated

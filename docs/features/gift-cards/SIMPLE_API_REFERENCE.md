# Gift Card System - Simple API Reference

A concise reference of all gift card related API endpoints with brief descriptions.

## Admin API Endpoints (V1)

### Base URL: `/api/v1/admin/gift-card-backgrounds`

| Method | Endpoint                                                 | Description                                              |
| ------ | -------------------------------------------------------- | -------------------------------------------------------- |
| GET    | `/api/v1/admin/gift-card-backgrounds`                    | List all background images with pagination and filtering |
| POST   | `/api/v1/admin/gift-card-backgrounds`                    | Upload a new background image (super admin only)         |
| GET    | `/api/v1/admin/gift-card-backgrounds/{id}`               | Get details of a specific background image               |
| PUT    | `/api/v1/admin/gift-card-backgrounds/{id}`               | Update an existing background image                      |
| DELETE | `/api/v1/admin/gift-card-backgrounds/{id}`               | Delete a background image and its files                  |
| PATCH  | `/api/v1/admin/gift-card-backgrounds/{id}/toggle-status` | Toggle active/inactive status                            |
| GET    | `/api/v1/admin/gift-card-backgrounds/active`             | Get only active background images (public)               |

## User API Endpoints (V3.1)

### Base URL: `/api/v3.1/user/gift-cards`

| Method | Endpoint                                                    | Description                                                      |
| ------ | ----------------------------------------------------------- | ---------------------------------------------------------------- |
| GET    | `/api/v3.1/user/gift-cards/options`                         | Get available options (gift types, colors, backgrounds, amounts) |
| GET    | `/api/v3.1/user/gift-cards`                                 | List user's gift cards                                           |
| POST   | `/api/v3.1/user/gift-cards`                                 | Create a new gift card (wallet or order type)                    |
| GET    | `/api/v3.1/user/gift-cards/{id}`                            | Get gift card details by ID                                      |
| GET    | `/api/v3.1/user/gift-cards/code?code=GC123456`              | Get gift card details by code                                    |
| GET    | `/api/v3.1/user/gift-cards/my?type=sent\|received\|all`     | Get sent/received gift cards                                     |
| POST   | `/api/v3.1/user/gift-cards/{id}/redeem-wallet`              | Redeem wallet-type gift card to wallet                           |
| POST   | `/api/v3.1/user/gift-cards/{id}/create-order`               | Create order from order-type gift card                           |
| POST   | `/api/v3.1/user/gift-cards/{id}/cancel-order-redeem-wallet` | Cancel order and redeem to wallet                                |

## Query Parameters

### Admin Endpoints

-   `search` - Search by name or description
-   `status` - Filter by status ('active' or 'inactive')
-   `per_page` - Number of items per page (default: 20)

### User Endpoints

-   `per_page` - Number of items per page (default: 20)
-   `type` - Filter gift cards ('sent', 'received', 'all' - default: 'all')
-   `code` - Gift card code (required for code lookup)

## Request Body Fields

### Create Gift Card

-   `gift_type` - 'wallet' or 'order' (required)
-   `amount` - Gift card amount (required)
-   `currency` - Currency code (e.g., 'SAR') (required)
-   `background_color` - Hex color code (e.g., '#FF8B85')
-   `background_image_id` - Admin background image ID
-   `background_image` - User uploaded image media ID
-   `message` - Gift card message
-   `notes` - Admin notes
-   `expires_at` - Expiration date (optional)
-   `recipient_name` - Recipient's name (required)
-   `recipient_email` - Recipient's email
-   `recipient_number` - Recipient's phone
-   `user_id` - Existing user ID (optional)
-   `create_new_user` - Boolean to create new user
-   `new_user_name` - New user name (if creating)
-   `new_user_phone` - New user phone (if creating)
-   `new_user_email` - New user email (if creating)

### For Order Type Gift Cards (Additional)

-   `product_id` - Product ID (required for order type)
-   `offer_id` - Offer ID (required for order type)
-   `vendor_id` - Vendor ID (required for order type)

### Create Background Image

-   `name` - Background name
-   `description` - Background description (optional)
-   `background_image` - Image file (max 5MB)
-   `is_active` - Boolean (default: true)

## Authentication

-   **Admin APIs**: Admin token required (`Authorization: Bearer {admin_token}`)
-   **User APIs**: User token required (`Authorization: Bearer {user_token}`)
-   **Public Endpoints**: No authentication required

## Rate Limiting

-   Admin APIs: 60 requests per minute
-   User APIs: 120 requests per minute

## File Upload

-   Maximum file size: 5MB
-   Allowed formats: jpeg, png, jpg, gif, svg

## Status Codes

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Internal Server Error

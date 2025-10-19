# Gift Card System - API Endpoints Reference

This file contains all gift card related API endpoints with detailed descriptions for development purposes.

## Admin API Endpoints (V1)

### Gift Card Background Management

```php
// GET /api/v1/admin/gift-card-backgrounds
// Description: Retrieve all gift card background images with pagination and filtering
// Query Parameters: search, status, per_page
// Response: List of background images with pagination metadata
// Authentication: Admin token required
// Rate Limit: 60 requests per minute
```

```php
// POST /api/v1/admin/gift-card-backgrounds
// Description: Upload a new gift card background image (super admin only)
// Request Body: multipart/form-data with name, description, background_image, is_active
// Response: Created background image details
// Authentication: Super admin token required
// File Upload: Max 5MB, formats: jpeg, png, jpg, gif, svg
```

```php
// GET /api/v1/admin/gift-card-backgrounds/{id}
// Description: Retrieve details of a specific background image
// Response: Background image details
// Authentication: Admin token required
```

```php
// PUT /api/v1/admin/gift-card-backgrounds/{id}
// Description: Update an existing background image
// Request Body: multipart/form-data with optional name, description, background_image, is_active
// Response: Updated background image details
// Authentication: Super admin token required
```

```php
// DELETE /api/v1/admin/gift-card-backgrounds/{id}
// Description: Delete a background image and its associated media files
// Response: Success message
// Authentication: Super admin token required
```

```php
// PATCH /api/v1/admin/gift-card-backgrounds/{id}/toggle-status
// Description: Toggle the active/inactive status of a background image
// Response: Updated status information
// Authentication: Super admin token required
```

```php
// GET /api/v1/admin/gift-card-backgrounds/active
// Description: Retrieve only active background images for public use
// Response: List of active background images
// Authentication: None required (public endpoint)
```

## User API Endpoints (V3.1)

### Gift Card Management

```php
// GET /api/v3.1/user/gift-cards/options
// Description: Retrieve available options for creating gift cards including colors, background images, and types
// Response: Available options for gift card creation
// Authentication: User token required
// Rate Limit: 120 requests per minute
```

```php
// GET /api/v3.1/user/gift-cards
// Description: Retrieve all gift cards belonging to the authenticated user
// Query Parameters: per_page (optional, default: 20)
// Response: Paginated list of user's gift cards
// Authentication: User token required
```

```php
// POST /api/v3.1/user/gift-cards
// Description: Create a new gift card (wallet or order type)
// Request Body: JSON with type, gift_type, amount, currency, background_color, background_image_id, message, recipient_name, recipient_email, expires_at, etc.
// Response: Created gift card details
// Authentication: User token required
// Business Logic:
//   - Validates gift card type (wallet/order)
//   - Handles user creation if create_new_user is true
//   - Associates background image (admin-uploaded or user-uploaded)
//   - Generates unique gift card code
```

```php
// GET /api/v3.1/user/gift-cards/{id}
// Description: Retrieve details of a specific gift card by ID
// Response: Gift card details with relationships (order, walletTransaction, backgroundImage)
// Authentication: User token required
// Authorization: User can only access their own gift cards
```

```php
// GET /api/v3.1/user/gift-cards/code?code=GC123456
// Description: Retrieve gift card details by its unique code
// Query Parameters: code (required) - Gift card code
// Response: Gift card details
// Authentication: User token required
// Business Logic: Searches by exact code match
```

```php
// GET /api/v3.1/user/gift-cards/my?type=sent|received|all
// Description: Retrieve gift cards sent by or received by the user
// Query Parameters: type (optional) - Filter by type ('sent', 'received', 'all' - default: 'all')
// Response: Paginated list of gift cards
// Authentication: User token required
// Business Logic:
//   - sent: gift cards created by the user
//   - received: gift cards where user is recipient (by user_id, email, or phone)
//   - all: combines both sent and received
```

### Gift Card Redemption

```php
// POST /api/v3.1/user/gift-cards/{id}/redeem-wallet
// Description: Redeem a wallet-type gift card by adding the amount to user's wallet
// Response: Updated gift card with wallet transaction details
// Authentication: User token required
// Business Logic:
//   - Validates gift card can be redeemed (not expired, not used, belongs to user)
//   - Validates gift card is wallet type
//   - Creates wallet transaction
//   - Updates gift card status to 'used' and redemption_method to 'wallet'
//   - Sets redeemed_at timestamp
```

```php
// POST /api/v3.1/user/gift-cards/{id}/create-order
// Description: Redeem an order-type gift card by creating an order for the specified product/offer
// Response: Created order and updated gift card details
// Authentication: User token required
// Business Logic:
//   - Validates gift card can be redeemed (not expired, not used, belongs to user)
//   - Validates gift card is order type
//   - Creates order with gift card payment method
//   - Updates gift card status to 'used' and redemption_method to 'order'
//   - Associates order with gift card
//   - Sets redeemed_at timestamp
```

```php
// POST /api/v3.1/user/gift-cards/{id}/cancel-order-redeem-wallet
// Description: Cancel an order created from a gift card and redeem the amount to wallet instead
// Response: Updated gift card with wallet transaction details
// Authentication: User token required
// Business Logic:
//   - Validates gift card has an associated order
//   - Validates gift card was redeemed as order
//   - Cancels the associated order
//   - Creates wallet transaction for the gift card amount
//   - Updates gift card redemption_method to 'wallet'
//   - Updates redeemed_at timestamp
```

## Request/Response Examples

### Create Wallet Gift Card Request

```json
{
    "type": "product",
    "gift_type": "wallet",
    "amount": 200,
    "currency": "SAR",
    "background_image_id": 1,
    "message": "Enjoy your gift!",
    "recipient_name": "Jane Doe",
    "recipient_email": "jane@example.com",
    "expires_at": "2025-12-31"
}
```

### Create Order Gift Card Request

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

### Create New User Gift Card Request

```json
{
    "type": "product",
    "gift_type": "wallet",
    "amount": 100,
    "currency": "SAR",
    "create_new_user": true,
    "new_user_name": "John Doe",
    "new_user_phone": "+966501234567",
    "new_user_email": "john@example.com",
    "message": "Welcome to our platform!",
    "background_color": "#FF8B85"
}
```

## Error Handling

### Validation Errors (422)

```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "amount": ["The amount field is required."],
        "recipient_email": [
            "The recipient email must be a valid email address."
        ],
        "gift_type": ["The selected gift type is invalid."]
    }
}
```

### Business Logic Errors (400)

```json
{
    "success": false,
    "message": "Gift card cannot be redeemed"
}
```

### Not Found Errors (404)

```json
{
    "success": false,
    "message": "Gift card not found"
}
```

### Authorization Errors (403)

```json
{
    "success": false,
    "message": "Access denied"
}
```

## Database Relationships

### GiftCard Model

```php
// Relationships
public function user() // Belongs to User (recipient)
public function createdBy() // Belongs to User (creator)
public function order() // Has one Order (if order type)
public function walletTransaction() // Has one WalletTransaction (if wallet type)
public function backgroundImage() // Belongs to GiftCardBackground (if admin background)
```

### GiftCardBackground Model

```php
// Relationships
public function media() // Has one Media (background image)
public function giftCards() // Has many GiftCard (gift cards using this background)
```

## Status Constants

### Gift Card Statuses

```php
const STATUS_ACTIVE = 'active';
const STATUS_USED = 'used';
const STATUS_EXPIRED = 'expired';
const STATUS_CANCELLED = 'cancelled';
```

### Gift Card Types

```php
const GIFT_TYPE_WALLET = 'wallet';
const GIFT_TYPE_ORDER = 'order';
```

### Redemption Methods

```php
const REDEMPTION_PENDING = 'pending';
const REDEMPTION_WALLET = 'wallet';
const REDEMPTION_ORDER = 'order';
```

## Configuration

### Gift Card Config (config/gift_card.php)

```php
return [
    'colors' => [
        '#FF8B85', '#FFB3BA', '#FFD3B6', '#FFEFD1',
        '#DCEDC8', '#B2DFDB', '#B3E5FC', '#E1BEE7'
    ],
    'code_length' => 8,
    'expiration_days' => 365,
    'max_file_size' => 5242880, // 5MB
];
```

## Development Notes

### File Upload Process

1. User uploads image to temporary endpoint
2. Image is stored with TemporaryUpload model
3. When creating gift card, image is associated with GiftCard model
4. Collection name is set to 'gift_card_backgrounds'

### Code Generation

-   Gift card codes are generated using Str::random()
-   Length is configurable via config
-   Codes are unique across all gift cards

### Expiration Handling

-   Gift cards can have custom expiration dates
-   System checks expiration on redemption attempts
-   Expired gift cards cannot be redeemed

### Order Integration

-   Order gift cards create actual orders in the system
-   Orders use 'gift_card' as payment method
-   Orders can be cancelled and amount redeemed to wallet

### Wallet Integration

-   Wallet gift cards create wallet transactions
-   Transactions are of type 'credit'
-   Amount is added to user's wallet balance

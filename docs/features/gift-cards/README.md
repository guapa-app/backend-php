# Gift Card System

## Overview

The Gift Card System is a comprehensive feature that allows users to create, send, and redeem gift cards within the platform. The system supports two types of gift cards:

1. **Wallet Gift Cards**: Directly credit the recipient's wallet upon redemption
2. **Order Gift Cards**: Create an order for a specific product or offer on behalf of the recipient

## Features

### Core Functionality

-   **Dual Gift Card Types**: Support for both wallet and order-based gift cards
-   **Background Customization**: Users can choose from admin-uploaded background images or solid colors
-   **Flexible Redemption**: Order gift cards can be cancelled and redeemed to wallet instead
-   **User Management**: Support for creating new users or selecting existing ones
-   **Expiration Management**: Gift cards can have expiration dates
-   **Status Tracking**: Comprehensive status tracking throughout the gift card lifecycle

### Admin Features

-   **Background Management**: Super admins can upload and manage background images
-   **Filament Integration**: Full admin panel integration for background management
-   **Policy Protection**: Restricted access to super admins only for background management

### User Features

-   **Gift Card Creation**: Create personalized gift cards with custom messages
-   **Redemption Options**: Multiple redemption methods based on gift card type
-   **Order Management**: Cancel orders and redeem amounts back to wallet
-   **History Tracking**: View sent and received gift cards

## Documentation

### 📖 [Complete System Documentation](GIFT_CARD_BACKGROUNDS_API.md)

Comprehensive documentation covering the entire gift card system including:

-   System architecture and design
-   Database schema and relationships
-   Business logic and workflows
-   Configuration options
-   Security considerations
-   Usage examples and best practices

### 🔌 [API Reference](API_REFERENCE.md)

Detailed API documentation with:

-   All endpoint specifications
-   Request/response examples
-   Error handling
-   Authentication requirements
-   Rate limiting information

### 📋 [Simple API Reference](SIMPLE_API_REFERENCE.md)

Concise endpoint reference with:

-   All endpoints in table format
-   Brief descriptions
-   Query parameters
-   Request body fields
-   Authentication and rate limiting info

### 💻 [Development Endpoints](ENDPOINTS.md)

Development-focused documentation with:

-   Commented endpoint descriptions
-   Business logic notes
-   Database relationships
-   Configuration details
-   Implementation examples

## Quick Start

### For Developers

1. **Database Setup**

    ```bash
    php artisan migrate
    ```

2. **Configuration**

    - Ensure `gift_card.php` config file is published
    - Configure background colors and other settings

3. **Admin Panel Access**
    - Access Filament admin panel
    - Navigate to Gift Card Backgrounds section
    - Upload background images (super admin only)

### For Users

1. **Create Gift Card**

    ```http
    POST /api/v3.1/user/gift-cards
    ```

2. **Redeem Gift Card**

    - Wallet type: `POST /api/v3.1/user/gift-cards/{id}/redeem-wallet`
    - Order type: `POST /api/v3.1/user/gift-cards/{id}/create-order`

3. **Cancel Order and Redeem to Wallet**
    ```http
    POST /api/v3.1/user/gift-cards/{id}/cancel-order-redeem-wallet
    ```

## System Architecture

### Models

-   `GiftCard`: Main gift card model with dual type support
-   `GiftCardBackground`: Admin-uploaded background images
-   `Media`: File management for user-uploaded backgrounds
-   `TemporaryUpload`: Temporary file handling

### Controllers

-   `GiftCardController`: User API endpoints (V3.1)
-   `GiftCardBackgroundController`: Admin API endpoints (V1)

### Resources

-   `GiftCardResource`: API response formatting
-   `GiftCardBackgroundResource`: Admin response formatting

### Admin Panel

-   `GiftCardBackgroundResource`: Filament admin resource
-   `GiftCardBackgroundPolicy`: Access control policy

## Gift Card Types

### Wallet Gift Cards

-   **Purpose**: Direct wallet credit
-   **Redemption**: Adds amount to user's wallet balance
-   **Use Case**: Flexible spending, general gifts

### Order Gift Cards

-   **Purpose**: Specific product/offer purchase
-   **Redemption**: Creates order for specified item
-   **Use Case**: Targeted gifts, promotional offers
-   **Flexibility**: Can be cancelled and redeemed to wallet

## Status Flow

```
Active → Used (Wallet/Order) → Completed
   ↓
Expired
```

## Background System

### Admin Backgrounds

-   Uploaded by super admins
-   Managed through Filament admin panel
-   Available to all users
-   Can be activated/deactivated

### User Backgrounds

-   Uploaded by individual users
-   Temporary upload system
-   Associated with specific gift cards

### Solid Colors

-   Predefined color palette
-   Configurable through config file
-   Always available

## Security Features

-   **Policy Protection**: Super admin only access for background management
-   **File Validation**: Strict file type and size validation
-   **Authentication**: All endpoints require proper authentication
-   **Authorization**: Role-based access control
-   **Input Validation**: Comprehensive request validation

## Configuration

### Gift Card Settings

```php
// config/gift_card.php
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

### File Upload Settings

-   Maximum file size: 5MB
-   Allowed formats: jpeg, png, jpg, gif, svg
-   Automatic image conversions generated

## Migration Guide

### From Previous System

1. Run the enhancement migration
2. Update any existing gift card creation code
3. Test new redemption flows
4. Update frontend to support new features

### Database Changes

-   New fields added to `gift_cards` table
-   New `gift_card_backgrounds` table created
-   Relationships updated for order and wallet integration

## Support

For technical support or questions about the gift card system:

-   Check the [Complete System Documentation](GIFT_CARD_BACKGROUNDS_API.md)
-   Review the [API Reference](API_REFERENCE.md)
-   Contact the development team

## Changelog

### Version 2.0 (Current)

-   Added dual gift card types (wallet/order)
-   Implemented order cancellation with wallet redemption
-   Enhanced background management system
-   Added comprehensive admin panel integration
-   Improved API structure and documentation

### Version 1.0 (Previous)

-   Basic gift card functionality
-   Single background system
-   Simple redemption process

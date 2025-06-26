# Gift Card API Use Cases & User Stories

## Table of Contents

-   [User APIs](#user-apis)
    -   [Endpoints](#user-endpoints)
    -   [User Stories & Use Cases](#user-stories--use-cases)
    -   [Request/Response Examples](#user-requestresponse-examples)
-   [Admin APIs](#admin-apis)
    -   [Endpoints](#admin-endpoints)
    -   [Admin Stories & Use Cases](#admin-stories--use-cases)
    -   [Request/Response Examples](#admin-requestresponse-examples)

---

# User APIs

## User Endpoints

| Method | Endpoint                                                  | Description                       |
| ------ | --------------------------------------------------------- | --------------------------------- |
| GET    | /api/user/v3.1/gift-cards                                 | List all user's gift cards        |
| GET    | /api/user/v3.1/gift-cards/my                              | List sent/received/all gift cards |
| GET    | /api/user/v3.1/gift-cards/options                         | Get available options             |
| GET    | /api/user/v3.1/gift-cards/{id}                            | Get details of a gift card        |
| GET    | /api/user/v3.1/gift-cards/code                            | Get gift card by code             |
| POST   | /api/user/v3.1/gift-cards                                 | Create a new gift card            |
| POST   | /api/user/v3.1/gift-cards/{id}/redeem-wallet              | Redeem wallet-type gift card      |
| POST   | /api/user/v3.1/gift-cards/{id}/create-order               | Redeem order-type gift card       |
| POST   | /api/user/v3.1/gift-cards/{id}/cancel-order-redeem-wallet | Cancel order and redeem to wallet |

## User Stories & Use Cases

### 1. View All My Gift Cards

-   **Story:** As a user, I want to see all gift cards I've sent or received.
-   **Use Case:**
    -   GET `/api/user/v3.1/gift-cards/my?type=all`
    -   **Success:** Returns paginated list.
    -   **No cards:** Returns empty list.
    -   **Unauthorized:** Returns 401.

### 2. Create a New Gift Card

-   **Story:** As a user, I want to send a gift card to a friend.
-   **Use Case:**
    -   POST `/api/user/v3.1/gift-cards`
    -   **Success:** Returns created card.
    -   **Validation error:** Returns 422 with errors.
    -   **Unauthorized:** Returns 401.

### 3. Redeem a Gift Card to My Wallet

-   **Story:** As a user, I want to add a wallet-type gift card's value to my wallet.
-   **Use Case:**
    -   POST `/api/user/v3.1/gift-cards/{id}/redeem-wallet`
    -   **Success:** Returns updated card and wallet transaction.
    -   **Already redeemed/expired:** Returns error.
    -   **Not owner:** Returns 404 or error.
    -   **Unauthorized:** Returns 401.

### 4. Redeem a Gift Card for an Order

-   **Story:** As a user, I want to use a gift card to pay for an order.
-   **Use Case:**
    -   POST `/api/user/v3.1/gift-cards/{id}/create-order`
    -   **Success:** Returns created order.
    -   **Already redeemed/expired:** Returns error.
    -   **Not owner:** Returns 404 or error.
    -   **Unauthorized:** Returns 401.

### 5. Cancel an Order and Redeem to Wallet

-   **Story:** As a user, I want to cancel an order and get the gift card value in my wallet.
-   **Use Case:**
    -   POST `/api/user/v3.1/gift-cards/{id}/cancel-order-redeem-wallet`
    -   **Success:** Returns updated card and wallet transaction.
    -   **No order:** Returns error.
    -   **Not owner:** Returns 404 or error.
    -   **Unauthorized:** Returns 401.

### 6. See Available Options for Creating a Gift Card

-   **Story:** As a user, I want to know what types, colors, and backgrounds I can use.
-   **Use Case:**
    -   GET `/api/user/v3.1/gift-cards/options`
    -   **Success:** Returns options.
    -   **Unauthorized:** Returns 401.

---

## User Request/Response Examples

### Create Gift Card (Wallet)

**Request:**

```json
POST /api/user/v3.1/gift-cards
{
  "gift_type": "wallet",
  "amount": 200,
  "currency": "SAR",
  "background_color": "#FF8B85",
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
        "gift_type": "wallet",
        "amount": "200.00",
        "currency": "SAR",
        "status": "active",
        "recipient_name": "Jane Doe",
        "recipient_email": "jane@example.com",
        "created_at": "2025-01-21T10:00:00.000000Z"
    }
}
```

### Redeem to Wallet

**Request:**

```http
POST /api/user/v3.1/gift-cards/1/redeem-wallet
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

### Error: Already Redeemed

**Response:**

```json
{
    "success": false,
    "message": "Gift card cannot be redeemed"
}
```

---

# Admin APIs

## Admin Endpoints

| Method | Endpoint                                            | Description                        |
| ------ | --------------------------------------------------- | ---------------------------------- |
| GET    | /admin-api/gift-cards/gift-cards                    | List all gift cards (admin)        |
| POST   | /admin-api/gift-cards/gift-cards                    | Create a new gift card (admin)     |
| GET    | /admin-api/gift-cards/gift-cards/{id}               | Get details of a gift card (admin) |
| PUT    | /admin-api/gift-cards/gift-cards/{id}               | Update a gift card (admin)         |
| DELETE | /admin-api/gift-cards/gift-cards/{id}               | Delete a gift card (admin)         |
| GET    | /admin-api/gift-cards/gift-cards/options            | Get available options (admin)      |
| GET    | /admin-api/gift-cards/gift-cards/code               | Get gift card by code (admin)      |
| POST   | /admin-api/gift-cards/gift-cards/bulk-update-status | Bulk update status (admin)         |

## Admin Stories & Use Cases

### 1. Manage All Gift Cards

-   **Story:** As an admin, I want to view, create, update, and delete any gift card.
-   **Use Case:**
    -   GET/POST/PUT/DELETE `/admin-api/gift-cards/gift-cards`
    -   **Success:** Returns data or confirmation.
    -   **Validation error:** Returns 422.
    -   **Not found:** Returns 404.
    -   **Unauthorized:** Returns 401.

### 2. Bulk Update Gift Card Statuses

-   **Story:** As an admin, I want to activate, deactivate, or expire multiple cards at once.
-   **Use Case:**
    -   POST `/admin-api/gift-cards/gift-cards/bulk-update-status`
    -   **Success:** Returns count.
    -   **Validation error:** Returns 422.
    -   **Unauthorized:** Returns 401.

### 3. See Available Options for Creating a Gift Card

-   **Story:** As an admin, I want to know what types, colors, and backgrounds are available.
-   **Use Case:**
    -   GET `/admin-api/gift-cards/gift-cards/options`
    -   **Success:** Returns options.
    -   **Unauthorized:** Returns 401.

---

## Admin Request/Response Examples

### List Gift Cards

**Request:**

```http
GET /admin-api/gift-cards/gift-cards
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
    "success": true,
    "message": "Success",
    "data": [
        {
            "id": 1,
            "code": "GC123456",
            "gift_type": "wallet",
            "amount": "200.00",
            "currency": "SAR",
            "status": "active",
            "recipient_name": "Jane Doe",
            "recipient_email": "jane@example.com",
            "created_at": "2025-01-21T10:00:00.000000Z"
        }
    ]
}
```

### Bulk Update Status

**Request:**

```json
POST /admin-api/gift-cards/gift-cards/bulk-update-status
{
  "gift_card_ids": [1,2,3],
  "status": "expired"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Bulk updated successfully",
    "data": { "updated_count": 3 }
}
```

---

# Error & Edge Case Scenarios

-   **Unauthorized:**
    ```json
    { "success": false, "message": "Unauthenticated." }
    ```
-   **Not Found:**
    ```json
    { "success": false, "message": "Not found" }
    ```
-   **Validation Error:**
    ```json
    { "success": false, "message": "Validation error", "errors": { ... } }
    ```

---

# Notes

-   All endpoints require authentication unless otherwise noted.
-   Use Bearer tokens for Authorization headers.
-   For full details on request fields, see the API reference or request validation rules in the codebase.

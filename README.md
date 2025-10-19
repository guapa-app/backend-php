# Guapa - Laravel Backend

A comprehensive Laravel backend application for the Guapa platform, providing robust APIs and admin management capabilities.

## Important Links

-   [Nozom](https://nozom.io)
-   [AQuadic](https://aquadic.com)
-   [API Documentation](https://guapa.com.sa/docs/)
-   [Live Backend](https://guapa.com.sa/)

## Documentation

### 📖 [API Documentation](https://guapa.com.sa/docs/)

Complete API documentation with interactive testing interface.

### 🎁 [Gift Card System Documentation](docs/features/gift-cards/README.md)

Comprehensive documentation for the gift card feature including:

-   [Complete System Documentation](docs/features/gift-cards/GIFT_CARD_BACKGROUNDS_API.md)
-   [API Reference](docs/features/gift-cards/API_REFERENCE.md)

## Features

-   **User Management**: Complete user registration, authentication, and profile management
-   **Gift Card System**: Dual-type gift cards (wallet & order) with background customization
-   **Order Management**: Comprehensive order processing and tracking
-   **Admin Panel**: Filament-based admin interface with role-based access
-   **Media Management**: Advanced file upload and management with Spatie Media Library
-   **Multi-language Support**: Arabic and English localization
-   **API Versioning**: Structured API versions for backward compatibility

## Requirements

-   PHP Version: `^8.0`
-   MySQL 5.7 or higher
-   Working mailing service for email verification and password reset
-   Access to [AQ Nova Repo.](https://github.com/AQuadic/nova_laravel)
-   Check [Spatie Media Library requirements](https://spatie.be/docs/laravel-medialibrary/v9/requirements)

## Installation

Please check the [official Laravel installation guide](https://laravel.com/docs/8.x/installation#installation) for server requirements before you start.

### 1. Clone the repository

```bash
git clone https://github.com/AQuadic/cosmo_laravel.git
cd cosmo_laravel
```

### 2. Install dependencies

```bash
composer install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database setup

Set the database connection in `.env` before migrating:

```bash
php artisan migrate:fresh
```

### 5. Storage setup

```bash
php artisan storage:link
```

### 6. System setup

```bash
# Create system roles (required before creating admins)
php artisan roles:setup

# Setup Passport tokens
php artisan passport:install
php artisan passport:client --password --provider=admins
```

### 7. Build admin panel

```bash
npm install
npm run prod
```

### 8. Create admin user

```bash
php artisan nova:user
```

### 9. Seed database

```bash
php artisan db:seed
```

### 10. Start development server

```bash
php artisan serve
```

You can now access the server at http://localhost:8000

## Environment Variables

Key environment variables to configure in `.env`:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=guapa
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Passport
PASSPORT_PRIVATE_KEY=
PASSPORT_PUBLIC_KEY=
```

## API Structure

### Version 1 (V1)

-   Admin APIs
-   Legacy endpoints

### Version 2 (V2)

-   Enhanced user APIs
-   Improved response formats

### Version 3 (V3)

-   Latest user APIs
-   Advanced features

### Version 3.1 (V3.1)

-   Gift card system
-   Enhanced order management

## Development

### Running Tests

```bash
php artisan test
```

### Code Quality

```bash
# Run PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# Run PHPStan
./vendor/bin/phpstan analyse
```

### Database Migrations

```bash
# Create new migration
php artisan make:migration migration_name

# Run migrations
php artisan migrate

# Rollback
php artisan migrate:rollback
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

For support and questions:

-   Check the [API Documentation](https://guapa.com.sa/docs/)
-   Review feature-specific documentation in `docs/features/`
-   Contact the development team

## License

This project is proprietary software developed by Nozom and AQuadic.

## Project Status

Active development - New features and improvements are regularly added.

# PTT Laravel CRM System

A modern Customer Relationship Management (CRM) system built with Laravel, Livewire, Alpine.js, and Tailwind CSS (TALL stack).

## Features

- **Company Management**: Track and manage company information, status, and relationships
- **Contact Management**: Handle multiple contacts per company with primary contact designation
- **Activity Tracking**: Comprehensive activity logging for all system events
- **Event-Driven Architecture**: Robust notification and event system
- **Real-time Updates**: Live updates using Livewire components
- **Modern UI**: Beautiful and responsive interface using Tailwind CSS and Alpine.js

## Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: 
  - Livewire 3.x
  - Alpine.js
  - Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Queue System**: Redis/Laravel Queue

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Redis (optional, for queues)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd ptt-laravel
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:
```bash
php artisan migrate
```

8. Start the development server:
```bash
php artisan serve
```

9. In a separate terminal, start Vite:
```bash
npm run dev
```

## Documentation

Detailed documentation is available in the `docs` directory:

- [System Design](docs/system-design.md)
- [Event System](docs/event-system.md)
- [Activity Tracking](docs/activity-tracking.md)
- [UI Components](docs/design-system.md)

## Development

### Code Style

This project follows PSR-12 coding standards and Laravel best practices. To maintain code quality:

```bash
# Run PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# Run PHPStan
./vendor/bin/phpstan analyse
```

### Testing

```bash
# Run PHPUnit tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

# ChangChang Integrated Inventory and Sales System

A comprehensive inventory and sales management system built with Laravel, designed to streamline business operations for small to medium-sized enterprises.

## Walkthrough Video

[Watch on YouTube](https://www.youtube.com/watch?v=2PG782-CWak)


## Features

### Inventory Management
- Product catalog with detailed information (SKU, brand, weight, pricing)
- Real-time stock tracking and monitoring
- Automatic SKU generation
- Product categorization
- Image management for products
- Low stock alerts and reorder level management
- Inventory transaction history

### Sales Management
- Point of Sale (POS) interface
- Real-time sales processing
- Customer management
- Sales history and reporting
- Multiple payment method support
- Discount management
- Sales receipt generation

### Supplier Management
- Supplier database
- Purchase order tracking
- Supplier performance monitoring
- Purchase history

### Reporting and Analytics
- Daily sales reports
- Inventory status reports
- Sales transaction history
- Low stock reports
- Customer purchase history

### Security
- User authentication
- Role-based access control
- Transaction logging
- Secure payment processing

## Technical Stack

- **Backend Framework**: Laravel
- **Database**: MySQL
- **Frontend**: Blade templates with Tailwind CSS
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

## System Requirements

- PHP >= 8.1
- MySQL >= 5.7
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)

## Installation

1. Clone the repository
```bash
git clone https://github.com/loftyyyy/ChangChangIntegratedInventoryAndSalesSystem.git
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Copy environment file
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Configure your database in .env file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations
```bash
php artisan migrate
```

8. Start the development server
```bash
php artisan serve
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Support

For support, email team@changchang.com or create an issue in the repository.

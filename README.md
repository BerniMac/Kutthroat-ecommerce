# KTB Kutthroat Ammunition - E-Commerce Store

A PHP e-commerce store built with Bootstrap 5, MySQL and Stripe payments.

## Requirements
- WAMP / XAMPP / LAMP
- PHP 8.0+
- MySQL
- Composer
- Stripe account

## Setup

### 1. Clone the repo
git clone https://github.com/BerniMac/Kutthroat-ecommerce.git

### 2. Install dependencies
composer install

### 3. Set up the database
- Create a database called `Kutthroat` in phpMyAdmin
- Import `database.sql` from the root of this project

### 4. Configure the project
- Copy `config.example.php` to `config.php`
- Fill in your database credentials in `core/init.php`
- Fill in your Stripe keys in `config.php`

## Database Tables
- `products` — store products with sizes and images
- `brand` — product brands
- `catergories` — navigation categories
- `cart` — session-based shopping cart
- `transactions` — completed orders

## Built With
- PHP 8
- MySQL
- Bootstrap 5
- Stripe PHP SDK
- Fotorama (product image gallery)

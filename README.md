# CoffeeBrew

**A Point of Sale (POS) system for coffee shops built with Laravel 12 and Livewire.**

## About CoffeeBrew

CoffeeBrew is a POS system designed for coffee shops. It allows customers to place orders by scanning a QR code with their phone, and admins to manage sales and transactions via the web interface.

## Features

-   QR code scanning for customers to access the menu
-   Interactive menu display with name, price, description, and category
-   Self-ordering system for customers using their mobile devices
-   Admin dashboard to manage menu items and transactions
-   Invoice generation and receipt printing
-   Built using Laravel 12 with Livewire for interactive user experience

## Technologies Used

-   Laravel 12
-   Livewire
-   MySQL
-   Bootstrap (for UI design)
-   JavaScript & AJAX

## Installation

```sh
git clone https://github.com/your-repo/coffeebrew.git
cd coffeebrew
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Usage

### For Customers:

1. Scan the QR code placed on the table.
2. Browse the menu and select items.
3. Confirm the order and wait for processing.

### For Admins:

1. Log in to the admin panel.
2. Manage menu items (add, edit, delete).
3. Monitor and process customer orders.
4. Generate and print receipts.

## Author

Created by **Ebenhaiser Jonathan Caprisiano**  
Connect with me on [LinkedIn](https://www.linkedin.com/in/ebenhaiser-caprisiano/)

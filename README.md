<!DOCTYPE html>
<html>
<head>
    <title>CoffeeBrew - POS System for Coffee Shop</title>
</head>
<body>
    <h1>CoffeeBrew</h1>
    <p><strong>A Point of Sale (POS) system for coffee shops built with Laravel 12 and Livewire.</strong></p>
    
    <h2>About CoffeeBrew</h2>
    <p>CoffeeBrew is a POS system designed for coffee shops. It allows customers to place orders by scanning a QR code with their phone, and admins to manage sales and transactions via the web interface.</p>
    
    <h2>Features</h2>
    <ul>
        <li>QR code scanning for customers to access the menu</li>
        <li>Interactive menu display with name, price, description, and category</li>
        <li>Self-ordering system for customers using their mobile devices</li>
        <li>Admin dashboard to manage menu items and transactions</li>
        <li>Invoice generation and receipt printing</li>
        <li>Built using Laravel 12 with Livewire for interactive user experience</li>
    </ul>
    
    <h2>Technologies Used</h2>
    <ul>
        <li>Laravel 12</li>
        <li>Livewire</li>
        <li>MySQL</li>
        <li>Bootstrap (for UI design)</li>
        <li>JavaScript & AJAX</li>
    </ul>
    
    <h2>Installation</h2>
    <pre>
    git clone https://github.com/your-repo/coffeebrew.git
    cd coffeebrew
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --seed
    php artisan serve
    </pre>
    
    <h2>Usage</h2>
    <p><strong>For Customers:</strong></p>
    <ol>
        <li>Scan the QR code placed on the table.</li>
        <li>Browse the menu and select items.</li>
        <li>Confirm the order and wait for processing.</li>
    </ol>
    
    <p><strong>For Admins:</strong></p>
    <ol>
        <li>Log in to the admin panel.</li>
        <li>Manage menu items (add, edit, delete).</li>
        <li>Monitor and process customer orders.</li>
        <li>Generate and print receipts.</li>
    </ol>
    
    <h2>Author</h2>
    <p>Created by <strong>Ebenhaiser Jonathan Caprisiano</strong></p>
    <p>Connect with me on <a href="https://www.linkedin.com/in/ebenhaiser-caprisiano/" target="_blank">LinkedIn</a></p>
</body>
</html>

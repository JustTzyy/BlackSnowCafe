# BlackSnow Cafe

A point-of-sale and inventory management system for a coffee shop, built with PHP and MySQL.

## Features

- **Role-based access** – separate Admin and Cashier dashboards
- **Cashier POS** – browse Drinks, Pastries and Meals, choose sizes and add-ons, take cash payments and compute change
- **Product management** – products, categories, add-ons, suppliers and stock-in history
- **Employee management** – employees, roles and user accounts
- **Sales reports** – sales history and transaction history

## Tech Stack

PHP 8, MySQL / MariaDB, HTML, CSS, JavaScript

## Getting Started

### Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL) or any PHP 8 / MySQL stack

### Installation

1. Clone the repository into your web root:
   ```bash
   git clone https://github.com/JustTzyy/BlackSnowCafe.git C:/xampp/htdocs/BlackSnowCafe
   ```
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Create a database named `coffee_shop` and import `database/coffee_shop.sql` (phpMyAdmin or the MySQL CLI).
4. Check the database settings in `Connection/database.php`.
5. Create your first admin user and role in the database. Passwords are stored with PHP's `password_hash()`.
6. Open `http://localhost/BlackSnowCafe/pages/login.php`.

## Project Structure

```
Connection/      Database connection
authentication/  Session / access checks
handlers/        Form and action handlers (CRUD, login, orders)
pages/           Admin and cashier pages
designs/         Stylesheets, scripts and images
database/        SQL schema and sample data
```

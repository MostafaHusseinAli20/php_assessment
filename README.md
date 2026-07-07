# Inventory Management API

A RESTful Inventory Management API built with **Laravel 10** that provides complete product management functionality, inventory tracking, stock adjustment, caching, and automated testing.

This project was developed following clean architecture principles using the **Repository Pattern**, **PostgreSQL**, **Redis caching**, and **Feature Testing**.

---

# Features

* Product CRUD operations
* UUID primary keys
* Product stock management
* Increase and decrease product stock
* Low stock detection
* PostgreSQL database
* Redis caching
* API Resources
* Repository Pattern
* Route Model Binding
* Form Request Validation
* Soft Deletes
* Pagination
* Feature Tests

---

# Tech Stack

* PHP 8.1+
* Laravel 10
* PostgreSQL
* Redis
* PHPUnit

---

# Project Structure

```
app
├── Http
│   ├── Controllers
│   ├── Requests
│   └── Resources
│
├── Interfaces
│
├── Repositories
│
├── Models
│
└── Traits
```

The application follows the Repository Pattern to separate business logic from controllers.

Controllers are responsible only for handling HTTP requests while all business logic is implemented inside repositories.

---

# API Endpoints

## Products

| Method | Endpoint                   | Description         |
| ------ | -------------------------- | ------------------- |
| GET    | /api/v1/products           | Get all products    |
| POST   | /api/v1/products           | Create product      |
| GET    | /api/v1/products/{product} | Get product details |
| PUT    | /api/v1/products/{product} | Update product      |
| DELETE | /api/v1/products/{product} | Soft delete product |

---

## Inventory

### Adjust Stock

```
PUT /api/v1/products/{product}/adjust-stock
```

Request Body

```json
{
    "action":"increment",
    "quantity":5
}
```

or

```json
{
    "action":"decrement",
    "quantity":3
}
```

Business Rules

* Increment increases available stock.
* Decrement decreases stock.
* Stock can never become negative.
* Returns HTTP 422 if requested quantity exceeds available stock.

---

### Low Stock Products

```
GET /api/v1/products/low-stock
```

Returns products where

```
stock_quantity <= low_stock_threshold
```

---

# Validation

Laravel Form Requests are used for request validation.

Examples include:

* Required fields
* Numeric validation
* UUID Route Model Binding
* SKU uniqueness
* Enum validation
* Minimum stock quantity
* Positive price validation

---

# Database

Database: PostgreSQL

Product fields

* UUID Primary Key
* SKU (Unique)
* Name
* Description
* Price
* Stock Quantity
* Low Stock Threshold
* Status
* Soft Deletes
* Timestamps

---

# Caching

Redis is used to cache the product listing endpoint.

Cached endpoint

```
GET /products
```

Cache lifetime

```
10 Minutes
```

The cache is automatically invalidated after:

* Creating a product
* Updating a product
* Deleting a product
* Adjusting stock

---

# Architecture Decisions

This project follows several architectural practices:

* Repository Pattern to separate business logic.
* Interface-based dependency injection.
* Route Model Binding.
* API Resource responses.
* Form Request validation.
* UUID identifiers instead of auto-increment IDs.
* Database transactions for write operations.
* Redis caching for product listing.
* Feature testing for API endpoints.

---

# Error Handling

The API returns consistent JSON responses.

Example

```json
{
    "success": false,
    "message": "Something went wrong.",
    "errors": {}
}
```

Validation errors return HTTP 422 with appropriate validation messages.

---

# Testing

Feature tests were implemented for:

* Get Products
* Create Product
* Show Product
* Update Product
* Delete Product
* Increment Stock
* Prevent Invalid Stock Decrement
* Low Stock Endpoint

Run tests

```bash
php artisan test
```

Current Result

```
PASS Tests: 10 passed (24 assertions)
```

---

# Installation

Clone the repository

```bash
git clone https://github.com/your-username/inventory-management-api.git
```

Enter the project

```bash
cd inventory-management-api
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure PostgreSQL and Redis inside the `.env` file.

Run migrations

```bash
php artisan migrate
```

Start the application

```bash
php artisan serve
```

---

# Postman

A Postman Collection is included with this project for testing all available endpoints.

---

# Future Improvements

* Docker support
* Authentication & Authorization
* Product Categories
* Inventory Reports
* Advanced Cache Tags
* API Documentation using Swagger

---

# Author

**Mostafa Hussein**

Full Stack Laravel Developer

# Laravel Product with AJAX and JSON Saving

This project is a simple CRUD (Create, Read, Update, Delete) application built with **Laravel**. It demonstrates:

- Storing data in a **MySQL** database
- Storing data concurrently in a **JSON** file
- Using **AJAX** (in a separate `public/js/product.js` file) for creating and editing products
- Calculating and displaying total values in a table
- Editing products via a Bootstrap modal

## Features

1. **Migration & Model**: A `products` table is created in the database.
2. **Controller**:
    - `index()` displays a form to add new products and a table of existing products.
    - `store()` saves new products (to DB and JSON) via AJAX.
    - `update()` edits existing products (DB and regenerates JSON) via AJAX.
3. **Routes**:
    - `GET /` -> Show products and form
    - `POST /products` -> Store a new product
    - `POST /products/{id}/edit` -> Update an existing product
4. **Blade Views**:
    - `resources/views/products.blade.php` uses Bootstrap for styling.
    - Includes a form and a table of products.
    - Loads the external JavaScript `product.js` for AJAX handling.
5. **JSON File**:
    - Maintained in `storage/app/products.json`.
    - Automatically updated whenever a product is added or edited.

## Requirements

- **PHP** ^8.0
- **Composer**
- **MySQL** (or another supported database, but the default instructions use MySQL)
- **Node.js** (optional if you want to compile assets differently, but not strictly necessary here since we are using CDN-based Bootstrap and jQuery)

## Installation

1. **Clone or Download** this repository:
   ```bash
   git clone https://github.com/sajidijaz/product-ajax-demo.git
   cd product-ajax-demo

2. **Install Composer Dependencies**

   ```bash
      composer install

3. **Create & Configure** `.env`:
    - Copy `.env.example` to `.env`
    - Update your database credentials in `.env`, e.g.:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=product_ajax_demo
   DB_USERNAME=root
   DB_PASSWORD=secret

4. (Create the database `product_ajax_demo` in MySQL, or rename to your preference.)

5. **Generate an Application Key:**
   ```bash
   php artisan key:generate

6. **Run Migrations:**
   ```bash
   php artisan migrate

7. **Serve the Application:**
   ```bash
   php artisan serve
Visit http://127.0.0.1:8000 in your browser.

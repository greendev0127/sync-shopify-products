# Shopify Product Sync

This project is a Laravel application that synchronizes products from a Shopify store to a local database. The synchronization process is handled by a background job, ensuring a smooth user experience.

## Features

  - **Manual Sync:** Trigger product synchronization from the Shopify store to the local database with a single button click.
  - **Background Processing:** Utilizes Laravel's queue system for efficient, non-blocking synchronization.
  - **API Integration:** Securely connects to the Shopify Admin API to fetch product data.
  - **Frontend Dashboard:** A simple Vue.js frontend displays all synchronized products.
  - **Scalable Architecture:** The project structure is designed to be robust and scalable, capable of handling a large number of products without affecting website performance.

## Prerequisites

Before you begin, ensure you have the following installed:

  - PHP \>= 8.1
  - Composer
  - Node.js & npm
  - MySQL or another database
  - A Shopify store with a private app (or custom app) configured. You will need the **Store URL** and a **Storefront Access Token**. The app must have **read\_products** permission.

## Installation

Follow these steps to get the project up and running.

### 1\. Clone the Repository

```bash
git clone [your-repository-url]
cd [your-project-folder]
```

### 2\. Configure Environment Variables

Create a copy of the `.env.example` file and name it `.env`.

```bash
cp .env.example .env
```

Now, open the `.env` file and fill in your database credentials and your Shopify store details.

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopify_sync
DB_USERNAME=root
DB_PASSWORD=

# Shopify Credentials
SHOPIFY_SHOP_NAME=your-store-name
SHOPIFY_ACCESS_TOKEN=your_shopify_access_token
```

### 3\. Install Dependencies

Install the PHP and JavaScript dependencies.

```bash
composer install
npm install
npm run dev
```

### 4\. Database Setup

Run the database migrations to create the necessary tables, including the `products` table.

```bash
php artisan migrate
```

### 5\. Start the Queue Worker

The synchronization process runs in the background. You need to start a queue worker to process the jobs. Open a new terminal tab and run:

```bash
php artisan queue:work
```

Keep this terminal window open while you are working on the project.

### 6\. Run the Application

Start the Laravel development server.

```bash
php artisan serve
```

You can now visit `http://127.0.0.1:8000` in your browser.

## Usage

1.  **Visit the Dashboard:** Navigate to the project's homepage.
2.  **Sync Products:** Click the **"Sync Products"** button on the page. This will dispatch a job to the queue worker.
3.  **View Products:** After a short delay, the page will automatically refresh and display the products fetched from your Shopify store.

## Project Structure Explained

The project follows a solid architectural pattern to ensure efficiency and reliability.

  - **`routes/api.php`**: Defines the API endpoints for the frontend, including `/api/products` (to get products) and `/api/sync-products` (to start the sync job).
  - **`app/Jobs/SyncProductsJob.php`**: The heart of the synchronization logic. This class performs the API call to Shopify and saves the data to the database. It is designed to run asynchronously in the background.
  - **`app/Http/Controllers/ShopifyController.php`**: Handles the API requests from the frontend, dispatching the job and returning the product data.
  - **`app/Services/Shopify/ShopifyClient.php`**: A dedicated service class for interacting with the Shopify API, encapsulating the HTTP requests and API logic.
  - **`resources/js/components/ShopifySync.vue`**: The Vue.js component that provides the user interface. It communicates with the backend via Axios to trigger syncs and fetch data.

This separation of concerns ensures that the application remains responsive and scalable, with long-running tasks being offloaded to the queue system.

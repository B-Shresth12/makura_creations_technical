# Project Name: URL Shortener

A simple and efficient URL shortening service built with Laravel, MySQL, and Redis. This service allows users to shorten, track, and manage URLs easily. It supports features like database-based URL storage, expiration, and analytics.

## Features

-   **Shorten URLs**: Generate short URLs that redirect to long URLs.
-   **URL Expiration**: Set expiration dates for shortened URLs.
-   **Analytics**: Track the number of times a shortened URL was accessed.
-   **Admin Panel**: Admins can manage all URLs, view analytics, and delete URLs.
-   **Search and Lookup**: Lookup by short URL or search by long URL.

## Prerequisites

Before you begin, ensure you have the following installed:

-   PHP 8.3
-   Laravel 11
-   MySQL
-   Redis

## Installation

Follow these steps to get the application running on your local machine:

1. **Clone the repository**:

```bash
git clone https://github.com/yourusername/url-shortener.git
```
2. **Navigate into the project directory**:

```bash
cd url-shortener
```

3. **Install dependencies**:

```bash
composer install
```

4. **Run the setup command:** If this is the first time setting up the project, run the setup command:

```bash
php artisan app:setup
```

## Usage

5. **To start the application, use the following command:**

```bash
php artisan serve
```

This will start the application at **http://127.0.0.1:8000**.

6. **Queue and Scheduler**
   This project utilizes queues and scheduled tasks to manage URL expiration and other background tasks. To ensure they are working properly, you need to run the following commands:

-   Run the queue worker:

```bash
php artisan queue:work
```

-   Run the scheduler:

```bash
php artisan schedule:run
```

Make sure that you have the cron job or scheduler set up to run periodically in your production environment.

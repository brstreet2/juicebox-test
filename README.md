# Juicebox Backend Test

Welcome to the Juicebox Backend Test repository. This project showcases a backend system developed using Laravel, designed for scalable and efficient functionality. Below, you will find the information necessary to set up, use, and explore the project.

---

## API Reference

For detailed API documentation, refer to the Postman collection linked below:

[Juicebox API Reference](https://blue-flare-865064.postman.co/workspace/Team-Workspace~22f02c25-ec65-41d4-88d0-063c448472ec/collection/21527756-75ded66a-8828-40d6-ae28-48aea1008454?action=share&creator=21527756)

---

## Authors

If you have any questions or need assistance, feel free to reach out:

-   **Email**: [azkasecio0405@gmail.com](mailto:azkasecio0405@gmail.com)

---

## Installation

Follow these steps to run the project locally:

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <repository-directory>
```

### 2. Environment Setup

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the `.env` file with your local environment details, including database credentials and additional API keys (see the "Environment Variables" section below).

### 3. Install Dependencies

Ensure you are using Laravel 11 with PHP 8.4 and run:

```bash
composer install
```

If you encounter issues with dependencies, try:

```bash
rm composer.lock
composer install
```

### 4. Database Setup

Run migrations to set up the database schema:

```bash
php artisan migrate
```

### 5. Run the Application

Start the local server:

```bash
php artisan serve
```

---

## Environment Variables

In addition to the standard Laravel `.env` variables, include the following:

```env
OPENWEATHERMAP_BASE_URL=https://api.openweathermap.org/data/2.5/weather
OPENWEATHERMAP_API_KEY=dc52afc4842282f49b0a27b5cd579efa
```

Make sure to configure your `.env` file with these and other necessary details for a seamless experience.

---

## Features

-   **Laravel 11 and PHP 8.4 compatibility.**
-   **Clean and modular architecture** for easy maintenance and scalability.
-   **Integration with the OpenWeatherMap API** for weather data retrieval.
-   **Comprehensive API documentation** available on Postman.
-   **Queue Implementation**:
    -   A queued job for sending a welcome email when a new user registers.
    -   An artisan command `email:welcome` to dispatch the welcome email job manually for testing purposes.
-   **Weather API Integration**:
    -   Endpoint `/api/weather` retrieves current weather data for Perth, Australia, and caches the data for 15 minutes to minimize API calls.

---

## Setup Instructions

### 1. **Welcome Email Job**

-   Ensure the `QUEUE_CONNECTION` is configured in the `.env` file.
-   Run the following commands to start processing jobs:
    ```bash
    php artisan queue:work
    ```

### 2. **Artisan Command for Testing Email Jobs**

-   Use the `email:welcome` artisan command to dispatch the welcome email job manually:
    ```bash
    php artisan email:welcome user@example.com
    ```

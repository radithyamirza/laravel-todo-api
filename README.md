# Laravel Todo API

This is a simple API-only Laravel project for managing a Todo list.

## Features

- Create Todo items with title, optional assignee, due date, time tracked, status, and priority
- Get Excel Output based on query params
- Get To do chart based on query params

## Requirements

- PHP >= 8.1
- Composer
- SQLite

## Setup

1. Clone the repository:
    ```
    git clone https://github.com/radithyamirza/laravel-todo-api.git
    cd laravel-todo-api
    ```

2. Install dependencies:
    ```
    composer install
    ```

3. Copy `.env` and set up your environment:
    ```
    cp .env.example .env
    php artisan key:generate
    ```
    - Set your database credentials in `.env`.

4. Run migrations:
    ```
    php artisan migrate
    ```

5. Start the development server:
    ```
    php artisan serve
    ```

## Postman API Collections
https://drive.google.com/file/d/14YmwBjO7AX1jts7qtLJTOmPR5wxzY1kJ/view?usp=sharing

---
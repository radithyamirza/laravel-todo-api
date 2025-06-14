# Laravel Todo API

This is a simple API-only Laravel project for managing a Todo list.

## Features

- Create Todo items with title, optional assignee, due date, time tracked, status, and priority
- Validation to ensure due date is not in the past
- API documentation via this README

## Requirements

- PHP >= 8.1
- Composer
- MySQL or SQLite

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

## API Endpoints

### Create Todo

`POST /api/todos`

#### Request Body

```json
{
  "title": "string, required",
  "assignee": "string, optional",
  "due_date": "YYYY-MM-DD, required, not in past",
  "time_tracked": "numeric, optional, defaults to 0",
  "status": "pending|open|in_progress|completed, optional, defaults to pending",
  "priority": "low|medium|high, required"
}
```

#### Validation

- `title` and `due_date` are required.
- `due_date` cannot be in the past.
- `status` defaults to `pending` if not provided.

#### Example Response

```json
{
  "id": 1,
  "title": "Do something",
  "assignee": "radithyamirza",
  "due_date": "2025-07-01",
  "time_tracked": 0,
  "status": "pending",
  "priority": "medium"
}
```

---

## Reference

![image1](image1)
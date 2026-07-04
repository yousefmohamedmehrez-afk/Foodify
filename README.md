# 🍔 Foodify Backend API

A RESTful API for the Foodify food delivery application built with Laravel.

## 🚀 Features

- 🔐 User Authentication (Register, Login, Logout)
- 📧 OTP Verification
- 👤 User Profile Management
- 🍽️ Meals Management
- 📂 Categories
- ❤️ Favorites
- 🛒 Shopping Cart
- 📍 Addresses
- 📦 Orders
- 🚚 Order Tracking
- 💳 Payment Methods
- 💰 Payments
- 🔔 Notifications
- 🔍 Search Meals
- 🎯 Filter Meals by Category
- ⭐ Reviews & Ratings

---

## 🛠️ Tech Stack

- Laravel
- PHP
- MySQL
- Laravel Sanctum
- REST API

---

## 📂 Project Structure

```
app/
├── Http/
├── Models/
├── Providers/

database/
├── migrations/
├── seeders/

routes/
└── api.php
```

---

## ⚙️ Installation

Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/Foodify.git
```

Go to the project

```bash
cd Foodify
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

Configure your database inside the `.env` file.

Run migrations

```bash
php artisan migrate
```

(Optional)

```bash
php artisan db:seed
```

Start the server

```bash
php artisan serve
```

The API will be available at

```
http://127.0.0.1:8000/api
```

---

## 📌 Main API Endpoints

| Method | Endpoint | Description |
|---------|----------|-------------|
| POST | /register | Register |
| POST | /login | Login |
| GET | /profile | Get Profile |
| PUT | /profile | Update Profile |
| GET | /categories | Get Categories |
| GET | /meals | Get Meals |
| POST | /cart | Add To Cart |
| GET | /cart | Get Cart |
| POST | /orders | Create Order |
| GET | /orders | Get Orders |
| GET | /orders/{id}/track | Track Order |
| GET | /notifications | Get Notifications |
| POST | /reviews | Add Review |

---

## 🔒 Authentication

The API uses **Laravel Sanctum**.

Include your token in every protected request:

```
Authorization: Bearer YOUR_TOKEN
```

---

## 👨‍💻 Developed By

**Yousef Mehrez**

Backend Developer

Laravel | REST API | MySQL

---

## 📄 License

This project is for educational purposes.

# 📅 Scheduler

A modern scheduling and productivity web application built with **Laravel 12** and **Alpine.js**.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?logo=tailwind-css)

---

## ✨ Features

- 📅 **Event Management** — Create, edit, delete, and track events
- 🍅 **Pomodoro Timer** — Built-in focus sessions with attendance tracking
- 👥 **Session Attendance** — Track participation in sessions
- 🔐 **Authentication** — Secure login and registration via Laravel Sanctum
- 🌗 **Responsive UI** — Tailwind CSS powered layout for all screen sizes

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 12 |
| **Language** | PHP 8.2+ |
| **Authentication** | Laravel Sanctum |
| **Database** | SQLite (development) / MySQL or PostgreSQL (production) |
| **Frontend JS** | Alpine.js 3 |
| **CSS Framework** | Tailwind CSS 4 |
| **Asset Bundler** | Vite + laravel-vite-plugin |
| **Testing** | PHPUnit 11 |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (for local dev, comes pre-installed on most systems)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/abdulhakeembsk4k59/scheduler.git
cd scheduler

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Build frontend assets
npm run build
```

### Run Locally

```bash
# Start all services at once (server + queue + logs + vite)
composer run dev
```

Or individually:
```bash
php artisan serve     # Laravel dev server → http://localhost:8000
npm run dev           # Vite dev server (hot reload)
```

---

## 🧪 Running Tests

```bash
# Run all tests
composer run test

# Or directly with PHPUnit
php artisan test

# Run a specific test file
php artisan test tests/Feature/ExampleTest.php
```

---

## 📁 Project Structure

```
scheduler-laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/           # API Controllers
│   │           ├── EventController.php
│   │           ├── PomodoroSessionController.php
│   │           └── SessionAttendanceController.php
│   └── Models/
│       ├── Event.php
│       ├── PomodoroSession.php
│       ├── SessionAttendance.php
│       └── User.php
├── database/
│   ├── migrations/            # Database schema
│   ├── factories/             # Model factories
│   └── seeders/               # Database seeders
├── resources/
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
├── tests/
│   ├── Feature/               # Feature tests
│   └── Unit/                  # Unit tests
├── vite.config.js             # Vite configuration
└── composer.json
```

---

## ☁️ Deployment

### Deploy to shared hosting / VPS

```bash
# On the server
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Deploy to [Railway](https://railway.app) or [Render](https://render.com)

1. Push this repo to GitHub
2. Connect to Railway/Render
3. Set environment variables (see below)
4. Set start command: `php artisan serve --host=0.0.0.0 --port=$PORT`

---

## 🔑 Environment Variables

Copy `.env.example` to `.env` and update:

```env
APP_NAME=Scheduler
APP_ENV=production
APP_KEY=          # Run: php artisan key:generate
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scheduler
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feat/my-feature`
3. Run tests before submitting: `composer run test`
4. Submit a pull request

---

## 📄 License

MIT License — see [LICENSE](LICENSE) for details.

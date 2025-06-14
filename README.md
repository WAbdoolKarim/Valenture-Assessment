# 📚 Learner Progress Tracker

A Laravel + Tailwind CSS web app for viewing and filtering learner course progress.

---

### Install PHP Dependencies
```bash
composer install
```

### Install Frontend Dependencies (Tailwind, Vite)
```bash
npm install
```

### Configure `.env` and Set Up Environment
```bash
cp .env.example .env
```

Then edit `.env` and update the following for SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE="/absolute/path/to/project/database/database.sqlite"
```

> Replace the path above with the absolute path on your system. Use forward slashes `/`, even on Linux.

### Set Permissions (Linux Only)
```bash
chmod -R 775 storage bootstrap/cache
```

### Generate App Key
```bash
php artisan key:generate
```

### Run Migrations and Seeders
```bash
php artisan migrate --seed
```

### Start Vite (for Tailwind styles)
```bash
npm run dev
```

Leave this running in a separate terminal tab.

### Start Laravel Dev Server
```bash
php artisan serve
```

Then visit: [http://localhost:8000/learner-progress](http://localhost:8000/learner-progress)

---

## System Requirements

- PHP 8.1+
- Composer
- Node.js + npm
- SQLite
- Laravel CLI (`composer global require laravel/installer` optional)

### Linux Installation Shortcut (Debian/Ubuntu)
```bash
sudo apt update && sudo apt install php php-cli php-mbstring php-xml php-bcmath php-curl php-zip php-sqlite3 unzip curl git
```

---

## Features

- Filter learners by course
- Toggle display of all enrolled courses per learner
- Sort by name or progress
- Expand/collapse all rows

---

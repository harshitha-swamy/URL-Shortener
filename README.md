## URL Shortener
A **role-based**, multi-tenant URL shortener with very strict authorization rules, built with **Laravel**.

Focus: strong access control • company data isolation • private short links (no public resolution)

<p align="center">
  <img src="https://dummyimage.com/1200x400/1e293b/ffffff.png&text=Secure+URL+Shortener+—+Laravel" alt="Project Banner" width="90%">
</p>

---

## 📌 Project Overview

- **Project Name**    : Secure URL Shortener System  
- **Author**          : Harshita Swamy  
- **Role**            : Full Stack Developer  
- **Framework**       : Laravel 10 / 11 / 12  
- **PHP Version**     : ≥ 8.1  
- **Database**        : SQLite (recommended for local dev) / MySQL  
- **Authentication**  : Laravel built-in Auth  
- **Testing**         : PHPUnit (authorization rules coverage)  
- **License**         : MIT  

---

## 🧠 Core Business & Security Requirements Implemented

- Multi-company structure with isolated visibility  
- Five user roles with deliberately restricted permissions  
- **No role** is allowed to create short URLs (SuperAdmin, Admin, Member blocked)  
- Short URLs are **private** — no public/guest redirection  
- Strict prevention of cross-company and cross-user URL leaking  

---

## 👥 Roles & Permissions Matrix

| Role       | Can create short URL? | URL list visibility rule                                      | Invitation restrictions                              |
|------------|------------------------|----------------------------------------------------------------|------------------------------------------------------|
| SuperAdmin | ❌ No                  | ❌ Cannot see full list across all companies                   | Cannot invite Admin to a new company                 |
| Admin      | ❌ No                  | ❌ Only URLs **not** created in own company                    | Cannot invite Admin or Member in same company        |
| Member     | ❌ No                  | ❌ Only URLs **not** created by themselves                     | —                                                    |
| Sales      | ❌ No                  | —                                                              | —                                                    |
| Manager    | ❌ No                  | —                                                              | —                                                    |

---

## 🔐 Key Security & Authorization Features

### URL Creation
Disabled for **all** roles listed in the assignment

### URL Listing Visibility
- SuperAdmin → blocked from global/company-crossing view  
- Admin     → sees **only URLs from other companies**  
- Member    → sees **only URLs created by others**

### Short URL Resolution
- **Not publicly accessible**  
- Requires proper authentication + authorization  
- Unauthorized requests → 403 Forbidden or 404 Not Found

### Invitation Rules
- SuperAdmin **cannot** invite Admin into a new company  
- Admin **cannot** invite another Admin or Member into own company

---

## 🛠️ Local Setup Instructions

### Prerequisites

- PHP ≥ 8.1  
- Composer  
- Node.js + npm (for frontend assets)  
- SQLite (easiest for local testing) or MySQL

### Step-by-step installation


# 1. Clone the repository
git clone https://github.com/harshitha-swamy/URL-Shortener.git
cd URL-Shortener

# 2. Install dependencies
composer install
npm install && npm run dev     # or npm run build for production

# 3. Set up environment file
cp .env.example .env
php artisan key:generate

# 4. Run migrations + seed SuperAdmin (raw SQL)
php artisan migrate --seed

# 5. Start local development server
php artisan serve

→ Open browser: http://127.0.0.1:8000

**Default SuperAdmin credentials** (change immediately after first login):  
**Email**    : `superadmin@example.com`  
**Password** : `password`

---

## 📸 Screenshots

<p align="center">
  <img src="https://placebear.com/800/450" alt="Login Page" width="48%">
  <img src="https://placebear.com/800/450" alt="Dashboard – Admin View" width="48%">
</p>

<p align="center">
  <img src="https://placebear.com/800/450" alt="URL List – Member Perspective" width="48%">
  <img src="https://placebear.com/800/450" alt="Access Denied (403)" width="48%">
</p>

> Placeholder images — real application screenshots will be added soon.

---

## 🛡️ Implementation Highlights

- Authorization enforced via **Laravel Policies + Gates + Middleware**  
- SuperAdmin account created using **raw SQL** inside seeder  
- No public route exists for short URL redirection  
- Visibility filtering applied at **Eloquent query level**  
- Tests verify that forbidden roles cannot create URLs or view unauthorized records  

---

## 👩‍💻 Author

**Harshita Swamy**  
Full Stack Developer  
Laravel • PHP • MySQL • JavaScript • Bootstrap (optional)

GitHub: [@harshitha-swamy](https://github.com/harshitha-swamy)

---

Built with ❤️ using Laravel  
February 2026

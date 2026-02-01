## URL Shortener
A **role-based**, multi-tenant URL shortener with very strict authorization rules, built with **Laravel**.

Focus: strong access control • company data isolation 

<p align="center">
  <img src="https://dummyimage.com/1200x400/1e293b/ffffff.png&text=Secure+URL+Shortener+—+Laravel" alt="Project Banner" width="90%">
</p>

---

## 📌 Project Overview

- **Project Name**    : URL Shortener System  
- **Author**          : Harshita Swamy  
- **Role**            : Full Stack Developer  
- **Framework**       : Laravel 10 / 11 / 12  
- **PHP Version**     : ≥ 8.1  
- **Database**        : MySQL  
- **Authentication**  : Laravel Sanctum (token-based) 
- **Testing**         : PHPUnit (authorization rules coverage)  
 

---

## 🧠 Core Business & Security Requirements Implemented

- Multi-company structure with isolated visibility  
- Three user roles with deliberately restricted permissions  
- **Admin, Member** is allowed to create short URLs (SuperAdmin blocked)    

---

## 👥 Roles & Permissions Matrix

| Role        | Can create short URL? | URL visibility                                   | Invitation permissions                          |
|-------------|----------------------|--------------------------------------------------|------------------------------------------------|
| SuperAdmin  | ❌ No                | ❌ Cannot view any short URLs                    | ✅ Can invite Admin to create NEW company      |
| Admin       | ✅ Yes               | ✅ URLs created within own company               | ✅ Can invite Member to same company           |
| Member      | ✅ Yes               | ✅ Only URLs created by themselves               | ❌ Cannot invite anyone                       |

---

## 🔐 Key Security & Authorization Features

### URL Creation
- Allowed only for **Admin** and **Member**
- Explicitly forbidden for **SuperAdmin**

### URL Listing Visibility
- SuperAdmin → blocked from viewing any URLs
- Admin → views URLs belonging to their company
- Member → views only URLs they created

### Invitation Rules
- SuperAdmin → can create a new company + invite Admin
- Admin → can invite Members into own company
- Member → no invitation permissions

---

## 🛠️ Local Setup Instructions

### Prerequisites

- PHP ≥ 8.1  
- Composer  
- MySQL

### Step-by-step installation


# 1. Clone the repository
git clone https://github.com/harshitha-swamy/URL-Shortener.git
cd URL-Shortener

# 2. Install dependencies
composer install

# 3. Set up environment file
cp .env.example .env

# 4. Run migrations + seed SuperAdmin (raw SQL)
php artisan migrate 
php artisan db:seed

# 5. Start local development server
php artisan serve

→ Open browser: http://127.0.0.1:8000

**Default SuperAdmin credentials** (change immediately after first login):  
**Email**    : `superadmin@example.com`  
**Password** : `password`

---

## 🧪 Testing Methodology

- Automated Feature Testing using PHPUnit
- Focus on authorization and access-control validation
- Policies and middleware tested through real HTTP requests
- Cross-role and cross-company security rules explicitly covered

   PASS  Tests\Feature\ShortUrlTest
  ✓ admin can create short url                                                                        0.52s  
  ✓ member can create short url                                                                       0.02s  
  ✓ superadmin cannot create short url                                                                0.02s  
  ✓ admin can only see company urls                                                                   0.02s  
  ✓ member can only see own urls                                                                      0.02s  
  ✓ superadmin cannot see any urls                                                                    0.02s  
  ✓ short url redirects to original url                                                               0.03s  



## 📸 Screenshots

<p align="center">
  <img src="https://github.com/harshitha-swamy/URL-Shortener/blob/main/public/images/login_page.png" alt="Login Page" width="48%">
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

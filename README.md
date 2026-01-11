# 🌱 AgriSmart - Agriculture Marketplace

<div align="center">

![AgriSmart](https://img.shields.io/badge/AgriSmart-v1.0.0-success?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A Modern, Secure PHP 8 MVC Agriculture Marketplace**

Buy and Sell Agricultural Products Online

[Features](#features) • [Installation](#installation) • [Usage](#usage) • [Security](#security) • [Credits](#credits)

</div>


---

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Security Features](#security-features)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [API Routes](#api-routes)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 About

**AgriSmart** is a comprehensive, production-ready agriculture marketplace built with pure PHP 8 and MySQL. It features a modern, premium UI with smooth animations and implements enterprise-level security practices. The platform enables farmers and agricultural sellers to showcase and sell their products while providing customers with an intuitive shopping experience.

### 🌟 Key Highlights

- ✅ **Pure PHP MVC Architecture** - No frameworks, complete control
- ✅ **Enterprise Security** - CSRF, XSS, SQL Injection protection
- ✅ **Premium Modern UI** - Responsive design with smooth animations
- ✅ **Multi-Role System** - Admin, Buyer (Seller), Customer
- ✅ **Complete E-commerce** - Cart, Checkout, Order Management
- ✅ **Smart Recommendations** - Category-based product suggestions
- ✅ **Ready for Production** - Optimized and tested

---

## ✨ Features

### For Customers
- 🛒 Browse and search agricultural products
- 🔍 Advanced filtering (category, price range, sorting)
- 🛍️ Shopping cart with quantity management
- 💳 Secure checkout process
- 📦 Order tracking and history
- ⭐ Product reviews and ratings
- 👤 Profile management

### For Sellers (Buyers)
- 📝 Product listing management (CRUD)
- 📊 Sales dashboard with statistics
- 📈 Revenue tracking
- 📦 Order management
- 🖼️ Product image upload
- 📱 Inventory management

### For Administrators
- 👥 User management
- 📦 Product moderation
- 📋 Order management
- 🏷️ Category management
- 📊 Platform analytics
- ⚙️ System settings

### Technical Features
- 🔐 Password hashing with bcrypt
- 🛡️ CSRF token protection
- 🧹 Input sanitization and validation
- 🔍 PDO prepared statements
- 📱 Mobile-responsive design
- ⚡ Optimized performance
- 🎨 Smooth animations
- 📝 Activity logging

---

## 🛠️ Tech Stack

### Backend
- **PHP 8.0+** - Server-side language
- **MySQL 8.0+** - Database
- **PDO** - Database abstraction layer

### Frontend
- **HTML5** - Markup
- **CSS3** - Styling with CSS Variables
- **JavaScript ES6+** - Interactivity
- **Bootstrap 5.3** - UI Framework
- **Bootstrap Icons** - Icon library
- **Google Fonts (Poppins)** - Typography

### Architecture
- **MVC Pattern** - Model-View-Controller
- **Singleton Pattern** - Database connection
- **Front Controller** - Single entry point
- **Repository Pattern** - Data access

---

## 🚀 Installation

### Prerequisites

Before you begin, ensure you have:
- ✅ **XAMPP** (or similar) with PHP 8.0+ and MySQL 8.0+
- ✅ A web browser (Chrome, Firefox, Edge, etc.)
- ✅ Text editor (VS Code recommended)

### Step 1: Clone/Download Project

```bash
# Option 1: Clone with Git
cd C:\xampp\htdocs
git clone https://github.com/yourusername/AgriSmart.git projet

# Option 2: Download ZIP
# Extract the ZIP file to C:\xampp\htdocs\projet
```

### Step 2: Database Setup

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL** services

2. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Click "New" to create a database
   - Import the SQL file:
     - Click "Import" tab
     - Choose file: `C:\xampp\htdocs\projet\sql\database.sql`
     - Click "Go"

### Step 3: Configuration

Edit `app/config/database.php` if needed (default settings):

```php
private const HOST = 'localhost';
private const DB_NAME = 'AgriSmart';
private const USERNAME = 'root';
private const PASSWORD = '';
```

### Step 4: File Permissions

Ensure upload directories exist and are writable:

```bash
# Create upload directories
mkdir public/assets/uploads/products
mkdir public/assets/uploads/profiles
```

### Step 5: Access Application

Open your browser and navigate to:

```
http://localhost/projet/public
```

---

## 🔑 Default Credentials

### Admin Account
- **Email:** `admin@AgriSmart.com`
- **Password:** `admin123`

### Seller Account
- **Email:** `seller@AgriSmart.com`
- **Password:** `buyer123`

### Customer Account
- **Email:** `customer@AgriSmart.com`
- **Password:** `user123`

> ⚠️ **Important:** Change these passwords after first login!

---

## ⚙️ Configuration

### Base URL

If your project is in a different folder, update `BASE_URL` in `public/index.php`:

```php
define('BASE_URL', '/your-folder-name/public');
```

Also update in `public/.htaccess`:

```apache
RewriteBase /your-folder-name/public/
```

### Upload Settings

Maximum file upload size in `public/.htaccess`:

```apache
php_value upload_max_filesize 10M
php_value post_max_size 12M
```

### Session Settings

Adjust session timeout in `app/middlewares/AuthMiddleware.php`:

```php
public static function checkTimeout($timeout = 1800) // 30 minutes
```

---

## 📖 Usage

### Customer Flow

1. **Browse Products**
   - Visit homepage
   - Click "Browse Products" or navigate to Products page
   - Use filters to find specific items

2. **Add to Cart**
   - Select a product
   - Choose quantity
   - Click "Add to Cart"

3. **Checkout**
   - Review cart
   - Provide shipping information
   - Place order

4. **Track Order**
   - Go to Dashboard → My Orders
   - View order status and details

### Seller Flow

1. **Register as Seller**
   - Click "Register"
   - Choose "Seller" as account type
   - Complete registration

2. **Add Products**
   - Login and go to Dashboard
   - Click "Add Product" or "Sell Product"
   - Fill in product details
   - Upload image
   - Submit

3. **Manage Products**
   - View all products in dashboard
   - Edit or delete products
   - Update stock quantities

4. **Manage Orders**
   - View incoming orders
   - Update order status
   - Contact customers

### Admin Flow

1. **System Overview**
   - View platform statistics
   - Monitor user activity
   - Check revenue metrics

2. **User Management**
   - View all users
   - Activate/deactivate accounts
   - Manage roles

3. **Product Moderation**
   - Review new products
   - Feature products
   - Remove inappropriate content

---

## 🔒 Security Features

### 1. Authentication & Authorization
- ✅ Password hashing with bcrypt (cost 12)
- ✅ Session management with regeneration
- ✅ Session timeout (30 minutes default)
- ✅ Role-based access control
- ✅ Middleware protection

### 2. CSRF Protection
- ✅ CSRF tokens for all forms
- ✅ Token validation on POST requests
- ✅ Token regeneration every hour

### 3. XSS Prevention
- ✅ Input sanitization using `htmlspecialchars()`
- ✅ Output escaping in views
- ✅ Security helper methods

### 4. SQL Injection Prevention
- ✅ PDO prepared statements
- ✅ Parameterized queries
- ✅ No raw SQL from user input

### 5. File Upload Security
- ✅ File type validation
- ✅ File size limits
- ✅ MIME type verification
- ✅ Filename sanitization

### 6. Additional Security
- ✅ HTTP security headers
- ✅ Input validation
- ✅ Activity logging
- ✅ Error handling
- ✅ IP address logging

---

## 📁 Project Structure

```
projet/
├── app/
│   ├── config/
│   │   └── database.php          # Database connection
│   ├── controllers/
│   │   ├── AuthController.php    # Authentication
│   │   ├── HomeController.php    # Home page
│   │   ├── ProductController.php # Product management
│   │   ├── DashboardController.php # User dashboards
│   │   ├── AdminController.php   # Admin panel
│   │   └── CartController.php    # Shopping cart
│   ├── models/
│   │   ├── User.php             # User model
│   │   ├── Product.php          # Product model
│   │   └── Order.php            # Order model
│   ├── middlewares/
│   │   ├── AuthMiddleware.php   # Authentication check
│   │   └── RoleMiddleware.php   # Authorization check
│   ├── helpers/
│   │   ├── Security.php         # Security utilities
│   │   └── Validator.php        # Input validation
│   └── views/
│       ├── layouts/
│       │   ├── header.php       # Page header
│       │   └── footer.php       # Page footer
│       ├── auth/                # Authentication views
│       ├── dashboard/           # Dashboard views
│       ├── products/            # Product views
│       ├── errors/              # Error pages
│       └── home.php             # Homepage
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css        # Premium CSS
│   │   ├── js/
│   │   │   └── app.js           # Interactive JavaScript
│   │   └── uploads/             # User uploads
│   ├── index.php                # Front controller
│   └── .htaccess                # URL rewriting
├── sql/
│   └── database.sql             # Database schema
└── README.md                    # This file
```

---

## 🗄️ Database Schema

### Core Tables
- **users** - User accounts (admin, buyer, user)
- **products** - Product listings
- **categories** - Product categories
- **orders** - Customer orders
- **order_items** - Order line items

### Supporting Tables
- **password_resets** - Password reset tokens
- **cart** - Shopping cart items
- **wishlist** - User wishlist
- **reviews** - Product reviews
- **activity_logs** - User activity tracking

---

## 🛣️ API Routes

### Public Routes
```
GET  /                    - Homepage
GET  /products            - Product listing
GET  /products/view       - Product details
GET  /auth/login          - Login page
GET  /auth/register       - Registration page
POST /auth/login          - Process login
POST /auth/register       - Process registration
```

### Protected Routes (Authenticated)
```
GET  /dashboard           - User dashboard
GET  /dashboard/profile   - User profile
GET  /dashboard/orders    - User orders
GET  /cart                - Shopping cart
POST /cart/add            - Add to cart
POST /cart/checkout       - Process checkout
```

### Seller Routes (Buyer Role)
```
GET  /products/create     - Add product form
POST /products/create     - Save product
GET  /products/edit       - Edit product form
POST /products/edit       - Update product
POST /products/delete     - Delete product
```

### Admin Routes
```
GET  /admin               - Admin dashboard
GET  /admin/users         - User management
GET  /admin/products      - Product management
GET  /admin/orders        - Order management
GET  /admin/categories    - Category management
```

---

## 📸 Screenshots

### Homepage
Modern landing page with hero section, featured products, and categories.

### Product Listing
Advanced filtering with category, price range, and sorting options.

### Product Details
Detailed product view with seller information and recommendations.

### Dashboard
Role-based dashboards with statistics and recent activity.

### Shopping Cart
Clean cart interface with quantity management and checkout.

---

## 🎨 Design Features

### Color Palette
- **Primary:** #2E7D32 (Agriculture Green)
- **Secondary:** #81C784
- **Accent:** #FFD54F
- **Background:** #F8F9FA

### Typography
- **Font Family:** Poppins (Google Fonts)
- **Weights:** 300, 400, 500, 600, 700

### UI Elements
- Glassmorphism cards
- Smooth hover animations
- Gradient backgrounds
- Animated counters
- Loading spinners
- Floating labels

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is licensed under the MIT License.

---

## 👨‍💻 Developer

**AgriSmart Development Team**

- Email: info@AgriSmart.com
- Website: https://AgriSmart.com
- GitHub: https://github.com/AgriSmart

---

## 🙏 Acknowledgments

- Bootstrap Team for the excellent UI framework
- PHP Community for comprehensive documentation
- All contributors and testers




<div align="center">

**Made with ❤️ for Agriculture Community**

⭐ Star this repo if you find it helpful!

</div>

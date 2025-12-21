-- AgroMarket Database Schema
-- PHP 8 MVC Agriculture Marketplace
-- Created: December 2025

DROP DATABASE IF EXISTS agromarket;
CREATE DATABASE agromarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agromarket;

-- ============================================
-- Users Table (Multi-role authentication)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'buyer', 'user') DEFAULT 'user',
    profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
    is_active TINYINT(1) DEFAULT 1,
    email_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Password Reset Tokens
-- ============================================
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Categories Table
-- ============================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    image VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Products Table
-- ============================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    unit VARCHAR(20) DEFAULT 'piece',
    image VARCHAR(255),
    images TEXT, -- JSON array for multiple images
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    views_count INT DEFAULT 0,
    -- Plant-specific fields
    min_temperature DECIMAL(5, 2) COMMENT 'Minimum temperature in Celsius',
    max_temperature DECIMAL(5, 2) COMMENT 'Maximum temperature in Celsius',
    min_humidity INT COMMENT 'Minimum humidity percentage',
    max_humidity INT COMMENT 'Maximum humidity percentage',
    origin VARCHAR(100) COMMENT 'Geographic origin (North, South, East, West, Central, etc.)',
    sunlight_requirement VARCHAR(50) COMMENT 'Full Sun, Partial Shade, Full Shade',
    watering_frequency VARCHAR(50) COMMENT 'Daily, Weekly, Bi-weekly, Monthly',
    soil_type VARCHAR(100) COMMENT 'Loamy, Sandy, Clay, etc.',
    growing_season VARCHAR(100) COMMENT 'Spring, Summer, Fall, Winter',
    harvest_time VARCHAR(100) COMMENT 'Days to harvest or season',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_seller (seller_id),
    INDEX idx_category (category_id),
    INDEX idx_slug (slug),
    INDEX idx_featured (is_featured),
    INDEX idx_active (is_active),
    INDEX idx_price (price),
    FULLTEXT idx_search (name, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Orders Table
-- ============================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('cash', 'card', 'mobile_money', 'bank_transfer') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    shipping_phone VARCHAR(20) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_user (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Order Items Table
-- ============================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id),
    INDEX idx_seller (seller_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Reviews Table
-- ============================================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_user (user_id),
    INDEX idx_rating (rating),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Cart Table
-- ============================================
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Wishlist Table
-- ============================================
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist_item (user_id, product_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Activity Logs Table
-- ============================================
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Insert Real Data from Production Database
-- ============================================

-- Real Users Data
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `profile_image`, `is_active`, `email_verified`, `created_at`, `updated_at`) VALUES
(4, 'razi', 'razi@gmail.com', '$2y$12$PyJ8/NAOYbxsFP0.0L.eteCKcsrqIurPUl50lUG6eimxQcUhvGGwW', 'ammari', '5693258', 'rue ibn sina', 'buyer', 'profile_69432048c89c0.png', 1, 1, '2025-12-15 23:14:41', '2025-12-17 21:58:59'),
(5, 'Houssine', 'ali@gmail.com', '$2y$12$tv4a9I2No5woHxh7VMNagu8OLTqmiibc6411WYLvvnurhz4ju6dIO', 'Ali Houssine', '45852632', 'monastir', 'buyer', 'default-avatar.png', 0, 0, '2025-12-17 21:06:12', '2025-12-17 21:58:52'),
(6, 'alii', 'housine@gmail.com', '$2y$12$43W1Zqhs1KddJ2UE2OvgeuGM.MJRFhy.j8qeOgc8S/aP8K7OCop0i', 'housine', '25698532', 'dar', 'buyer', 'profile_6943206b719da.png', 1, 1, '2025-12-17 21:07:30', '2025-12-17 21:58:45'),
(7, 'Ahmed', 'Ahmed@gmail.com', '$2y$12$kYAeQrL9sma0VhVMpwaKROI5wyC9QVTerb6R5XzZVKBYKi6mUeZBW', 'Ahmed', '58963265', 'fe doura', 'user', 'profile_694320f1f15fc.png', 1, 1, '2025-12-17 21:30:01', '2025-12-17 21:58:33'),
(8, 'admin', 'admin@admin.com', '$2y$12$1VUDr/oDwD7A76Mt4JfA2u3DAOhKD8be9mFiAfo7.PKleLABJYJ6.', 'admin', '98532694', 'admin', 'admin', 'profile_6943251666112.png', 1, 1, '2025-12-17 21:45:42', '2025-12-17 21:54:56');

-- Real Categories Data
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vegetables', 'vegetables', 'Fresh organic vegetables directly from farm', 'bi-basket', NULL, 1, '2025-12-15 22:56:49', '2025-12-15 22:56:49'),
(2, 'Fruits', 'fruits', 'Seasonal fresh fruits', 'bi-apple', NULL, 1, '2025-12-15 22:56:49', '2025-12-15 22:56:49'),
(3, 'Seeds', 'seeds', 'Quality seeds for plantation', 'bi-seed', NULL, 1, '2025-12-15 22:56:49', '2025-12-15 22:56:49'),
(4, 'Plants', 'plants', 'Decorative and agricultural plants', 'bi-tree', NULL, 1, '2025-12-15 22:56:49', '2025-12-15 22:56:49'),
(5, 'Tools', 'tools', 'Agricultural tools and equipment', 'bi-tools', NULL, 1, '2025-12-15 22:56:49', '2025-12-15 22:56:49'),
(6, 'Fertilizers', 'fertilizers', 'Organic and chemical fertilizers', 'bi-droplet', NULL, 1, '2025-12-15 22:56:49', '2025-12-15 22:56:49');

-- Real Products Data
INSERT INTO `products` (`id`, `seller_id`, `category_id`, `name`, `slug`, `description`, `price`, `stock_quantity`, `unit`, `image`, `images`, `is_featured`, `is_active`, `views_count`, `min_temperature`, `max_temperature`, `min_humidity`, `max_humidity`, `origin`, `sunlight_requirement`, `watering_frequency`, `soil_type`, `growing_season`, `harvest_time`, `created_at`, `updated_at`) VALUES
(24, 4, 2, 'Banana', 'banana', 'Fresh, naturally ripe bananas with a sweet taste and smooth texture. Perfect for snacking, smoothies, baking, or breakfast bowls. Packed with natural energy and essential nutrients.', 20.00, 70, 'kg', 'product_694306da87bb6.png', NULL, 0, 1, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 16:39:05', '2025-12-17 20:28:07'),
(29, 4, 2, 'Orange', 'orange', 'Fresh, juicy oranges with a naturally sweet and tangy flavor. Perfect for snacking, fresh juice, or adding a burst of freshness to your meals.', 12.00, 80, 'kg', 'product_69431296ebc87.png', NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 20:29:10', '2025-12-17 20:29:10'),
(30, 4, 1, 'Tomato', 'tomato', 'Fresh, ripe tomatoes with a rich flavor and firm texture. Perfect for salads, sauces, cooking, and everyday meals.', 9.00, 80, 'kg', 'product_694312c70fa59.png', NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 20:29:59', '2025-12-17 20:29:59'),
(31, 4, 5, 'hand garden tool set', 'hand-garden-tool-set', 'ractical hand garden tool set including a hand trowel and a hand fork. Ideal for planting, digging, loosening soil, and maintaining gardens or potted plants.', 12.00, 14, 'piece', 'product_69431320f0bc6.png', NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 20:31:28', '2025-12-17 20:31:29'),
(32, 4, 5, 'hand trowel', 'hand-trowel', 'Sturdy hand trowel ideal for digging, planting, and transplanting. Perfect for gardens, flower beds, and potted plants.', 5.00, 20, 'piece', 'product_6943135252bde.png', NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 20:32:18', '2025-12-17 20:32:18'),
(33, 4, 4, 'Peace Lily', 'peace-lily', 'The Peace Lily is a popular indoor plant known for its glossy green leaves and elegant white flowers. It is easy to care for, improves indoor air quality, and adds a calm, decorative touch to homes and offices', 14.00, 7, 'piece', 'product_69431469350d8.png', NULL, 0, 1, 1, 15.00, 30.00, 40, 80, NULL, 'partial-shade', '3-4-days', 'mixed', 'year-round', '120', '2025-12-17 20:36:57', '2025-12-17 20:36:57'),
(34, 4, 4, 'Monstera', 'monstera', 'Monstera is a tropical ornamental plant known for its large, glossy green leaves with natural splits and holes. It's very popular as an indoor plant thanks to its bold look, easy care, and fast growth.', 14.00, 13, 'piece', 'product_694314e663daa.png', NULL, 0, 1, 3, 15.00, 30.00, 40, 85, NULL, 'partial-shade', 'weekly', 'peat', 'year-round', '60', '2025-12-17 20:39:02', '2025-12-17 21:08:39'),
(35, 4, 4, 'Pothos', 'pothos', 'Pothos is a fast-growing, trailing indoor plant with heart-shaped green leaves often variegated with yellow or cream. It's extremely easy to care for, making it perfect for beginners, hanging baskets, or shelves.', 10.00, 29, 'piece', 'product_69431573e6d36.png', NULL, 0, 1, 2, 12.00, 32.00, 30, 80, NULL, 'partial-shade', 'bi-weekly', 'sandy', 'year-round', '28', '2025-12-17 20:41:23', '2025-12-17 21:09:31'),
(36, 4, 3, 'Chia Seeds', 'chia-seeds', 'Chia seeds are tiny, nutrient-rich seeds known for their high fiber, omega-3s, and antioxidants. They're easy to add to smoothies, yogurt, or baking and help support energy, digestion, and overall health.', 20.00, 70, 'bundle', 'product_694316d21df89.PNG', NULL, 1, 1, 4, 15.00, 30.00, 40, 70, NULL, 'full-sun', 'daily', 'silty', 'spring', '28', '2025-12-17 20:46:58', '2025-12-17 22:11:46'),
(37, 4, 3, 'Sesame Seeds', 'chia-seeds-1', 'Sesame seeds are small, oil-rich seeds used in cooking, baking, and oil extraction. They are highly nutritious, containing protein, healthy fats, and minerals.', 4.00, 75, 'bundle', 'product_6943177a0ea31.PNG', NULL, 1, 1, 2, 25.00, 35.00, 50, 70, NULL, 'full-sun', 'weekly', 'peat', 'summer', NULL, '2025-12-17 20:50:02', '2025-12-17 20:54:38'),
(38, 4, 3, 'Sunflower Seeds', 'sunflower-seeds', 'Sunflower seeds are nutritious seeds rich in healthy fats, protein, vitamins, and minerals. They're popular for snacking, baking, and oil extraction.', 10.00, 80, 'bundle', 'product_694317d5e9bc6.PNG', NULL, 1, 1, 2, 25.00, 35.00, 40, 70, NULL, 'full-sun', '3-4-days', 'loamy', 'summer', NULL, '2025-12-17 20:51:33', '2025-12-17 20:55:20'),
(39, 4, 6, 'Goldensea Fertilizer', 'sunflower-seeds-1', 'Goldensea Fertilizer is a premium, nutrient-rich fertilizer designed to boost plant growth, improve soil health, and enhance yield. Ideal for vegetables, fruits, and ornamental plants, it ensures stronger roots, greener leaves, and vibrant blooms.', 50.00, 85, 'bundle', 'product_694318171a29c.PNG', NULL, 1, 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 20:52:39', '2025-12-17 21:16:28'),
(40, 4, 6, 'Bifen L/P', 'bifen-l-p', 'Bifen L/P is a granular insecticide primarily used for lawn and perimeter pest control. It contains 0.2 % bifenthrin, a synthetic pyrethroid insecticide that provides long‑lasting control of a wide range of surface‑feeding insects.', 55.00, 98, 'bundle', 'product_69431851d780d.PNG', NULL, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 20:53:37', '2025-12-17 21:12:52');

-- Real Orders Data
INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `status`, `payment_method`, `payment_status`, `shipping_address`, `shipping_phone`, `notes`, `created_at`, `updated_at`) VALUES
(5, 4, 'ORD-2025-81162', 3.00, 'pending', 'cash', 'pending', 'rue ibn sina', '5693258', '', '2025-12-17 20:17:25', '2025-12-17 20:17:25'),
(6, 6, 'ORD-2025-83928', 110.00, 'pending', 'cash', 'pending', 'dar', '25698532', '', '2025-12-17 21:08:01', '2025-12-17 21:08:01'),
(7, 6, 'ORD-2025-77177', 28.00, 'delivered', 'cash', 'pending', 'dar', '25698532', '', '2025-12-17 21:08:39', '2025-12-17 21:18:20'),
(8, 6, 'ORD-2025-96496', 10.00, 'shipped', 'cash', 'pending', 'dar', '25698532', '', '2025-12-17 21:09:31', '2025-12-17 21:18:05');

-- Real Order Items Data
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `seller_id`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(9, 6, 40, 4, 2, 55.00, 110.00, '2025-12-17 21:08:01'),
(10, 7, 34, 4, 2, 14.00, 28.00, '2025-12-17 21:08:39'),
(11, 8, 35, 4, 1, 10.00, 10.00, '2025-12-17 21:09:31');

-- Real Activity Logs Data
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 4, 'user_registered', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-15 23:14:41'),
(2, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-15 23:16:30'),
(3, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 15:07:31'),
(4, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 16:20:32'),
(5, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 16:51:53'),
(6, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 19:26:52'),
(7, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 19:31:00'),
(8, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 19:56:34'),
(9, 5, 'user_registered', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:06:12'),
(10, 5, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:06:31'),
(11, 6, 'user_registered', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:07:30'),
(12, 6, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:07:44'),
(13, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:09:55'),
(14, 6, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:17:30'),
(15, 4, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:17:58'),
(16, 6, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 21:18:42'),
(17, 4, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:27:13'),
(18, 4, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:27:36'),
(19, 6, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 21:28:11'),
(20, 6, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 21:28:25'),
(21, 7, 'user_registered', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 21:30:01'),
(22, 7, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 21:30:10'),
(23, 7, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', '2025-12-17 21:30:25'),
(24, 8, 'user_registered', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:45:42'),
(25, 8, 'user_login', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:46:20'),
(26, 8, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:47:54'),
(27, 8, 'user_updated', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-12-17 21:48:06');

-- ============================================
-- Set AUTO_INCREMENT values to match production data
-- ============================================
ALTER TABLE `users` AUTO_INCREMENT = 9;
ALTER TABLE `categories` AUTO_INCREMENT = 7;
ALTER TABLE `products` AUTO_INCREMENT = 41;
ALTER TABLE `orders` AUTO_INCREMENT = 9;
ALTER TABLE `order_items` AUTO_INCREMENT = 12;
ALTER TABLE `activity_logs` AUTO_INCREMENT = 28;

-- ============================================
-- Database Info
-- ============================================
-- Database: agromarket
-- Exported from production on: December 17, 2025 at 11:25 PM
-- Real Users: 5 users (including admin)
-- Real Products: 17 products
-- Real Orders: 4 orders
-- ============================================

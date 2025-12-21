<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <title><?php echo $pageTitle ?? 'AgriSmart - Agriculture Marketplace'; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $metaDescription ?? 'Buy and sell agricultural products online. Fresh vegetables, fruits, seeds, plants and more.'; ?>">
    <meta name="keywords" content="agriculture, marketplace, plants, vegetables, fruits, organic">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/favicon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    
    <?php if (isset($customCSS)): ?>
        <?php echo $customCSS; ?>
    <?php endif; ?>
</head>
<body>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="bi bi-shop"></i>
                <span>AgriSmart</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (!AuthMiddleware::check() || !RoleMiddleware::isAdmin()): ?>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == BASE_URL . '/' || strpos($_SERVER['REQUEST_URI'], '/home') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/products') !== false && strpos($_SERVER['REQUEST_URI'], '/recommendations') === false ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>/products">
                            <i class="bi bi-grid"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/recommendations') !== false ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>/products/recommendations">
                            <i class="bi bi-stars"></i> AI Recommendations
                        </a>
                    </li>
                    <?php if (AuthMiddleware::check()): ?>
                        <?php if (RoleMiddleware::isBuyer()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/products/create">
                                <i class="bi bi-plus-circle"></i> Sell Product
                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <?php else: ?>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <span class="nav-link text-muted" style="font-weight: 600;">
                            <i class="bi bi-shield-lock-fill"></i> Admin Panel
                        </span>
                    </li>
                </ul>
                <?php endif; ?>
                
                <ul class="navbar-nav ms-auto">
                    <?php if (AuthMiddleware::check()): ?>
                        <?php $user = AuthMiddleware::user(); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?php if (!empty($user['profile_image']) && file_exists(PUBLIC_PATH . '/assets/uploads/profiles/' . $user['profile_image'])): ?>
                                    <img src="<?php echo BASE_URL; ?>/assets/uploads/profiles/<?php echo $user['profile_image']; ?>" 
                                         alt="Profile" 
                                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #2e7d32;">
                                <?php else: ?>
                                    <i class="bi bi-person-circle" style="font-size: 2rem; color: #2e7d32;"></i>
                                <?php endif; ?>
                                <span><?php echo Security::escape($user['full_name']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <?php if (!RoleMiddleware::isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/dashboard">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/cart">
                                    <i class="bi bi-cart3"></i> Cart
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php else: ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/dashboard">
                                    <i class="bi bi-shield-lock"></i> Admin Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/dashboard/profile">
                                    <i class="bi bi-person"></i> Profile
                                </a></li>
                                <?php if (!RoleMiddleware::isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/dashboard/orders">
                                    <i class="bi bi-bag"></i> My Orders
                                </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/auth/logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/login">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="<?php echo BASE_URL; ?>/auth/register">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                <?php echo Security::escape($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="container mt-3">
            <div class="alert alert-error">
                <i class="bi bi-exclamation-triangle"></i>
                <?php echo Security::escape($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['info'])): ?>
        <div class="container mt-3">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <?php echo Security::escape($_SESSION['info']); unset($_SESSION['info']); ?>
            </div>
        </div>
    <?php endif; ?>

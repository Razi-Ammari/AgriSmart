<?php
/**
 * Front Controller - Application Entry Point
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles all incoming requests and routes them to appropriate controllers
 */

// Start session with security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();

// Regenerate session ID periodically for security
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads');

// Base URL configuration
define('BASE_URL', '/agromarket/public');

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/middlewares/',
        APP_PATH . '/helpers/',
        APP_PATH . '/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize Security Helper
require_once APP_PATH . '/helpers/Security.php';
Security::init();

// Get route from query string
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

// Parse route
$parts = $route ? explode('/', $route) : [];
$controller = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';
$params = array_slice($parts, 2);

// Route mapping
$routes = [
    // Home routes
    '' => ['controller' => 'HomeController', 'action' => 'index'],
    'home' => ['controller' => 'HomeController', 'action' => 'index'],
    
    // Auth routes
    'auth/login' => ['controller' => 'AuthController', 'action' => 'login'],
    'auth/register' => ['controller' => 'AuthController', 'action' => 'register'],
    'auth/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    'auth/forgot-password' => ['controller' => 'AuthController', 'action' => 'forgotPassword'],
    'auth/reset-password' => ['controller' => 'AuthController', 'action' => 'resetPassword'],
    
    // Dashboard routes
    'dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
    'dashboard/profile' => ['controller' => 'DashboardController', 'action' => 'profile'],
    'dashboard/upgrade-to-seller' => ['controller' => 'DashboardController', 'action' => 'upgradeToSeller'],
    'dashboard/orders' => ['controller' => 'DashboardController', 'action' => 'orders'],
    'dashboard/products' => ['controller' => 'DashboardController', 'action' => 'products'],
    'dashboard/update-order-status' => ['controller' => 'DashboardController', 'action' => 'updateOrderStatus'],
    'dashboard/verify-user' => ['controller' => 'DashboardController', 'action' => 'verifyUser'],
    'dashboard/toggle-user-status' => ['controller' => 'DashboardController', 'action' => 'toggleUserStatus'],
    
    // Product routes
    'products' => ['controller' => 'ProductController', 'action' => 'index'],
    'products/view' => ['controller' => 'ProductController', 'action' => 'view'],
    'products/create' => ['controller' => 'ProductController', 'action' => 'create'],
    'products/edit' => ['controller' => 'ProductController', 'action' => 'edit'],
    'products/delete' => ['controller' => 'ProductController', 'action' => 'delete'],
    'products/category' => ['controller' => 'ProductController', 'action' => 'category'],
    'products/search' => ['controller' => 'ProductController', 'action' => 'search'],
    'products/recommendations' => ['controller' => 'ProductController', 'action' => 'recommendations'],
    
    // Cart routes
    'cart' => ['controller' => 'CartController', 'action' => 'index'],
    'cart/add' => ['controller' => 'CartController', 'action' => 'add'],
    'cart/update' => ['controller' => 'CartController', 'action' => 'update'],
    'cart/remove' => ['controller' => 'CartController', 'action' => 'remove'],
    'cart/checkout' => ['controller' => 'CartController', 'action' => 'checkout'],
    
    // Admin routes
    'admin' => ['controller' => 'AdminController', 'action' => 'index'],
    'admin/users' => ['controller' => 'AdminController', 'action' => 'users'],
    'admin/products' => ['controller' => 'AdminController', 'action' => 'products'],
    'admin/orders' => ['controller' => 'AdminController', 'action' => 'orders'],
    'admin/categories' => ['controller' => 'AdminController', 'action' => 'categories'],
    'admin/settings' => ['controller' => 'AdminController', 'action' => 'settings'],
];

// Resolve route
$routeKey = $route;
if (isset($routes[$routeKey])) {
    $controllerName = $routes[$routeKey]['controller'];
    $actionName = $routes[$routeKey]['action'];
} else {
    // Try dynamic routing
    $controllerName = ucfirst($controller) . 'Controller';
    $actionName = $action;
}

// Check if controller exists
if (!class_exists($controllerName)) {
    http_response_code(404);
    require_once APP_PATH . '/views/errors/404.php';
    exit;
}

// Instantiate controller
try {
    $controllerInstance = new $controllerName();
    
    // Check if action exists
    if (!method_exists($controllerInstance, $actionName)) {
        http_response_code(404);
        require_once APP_PATH . '/views/errors/404.php';
        exit;
    }
    
    // Execute action
    call_user_func_array([$controllerInstance, $actionName], $params);
    
} catch (Exception $e) {
    // Log error
    error_log("Application Error: " . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    require_once APP_PATH . '/views/errors/500.php';
    exit;
}

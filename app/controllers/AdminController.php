<?php
/**
 * Admin Controller
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles admin operations
 */

class AdminController {
    
    private $userModel;
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }
    
    /**
     * Show admin dashboard
     */
    public function index() {
        RoleMiddleware::admin();
        
        // Get statistics
        $stats = [
            'total_users' => $this->userModel->getAll(1, 1)['total'],
            'total_products' => $this->productModel->getAll(1, 1)['total'],
            'total_orders' => $this->orderModel->getAll(1, 1)['total'],
            'order_stats' => $this->orderModel->getStatistics()
        ];
        
        // Recent orders
        $recentOrders = $this->orderModel->getAll(1, 5)['data'];
        
        require_once APP_PATH . '/views/dashboard/admin.php';
    }
    
    /**
     * Manage users
     */
    public function users() {
        RoleMiddleware::admin();
        
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $role = $_GET['role'] ?? null;
        
        $filters = [];
        if ($search) $filters['search'] = $search;
        if ($role) $filters['role'] = $role;
        
        $users = $this->userModel->getAll($page, 20, $filters);
        
        require_once APP_PATH . '/views/admin/users.php';
    }
    
    /**
     * Manage products
     */
    public function products() {
        RoleMiddleware::admin();
        
        $page = $_GET['page'] ?? 1;
        $products = $this->productModel->getAll($page, 20);
        
        require_once APP_PATH . '/views/admin/products.php';
    }
    
    /**
     * Manage orders
     */
    public function orders() {
        RoleMiddleware::admin();
        
        $page = $_GET['page'] ?? 1;
        $status = $_GET['status'] ?? null;
        
        $filters = [];
        if ($status) $filters['status'] = $status;
        
        $orders = $this->orderModel->getAll($page, 20, $filters);
        
        require_once APP_PATH . '/views/admin/orders.php';
    }
    
    /**
     * Manage categories
     */
    public function categories() {
        RoleMiddleware::admin();
        
        $categories = $this->productModel->getCategories();
        
        require_once APP_PATH . '/views/admin/categories.php';
    }
    
    /**
     * Settings
     */
    public function settings() {
        RoleMiddleware::admin();
        
        require_once APP_PATH . '/views/admin/settings.php';
    }
}

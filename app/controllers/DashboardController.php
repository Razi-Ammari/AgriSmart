<?php
/**
 * Dashboard Controller
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles user dashboard for all roles
 */

class DashboardController {
    
    private $userModel;
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }
    
    /**
     * Show dashboard based on user role
     */
    public function index() {
        AuthMiddleware::require();
        
        $user = AuthMiddleware::user();
        $role = $user['role'];
        
        // Get statistics based on role
        $stats = [];
        
        if ($role === 'admin') {
            // Admin statistics
            $stats['total_users'] = $this->userModel->getAll(1, 1)['total'];
            $stats['total_products'] = $this->productModel->getAll(1, 1)['total'];
            $stats['total_orders'] = $this->orderModel->getAll(1, 1)['total'];
            $stats['order_stats'] = $this->orderModel->getStatistics();
            
            // Get all data for admin dashboard
            $allUsers = $this->userModel->getAll(1, 1000)['data']; // Get all users
            $allProducts = $this->productModel->getAll(1, 1000)['data']; // Get all products
            
            // Recent orders
            $recentOrders = $this->orderModel->getAll(1, 5)['data'];
            
            require_once APP_PATH . '/views/dashboard/admin.php';
            
        } elseif ($role === 'buyer') {
            // Buyer statistics
            $stats['total_products'] = $this->productModel->getAll(1, 1, ['seller_id' => $user['id']])['total'];
            $stats['order_stats'] = $this->orderModel->getStatistics(['seller_id' => $user['id']]);
            
            // My products
            $myProducts = $this->productModel->getAll(1, 10, ['seller_id' => $user['id']])['data'];
            
            // My orders (as seller)
            $myOrders = $this->orderModel->getSellerOrders($user['id'], 5);
            
            require_once APP_PATH . '/views/dashboard/buyer.php';
            
        } else {
            // User statistics
            $stats = $this->userModel->getStatistics($user['id']);
            
            // My orders
            $myOrders = $this->orderModel->getUserOrders($user['id'], 5);
            
            require_once APP_PATH . '/views/dashboard/user.php';
        }
    }
    
    /**
     * Show user profile
     */
    public function profile() {
        AuthMiddleware::require();
        
        $user = AuthMiddleware::user();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $data = [
                'full_name' => Security::clean($_POST['full_name'] ?? ''),
                'phone' => Security::clean($_POST['phone'] ?? ''),
                'address' => Security::clean($_POST['address'] ?? '')
            ];
            
            // Validation
            $validator = new Validator($data);
            $validator->required('full_name', 'Full name is required');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                header('Location: ' . BASE_URL . '/dashboard/profile');
                exit;
            }
            
            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $validation = Security::validateFileUpload($_FILES['profile_image'], ['jpg', 'jpeg', 'png'], 2097152);
                
                if ($validation['success']) {
                    $uploadDir = PUBLIC_PATH . '/assets/uploads/profiles/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('profile_') . '.' . $extension;
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                        // Delete old image
                        if ($user['profile_image'] !== 'default-avatar.png' && file_exists($uploadDir . $user['profile_image'])) {
                            unlink($uploadDir . $user['profile_image']);
                        }
                        $data['profile_image'] = $filename;
                    }
                }
            }
            
            // Handle password change
            if (!empty($_POST['new_password'])) {
                if (empty($_POST['current_password'])) {
                    $_SESSION['error'] = 'Current password is required to change password.';
                    header('Location: ' . BASE_URL . '/dashboard/profile');
                    exit;
                }
                
                if (!Security::verifyPassword($_POST['current_password'], $user['password'])) {
                    $_SESSION['error'] = 'Current password is incorrect.';
                    header('Location: ' . BASE_URL . '/dashboard/profile');
                    exit;
                }
                
                $validator = new Validator($_POST);
                $validator->min('new_password', 6, 'New password must be at least 6 characters')
                         ->matches('password_confirm', 'new_password', 'Passwords do not match');
                
                if ($validator->fails()) {
                    $_SESSION['error'] = $validator->getFirstError();
                    header('Location: ' . BASE_URL . '/dashboard/profile');
                    exit;
                }
                
                $data['password'] = $_POST['new_password'];
            }
            
            // Update user
            $success = $this->userModel->update($user['id'], $data);
            
            if ($success) {
                AuthMiddleware::refreshUser();
                $_SESSION['success'] = 'Profile updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update profile.';
            }
            
            header('Location: ' . BASE_URL . '/dashboard/profile');
            exit;
        }
        
        require_once APP_PATH . '/views/dashboard/profile.php';
    }
    
    /**
     * Upgrade user to seller
     */
    public function upgradeToSeller() {
        AuthMiddleware::require();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/profile');
            exit;
        }
        
        Security::verifyCsrf();
        
        $user = AuthMiddleware::user();
        
        // Only users can upgrade to buyers (sellers)
        if ($user['role'] !== 'user') {
            $_SESSION['error'] = 'Your account is already a seller account.';
            header('Location: ' . BASE_URL . '/dashboard/profile');
            exit;
        }
        
        // Update user role to buyer (which allows selling)
        $success = $this->userModel->update($user['id'], ['role' => 'buyer']);
        
        if ($success) {
            AuthMiddleware::refreshUser();
            $_SESSION['success'] = 'Congratulations! Your account has been upgraded to Seller. You can now list and sell products!';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to upgrade account. Please try again.';
            header('Location: ' . BASE_URL . '/dashboard/profile');
            exit;
        }
    }
    
    /**
     * Show user orders
     */
    public function orders() {
        AuthMiddleware::require();
        
        $user = AuthMiddleware::user();
        $page = $_GET['page'] ?? 1;
        
        if ($user['role'] === 'buyer') {
            $orders = $this->orderModel->getAll($page, 10, ['seller_id' => $user['id']]);
        } else {
            $orders = $this->orderModel->getAll($page, 10, ['user_id' => $user['id']]);
        }
        
        // Fetch order items for each order
        foreach ($orders['data'] as &$order) {
            $order['items'] = $this->orderModel->getOrderItems($order['id']);
        }
        
        require_once APP_PATH . '/views/dashboard/orders.php';
    }
    
    /**
     * Show all seller products
     */
    public function products() {
        AuthMiddleware::require();
        
        $user = AuthMiddleware::user();
        $page = $_GET['page'] ?? 1;
        
        // Get all products for this seller with pagination
        $result = $this->productModel->getAll($page, 20, ['seller_id' => $user['id']]);
        $products = $result['data'];
        $totalPages = $result['total_pages'];
        $currentPage = $result['current_page'];
        $total = $result['total'];
        
        require_once APP_PATH . '/views/dashboard/products.php';
    }
    
    /**
     * Update order status (seller only)
     */
    public function updateOrderStatus() {
        AuthMiddleware::require();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/orders');
            exit;
        }
        
        Security::verifyCsrf();
        
        $user = AuthMiddleware::user();
        
        // Only sellers can update order status
        if ($user['role'] !== 'buyer') {
            $_SESSION['error'] = 'Only sellers can update order status.';
            header('Location: ' . BASE_URL . '/dashboard/orders');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        
        // Validate inputs
        if (!$orderId || !$newStatus) {
            $_SESSION['error'] = 'Invalid request.';
            header('Location: ' . BASE_URL . '/dashboard/orders');
            exit;
        }
        
        // Validate status
        $allowedStatuses = ['processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            $_SESSION['error'] = 'Invalid order status.';
            header('Location: ' . BASE_URL . '/dashboard/orders');
            exit;
        }
        
        // Verify the order belongs to this seller
        $order = $this->orderModel->getById($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Order not found.';
            header('Location: ' . BASE_URL . '/dashboard/orders');
            exit;
        }
        
        // Check if any items in the order belong to this seller
        $orderItems = $this->orderModel->getOrderItems($orderId);
        $isSellerOrder = false;
        foreach ($orderItems as $item) {
            if ($item['seller_id'] == $user['id']) {
                $isSellerOrder = true;
                break;
            }
        }
        
        if (!$isSellerOrder) {
            $_SESSION['error'] = 'You are not authorized to update this order.';
            header('Location: ' . BASE_URL . '/dashboard/orders');
            exit;
        }
        
        // Update order status
        $success = $this->orderModel->updateStatus($orderId, $newStatus);
        
        if ($success) {
            $statusLabels = [
                'processing' => 'confirmed',
                'shipped' => 'marked as shipped',
                'delivered' => 'marked as delivered',
                'cancelled' => 'cancelled'
            ];
            $_SESSION['success'] = 'Order #' . str_pad($orderId, 6, '0', STR_PAD_LEFT) . ' has been ' . $statusLabels[$newStatus] . '!';
        } else {
            $_SESSION['error'] = 'Failed to update order status.';
        }
        
        header('Location: ' . BASE_URL . '/dashboard/orders');
        exit;
    }
    
    /**
     * Verify user account (Admin only)
     */
    public function verifyUser() {
        AuthMiddleware::require();
        RoleMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        Security::verifyCsrf();
        
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        
        if (!$userId) {
            $_SESSION['error'] = 'Invalid user ID.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $success = $this->userModel->verifyAccount($userId);
        
        if ($success) {
            $_SESSION['success'] = 'User account has been verified successfully!';
        } else {
            $_SESSION['error'] = 'Failed to verify user account.';
        }
        
        header('Location: ' . BASE_URL . '/dashboard');
    }
    
    /**
     * Toggle user active status (Admin only)
     */
    public function toggleUserStatus() {
        AuthMiddleware::require();
        RoleMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        Security::verifyCsrf();
        
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        
        if (!$userId || !in_array($status, [0, 1])) {
            $_SESSION['error'] = 'Invalid parameters.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $success = $this->userModel->toggleActiveStatus($userId, $status);
        
        if ($success) {
            $_SESSION['success'] = 'User status has been updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update user status.';
        }
        
        header('Location: ' . BASE_URL . '/dashboard');
    }
}

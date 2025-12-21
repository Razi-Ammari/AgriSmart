<?php
/**
 * Cart Controller  
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles shopping cart and checkout
 */

class CartController {
    
    private $db;
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }
    
    /**
     * Show cart
     */
    public function index() {
        AuthMiddleware::require();
        
        $userId = AuthMiddleware::userId();
        $cartItems = $this->getCartItems($userId);
        $total = $this->calculateTotal($cartItems);
        
        require_once APP_PATH . '/views/cart/index.php';
    }
    
    /**
     * Add to cart
     */
    public function add() {
        AuthMiddleware::require();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        Security::verifyCsrf();
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        $userId = AuthMiddleware::userId();
        $isAjax = !empty($_POST['ajax']);
        
        if (!$productId) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Invalid product.']);
                exit;
            }
            $_SESSION['error'] = 'Invalid product.';
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        // Check if product exists and has stock
        $product = $this->productModel->findById($productId);
        
        if (!$product || !$product['is_active']) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Product not available.']);
                exit;
            }
            $_SESSION['error'] = 'Product not available.';
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        // Prevent sellers from buying their own products
        if ($product['seller_id'] == $userId) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'You cannot purchase your own products.']);
                exit;
            }
            $_SESSION['error'] = 'You cannot purchase your own products.';
            header('Location: ' . BASE_URL . '/products/view?id=' . $productId);
            exit;
        }
        
        if ($product['stock_quantity'] < $quantity) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Insufficient stock. Only ' . $product['stock_quantity'] . ' available.']);
                exit;
            }
            $_SESSION['error'] = 'Insufficient stock.';
            header('Location: ' . BASE_URL . '/products/view?id=' . $productId);
            exit;
        }
        
        // Check if already in cart
        $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $productId]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            if ($newQuantity > $product['stock_quantity']) {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Cannot add more items than available stock.']);
                    exit;
                }
                $_SESSION['error'] = 'Cannot add more items than available stock.';
                header('Location: ' . BASE_URL . '/products/view?id=' . $productId);
                exit;
            }
            
            $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$newQuantity, $existing['id']]);
        } else {
            // Add new item
            $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $productId, $quantity]);
        }
        
        if ($isAjax) {
            // Get cart count for header update
            $countSql = "SELECT SUM(quantity) as total FROM cart WHERE user_id = ?";
            $stmt = $this->db->prepare($countSql);
            $stmt->execute([$userId]);
            $count = $stmt->fetchColumn() ?? 0;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Product added to cart!',
                'cart_count' => $count,
                'product_name' => $product['name']
            ]);
            exit;
        }
        
        $_SESSION['success'] = 'Product added to cart!';
        header('Location: ' . BASE_URL . '/cart');
        exit;
    }
    
    /**
     * Update cart item
     */
    public function update() {
        AuthMiddleware::require();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
        
        Security::verifyCsrf();
        
        $cartId = $_POST['cart_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        $userId = AuthMiddleware::userId();
        
        $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$quantity, $cartId, $userId]);
        
        $_SESSION['success'] = 'Cart updated!';
        header('Location: ' . BASE_URL . '/cart');
        exit;
    }
    
    /**
     * Remove from cart
     */
    public function remove() {
        AuthMiddleware::require();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
        
        Security::verifyCsrf();
        
        $cartId = $_POST['cart_id'] ?? null;
        $userId = AuthMiddleware::userId();
        
        $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId, $userId]);
        
        $_SESSION['success'] = 'Item removed from cart!';
        header('Location: ' . BASE_URL . '/cart');
        exit;
    }
    
    /**
     * Checkout
     */
    public function checkout() {
        AuthMiddleware::require();
        
        $userId = AuthMiddleware::userId();
        $user = AuthMiddleware::user();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $cartItems = $this->getCartItems($userId);
            
            if (empty($cartItems)) {
                $_SESSION['error'] = 'Your cart is empty.';
                header('Location: ' . BASE_URL . '/cart');
                exit;
            }
            
            // Prepare order data
            $orderData = [
                'user_id' => $userId,
                'total_amount' => $this->calculateTotal($cartItems),
                'payment_method' => $_POST['payment_method'] ?? 'cash',
                'shipping_address' => Security::clean($_POST['shipping_address'] ?? $user['address']),
                'shipping_phone' => Security::clean($_POST['shipping_phone'] ?? $user['phone']),
                'notes' => Security::clean($_POST['notes'] ?? '')
            ];
            
            // Validation
            $validator = new Validator($orderData);
            $validator->required('shipping_address', 'Shipping address is required')
                     ->required('shipping_phone', 'Phone number is required');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                header('Location: ' . BASE_URL . '/cart/checkout');
                exit;
            }
            
            // Prepare order items
            $orderItems = [];
            foreach ($cartItems as $item) {
                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'seller_id' => $item['seller_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ];
            }
            
            // Create order
            $orderId = $this->orderModel->create($orderData, $orderItems);
            
            if ($orderId) {
                // Clear cart
                $sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$userId]);
                
                $_SESSION['success'] = 'Order placed successfully!';
                header('Location: ' . BASE_URL . '/dashboard/orders');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to place order. Please try again.';
                header('Location: ' . BASE_URL . '/cart/checkout');
                exit;
            }
        }
        
        $cartItems = $this->getCartItems($userId);
        $total = $this->calculateTotal($cartItems);
        
        require_once APP_PATH . '/views/cart/checkout.php';
    }
    
    /**
     * Get cart items for user
     * 
     * @param int $userId
     * @return array
     */
    private function getCartItems($userId) {
        $sql = "SELECT c.*, p.name, p.price, p.image, p.stock_quantity as stock, p.unit, p.seller_id,
                u.full_name as seller_name, cat.name as category_name
                FROM cart c
                JOIN products p ON c.product_id = p.id
                JOIN users u ON p.seller_id = u.id
                LEFT JOIN categories cat ON p.category_id = cat.id
                WHERE c.user_id = ? AND p.is_active = 1
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Calculate cart total
     * 
     * @param array $cartItems
     * @return float
     */
    private function calculateTotal($cartItems) {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}

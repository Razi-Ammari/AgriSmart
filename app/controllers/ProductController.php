<?php
/**
 * Product Controller
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles product operations: listing, view, create, edit, delete
 */
error_log('CREATE PRODUCT CONTROLLER HIT');
error_log(print_r($_POST, true));
error_log(print_r($_FILES, true));

class ProductController {
    
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
    }
    
    /**
     * Show products listing
     */
    public function index() {
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        
        $filters = [
            'category_id' => !empty($_GET['category']) ? $_GET['category'] : null,
            'search' => !empty($_GET['search']) ? $_GET['search'] : null,
            'min_price' => !empty($_GET['min_price']) ? $_GET['min_price'] : null,
            'max_price' => !empty($_GET['max_price']) ? $_GET['max_price'] : null,
            'sort' => $_GET['sort'] ?? 'latest'
        ];
        
        $products = $this->productModel->getAll($page, $perPage, $filters);
        $categories = $this->productModel->getCategories();
        
        require_once APP_PATH . '/views/products/index.php';
    }
    
    /**
     * Show product details
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Product not found.';
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            $_SESSION['error'] = 'Product not found.';
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        // Increment views
        $this->productModel->incrementViews($id);
        
        // Get reviews and rating
        $reviews = $this->productModel->getReviews($id);
        $rating = $this->productModel->getAverageRating($id);
        
        // Get recommended products
        $recommended = $this->productModel->getRecommended($id, 4);
        
        require_once APP_PATH . '/views/products/view.php';
    }
    
    /**
     * Show create product form
     */
    public function create() {
        RoleMiddleware::buyer();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();

            $data = [
                'seller_id' => AuthMiddleware::userId(),
                'category_id' => $_POST['category_id'] ?? '',
                'name' => Security::clean($_POST['name'] ?? ''),
                'description' => Security::clean($_POST['description'] ?? ''),
                'price' => $_POST['price'] ?? '',
                'stock_quantity' => $_POST['stock_quantity'] ?? 0,
                'unit' => Security::clean($_POST['unit'] ?? 'piece'),
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                // Plant-specific fields
                'min_temperature' => !empty($_POST['min_temperature']) ? $_POST['min_temperature'] : null,
                'max_temperature' => !empty($_POST['max_temperature']) ? $_POST['max_temperature'] : null,
                'min_humidity' => !empty($_POST['min_humidity']) ? $_POST['min_humidity'] : null,
                'max_humidity' => !empty($_POST['max_humidity']) ? $_POST['max_humidity'] : null,
                'origin' => !empty($_POST['origin']) ? Security::clean($_POST['origin']) : null,
                'sunlight_requirement' => !empty($_POST['sunlight_requirement']) ? Security::clean($_POST['sunlight_requirement']) : null,
                'watering_frequency' => !empty($_POST['watering_frequency']) ? Security::clean($_POST['watering_frequency']) : null,
                'soil_type' => !empty($_POST['soil_type']) ? Security::clean($_POST['soil_type']) : null,
                'growing_season' => !empty($_POST['growing_season']) ? Security::clean($_POST['growing_season']) : null,
                'harvest_time' => !empty($_POST['harvest_time']) ? Security::clean($_POST['harvest_time']) : null,
                'image' => null // Always initialize image to null
            ];
            
            // Validation
            $validator = new Validator($data);
            $validator->required('name', 'Product name is required')
                     ->min('name', 3, 'Product name must be at least 3 characters')
                     ->required('category_id', 'Category is required')
                     ->required('price', 'Price is required')
                     ->numeric('price', 'Price must be a number')
                     ->minValue('price', 0.01, 'Price must be greater than 0')
                     ->numeric('stock_quantity', 'Stock quantity must be a number');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                $_SESSION['old'] = $data;
                header('Location: ' . BASE_URL . '/products/create');
                exit;
            }
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $validation = Security::validateFileUpload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif'], 5242880);
                
                if (!$validation['success']) {
                    $_SESSION['error'] = $validation['message'];
                    $_SESSION['old'] = $data;
                    header('Location: ' . BASE_URL . '/products/create');
                    exit;
                }
                
                // Create upload directory if not exists
                $uploadDir = PUBLIC_PATH . '/assets/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('product_') . '.' . $extension;
                $uploadPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $data['image'] = $filename;
                }
            }
            
            // Generate slug
            $data['slug'] = Security::generateSlug($data['name']);
            
            // Create product
            $productId = $this->productModel->create($data);
            
            if ($productId) {
                $_SESSION['success'] = 'Product created successfully!';
                header('Location: ' . BASE_URL . '/products/view?id=' . $productId);
                exit;
            } else {
                error_log("Product creation failed for user: " . AuthMiddleware::userId());
                $_SESSION['error'] = 'Failed to create product. Please try again or contact support.';
                $_SESSION['old'] = $data;
                header('Location: ' . BASE_URL . '/products/create');
                exit;
            }
        }
        
        $categories = $this->productModel->getCategories();
        require_once APP_PATH . '/views/products/create.php';
    }
    
    /**
     * Show edit product form
     */
    public function edit() {
        RoleMiddleware::buyer();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Product not found.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product || $product['seller_id'] != AuthMiddleware::userId()) {
            $_SESSION['error'] = 'Product not found or you do not have permission.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $data = [
                'category_id' => $_POST['category_id'] ?? '',
                'name' => Security::clean($_POST['name'] ?? ''),
                'description' => Security::clean($_POST['description'] ?? ''),
                'price' => $_POST['price'] ?? '',
                'stock_quantity' => $_POST['stock_quantity'] ?? 0,
                'unit' => Security::clean($_POST['unit'] ?? 'piece'),
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                // Plant-specific fields
                'min_temperature' => !empty($_POST['min_temperature']) ? $_POST['min_temperature'] : null,
                'max_temperature' => !empty($_POST['max_temperature']) ? $_POST['max_temperature'] : null,
                'min_humidity' => !empty($_POST['min_humidity']) ? $_POST['min_humidity'] : null,
                'max_humidity' => !empty($_POST['max_humidity']) ? $_POST['max_humidity'] : null,
                'origin' => !empty($_POST['origin']) ? Security::clean($_POST['origin']) : null,
                'sunlight_requirement' => !empty($_POST['sunlight_requirement']) ? Security::clean($_POST['sunlight_requirement']) : null,
                'watering_frequency' => !empty($_POST['watering_frequency']) ? Security::clean($_POST['watering_frequency']) : null,
                'soil_type' => !empty($_POST['soil_type']) ? Security::clean($_POST['soil_type']) : null,
                'growing_season' => !empty($_POST['growing_season']) ? Security::clean($_POST['growing_season']) : null,
                'harvest_time' => !empty($_POST['harvest_time']) ? Security::clean($_POST['harvest_time']) : null
            ];
            
            // Validation
            $validator = new Validator($data);
            $validator->required('name', 'Product name is required')
                     ->min('name', 3, 'Product name must be at least 3 characters')
                     ->required('category_id', 'Category is required')
                     ->required('price', 'Price is required')
                     ->numeric('price', 'Price must be a number')
                     ->minValue('price', 0.01, 'Price must be greater than 0')
                     ->numeric('stock_quantity', 'Stock quantity must be a number');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                $_SESSION['old'] = $data;
                header('Location: ' . BASE_URL . '/products/edit?id=' . $id);
                exit;
            }
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $validation = Security::validateFileUpload($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif'], 5242880);
                
                if ($validation['success']) {
                    $uploadDir = PUBLIC_PATH . '/assets/uploads/products/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('product_') . '.' . $extension;
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Delete old image
                        if ($product['image'] && file_exists($uploadDir . $product['image'])) {
                            unlink($uploadDir . $product['image']);
                        }
                        $data['image'] = $filename;
                    }
                }
            }
            
            // Generate slug if name changed
            if ($data['name'] !== $product['name']) {
                $data['slug'] = Security::generateSlug($data['name']);
            }
            
            // Update product
            $success = $this->productModel->update($id, $data);
            
            if ($success) {
                $_SESSION['success'] = 'Product updated successfully!';
                header('Location: ' . BASE_URL . '/products/view?id=' . $id);
                exit;
            } else {
                $_SESSION['error'] = 'Failed to update product.';
                header('Location: ' . BASE_URL . '/products/edit?id=' . $id);
                exit;
            }
        }
        
        $categories = $this->productModel->getCategories();
        require_once APP_PATH . '/views/products/edit.php';
    }
    
    /**
     * Delete product
     */
    public function delete() {
        RoleMiddleware::buyer();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        Security::verifyCsrf();
        
        $id = $_POST['product_id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Product not found.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product || $product['seller_id'] != AuthMiddleware::userId()) {
            $_SESSION['error'] = 'Product not found or you do not have permission.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        // Delete product image
        if ($product['image']) {
            $imagePath = PUBLIC_PATH . '/assets/uploads/products/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Delete product
        $success = $this->productModel->delete($id);
        
        if ($success) {
            $_SESSION['success'] = 'Product deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete product.';
        }
        
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    
    /**
     * Show products by category
     */
    public function category() {
        $categoryId = $_GET['id'] ?? null;
        $page = $_GET['page'] ?? 1;
        
        if (!$categoryId) {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }
        
        $filters = ['category_id' => $categoryId];
        $products = $this->productModel->getAll($page, 12, $filters);
        $categories = $this->productModel->getCategories();
        
        require_once APP_PATH . '/views/products/index.php';
    }
    
    /**
     * Search products
     */
    public function search() {
        $query = $_GET['q'] ?? '';
        $page = $_GET['page'] ?? 1;
        
        $filters = ['search' => $query];
        $products = $this->productModel->getAll($page, 12, $filters);
        $categories = $this->productModel->getCategories();
        
        require_once APP_PATH . '/views/products/index.php';
    }
    
    /**
     * Smart AI-style recommendations based on growing conditions
     */
    public function recommendations() {
        $recommendations = null;
        
        // Check if form was submitted with at least one parameter
        if (!empty($_GET['min_temp']) || !empty($_GET['max_temp']) || 
            !empty($_GET['min_humidity']) || !empty($_GET['max_humidity']) || 
            !empty($_GET['season'])) {
            
            $conditions = [
                'min_temp' => !empty($_GET['min_temp']) ? floatval($_GET['min_temp']) : null,
                'max_temp' => !empty($_GET['max_temp']) ? floatval($_GET['max_temp']) : null,
                'min_humidity' => !empty($_GET['min_humidity']) ? intval($_GET['min_humidity']) : null,
                'max_humidity' => !empty($_GET['max_humidity']) ? intval($_GET['max_humidity']) : null,
                'season' => !empty($_GET['season']) ? $_GET['season'] : null
            ];
            
            $recommendations = $this->productModel->getRecommendations($conditions);
        }
        
        require_once APP_PATH . '/views/products/recommendations.php';
    }
}

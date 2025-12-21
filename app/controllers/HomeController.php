<?php
/**
 * Home Controller
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles home page and public pages
 */

class HomeController {
    
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
    }
    
    /**
     * Show home page
     */
    public function index() {
        // Get featured products
        $featuredProducts = $this->productModel->getFeatured(8);
        
        // Get latest products
        $latestProducts = $this->productModel->getLatest(8);
        
        // Get categories
        $categories = $this->productModel->getCategories();
        
        // Get user if logged in
        $user = AuthMiddleware::user();
        
        require_once APP_PATH . '/views/home.php';
    }
    
    /**
     * Show about page
     */
    public function about() {
        require_once APP_PATH . '/views/pages/about.php';
    }
    
    /**
     * Show contact page
     */
    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            // Handle contact form submission
            $_SESSION['success'] = 'Thank you for your message. We will get back to you soon.';
            header('Location: ' . BASE_URL . '/contact');
            exit;
        }
        
        require_once APP_PATH . '/views/pages/contact.php';
    }
}

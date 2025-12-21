<?php
/**
 * Authentication Middleware
 * AgriSmart - Agriculture Marketplace
 * 
 * Protects routes that require authentication
 */

class AuthMiddleware {
    
    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    public static function check() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Require authentication
     * Redirects to login if not authenticated
     */
    public static function require() {
        if (!self::check()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $_SESSION['error'] = 'Please login to continue.';
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }
    
    /**
     * Require guest (not authenticated)
     * Redirects to dashboard if authenticated
     */
    public static function guest() {
        if (self::check()) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Get current user data
     * 
     * @return array|null
     */
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        if (!isset($_SESSION['user_data'])) {
            $userModel = new User();
            $_SESSION['user_data'] = $userModel->findById($_SESSION['user_id']);
        }
        
        return $_SESSION['user_data'] ?? null;
    }
    
    /**
     * Get user ID
     * 
     * @return int|null
     */
    public static function userId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get user role
     * 
     * @return string|null
     */
    public static function userRole() {
        $user = self::user();
        return $user['role'] ?? null;
    }
    
    /**
     * Login user
     * 
     * @param array $user
     */
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = $user;
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        // Clear all session data
        $_SESSION = [];
        
        // Destroy session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destroy session
        session_destroy();
        
        // Start new session
        session_start();
        $_SESSION['success'] = 'You have been logged out successfully.';
    }
    
    /**
     * Check session timeout
     * 
     * @param int $timeout Timeout in seconds (default: 30 minutes)
     */
    public static function checkTimeout($timeout = 1800) {
        if (self::check() && isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $timeout) {
                self::logout();
                $_SESSION['error'] = 'Your session has expired. Please login again.';
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
        }
        
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Refresh user data
     */
    public static function refreshUser() {
        if (self::check()) {
            $userModel = new User();
            $_SESSION['user_data'] = $userModel->findById($_SESSION['user_id']);
        }
    }
}

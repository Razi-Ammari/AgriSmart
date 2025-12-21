<?php
/**
 * Role Middleware
 * AgriSmart - Agriculture Marketplace
 * 
 * Protects routes based on user roles
 */

class RoleMiddleware {
    
    /**
     * Require specific role
     * 
     * @param string|array $roles
     */
    public static function require($roles) {
        // First check authentication
        AuthMiddleware::require();
        
        // Convert single role to array
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        // Get user role
        $userRole = AuthMiddleware::userRole();
        
        // Check if user has required role
        if (!in_array($userRole, $roles)) {
            http_response_code(403);
            $_SESSION['error'] = 'You do not have permission to access this page.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Require admin role
     */
    public static function admin() {
        self::require('admin');
    }
    
    /**
     * Require admin role (alias for admin())
     */
    public static function requireAdmin() {
        self::require('admin');
    }
    
    /**
     * Require buyer role
     */
    public static function buyer() {
        self::require(['admin', 'buyer']);
    }
    
    /**
     * Require user role
     */
    public static function user() {
        self::require(['admin', 'buyer', 'user']);
    }
    
    /**
     * Check if user is admin
     * 
     * @return bool
     */
    public static function isAdmin() {
        return AuthMiddleware::userRole() === 'admin';
    }
    
    /**
     * Check if user is buyer
     * 
     * @return bool
     */
    public static function isBuyer() {
        return AuthMiddleware::userRole() === 'buyer';
    }
    
    /**
     * Check if user is regular user
     * 
     * @return bool
     */
    public static function isUser() {
        return AuthMiddleware::userRole() === 'user';
    }
    
    /**
     * Check if user has any of the specified roles
     * 
     * @param string|array $roles
     * @return bool
     */
    public static function hasRole($roles) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        $userRole = AuthMiddleware::userRole();
        return in_array($userRole, $roles);
    }
}

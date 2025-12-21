<?php
/**
 * Security Helper Class
 * AgriSmart - Agriculture Marketplace
 * 
 * Provides security features: CSRF protection, XSS prevention, input sanitization
 */

class Security {
    
    /**
     * Initialize security settings
     */
    public static function init() {
        // Generate CSRF token if not exists
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Regenerate token periodically
        if (!isset($_SESSION['csrf_token_time'])) {
            $_SESSION['csrf_token_time'] = time();
        } elseif (time() - $_SESSION['csrf_token_time'] > 3600) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
    }
    
    /**
     * Get CSRF token
     * 
     * @return string
     */
    public static function getCsrfToken() {
        return $_SESSION['csrf_token'] ?? '';
    }
    
    /**
     * Generate CSRF token field for forms
     * 
     * @return string
     */
    public static function csrfField() {
        $token = self::getCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token
     * @return bool
     */
    public static function validateCsrfToken($token) {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Verify CSRF token from POST request
     * 
     * @return bool
     */
    public static function verifyCsrf() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        
        $token = $_POST['csrf_token'] ?? '';
        if (!self::validateCsrfToken($token)) {
            http_response_code(403);
            die('CSRF token validation failed.');
        }
        return true;
    }
    
    /**
     * Sanitize string (XSS prevention)
     * 
     * @param string $data
     * @return string
     */
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Clean input data
     * 
     * @param mixed $data
     * @return mixed
     */
    public static function clean($data) {
        if (is_array($data)) {
            return array_map([self::class, 'clean'], $data);
        }
        return trim(stripslashes($data));
    }
    
    /**
     * Escape output for HTML
     * 
     * @param string $data
     * @return string
     */
    public static function escape($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Hash password
     * 
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Verify password
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Generate random token
     * 
     * @param int $length
     * @return string
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Generate secure random string
     * 
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, $max)];
        }
        
        return $string;
    }
    
    /**
     * Validate file upload
     * 
     * @param array $file
     * @param array $allowedTypes
     * @param int $maxSize (in bytes)
     * @return array ['success' => bool, 'message' => string]
     */
    public static function validateFileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5242880) {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'message' => 'Invalid file upload.'];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload failed.'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File size exceeds limit.'];
        }
        
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension'] ?? '');
        
        if (!in_array($extension, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type.'];
        }
        
        // Verify actual file type (MIME)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        ];
        
        if (!isset($allowedMimes[$extension]) || $mimeType !== $allowedMimes[$extension]) {
            return ['success' => false, 'message' => 'File type mismatch.'];
        }
        
        return ['success' => true, 'message' => 'File is valid.'];
    }
    
    /**
     * Get client IP address
     * 
     * @return string
     */
    public static function getClientIp() {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
                return $_SERVER[$key];
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
    
    /**
     * Get user agent
     * 
     * @return string
     */
    public static function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    }
    
    /**
     * Prevent clickjacking
     */
    public static function preventClickjacking() {
        header('X-Frame-Options: SAMEORIGIN');
    }
    
    /**
     * Set security headers
     */
    public static function setSecurityHeaders() {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
    
    /**
     * Generate slug from string
     * 
     * @param string $string
     * @return string
     */
    public static function generateSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
}

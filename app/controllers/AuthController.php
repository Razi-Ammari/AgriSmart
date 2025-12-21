<?php
/**
 * Authentication Controller
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles user authentication: login, register, logout, password reset
 */

class AuthController {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Show login page
     */
    public function login() {
        AuthMiddleware::guest();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $login = Security::clean($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validation
            $validator = new Validator($_POST);
            $validator->required('login', 'Email or username is required')
                     ->required('password', 'Password is required');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
            
            // Authenticate
            $user = $this->userModel->authenticate($login, $password);
            
            if ($user) {
                AuthMiddleware::login($user);
                $_SESSION['success'] = 'Welcome back, ' . $user['full_name'] . '!';
                
                // Redirect to intended page or dashboard
                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard';
                unset($_SESSION['redirect_after_login']);
                
                header('Location: ' . $redirect);
                exit;
            } else {
                $_SESSION['error'] = 'Invalid credentials. Please try again.';
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
        }
        
        require_once APP_PATH . '/views/auth/login.php';
    }
    
    /**
     * Show registration page
     */
    public function register() {
        AuthMiddleware::guest();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $data = [
                'username' => Security::clean($_POST['username'] ?? ''),
                'email' => Security::clean($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'password_confirm' => $_POST['password_confirm'] ?? '',
                'full_name' => Security::clean($_POST['full_name'] ?? ''),
                'phone' => Security::clean($_POST['phone'] ?? ''),
                'address' => Security::clean($_POST['address'] ?? ''),
                'role' => Security::clean($_POST['role'] ?? 'user')
            ];
            
            // Validation
            $validator = new Validator($data);
            $validator->required('username', 'Username is required')
                     ->min('username', 3, 'Username must be at least 3 characters')
                     ->alphanumeric('username', 'Username must contain only letters and numbers')
                     ->unique('username', 'users', 'username', null, 'Username already exists')
                     ->required('email', 'Email is required')
                     ->email('email', 'Invalid email address')
                     ->unique('email', 'users', 'email', null, 'Email already exists')
                     ->required('full_name', 'Full name is required')
                     ->required('password', 'Password is required')
                     ->min('password', 6, 'Password must be at least 6 characters')
                     ->matches('password_confirm', 'password', 'Passwords do not match')
                     ->in('role', ['user', 'buyer'], 'Invalid role selected');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                $_SESSION['old'] = $data;
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }
            
            // Create user
            $userId = $this->userModel->create($data);
            
            if ($userId) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                $_SESSION['old'] = $data;
                header('Location: ' . BASE_URL . '/auth/register');
                exit;
            }
        }
        
        require_once APP_PATH . '/views/auth/register.php';
    }
    
    /**
     * Logout user
     */
    public function logout() {
        AuthMiddleware::logout();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
    
    /**
     * Show forgot password page
     */
    public function forgotPassword() {
        AuthMiddleware::guest();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $email = Security::clean($_POST['email'] ?? '');
            
            // Validation
            $validator = new Validator(['email' => $email]);
            $validator->required('email', 'Email is required')
                     ->email('email', 'Invalid email address');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                header('Location: ' . BASE_URL . '/auth/forgot-password');
                exit;
            }
            
            // Create reset token
            $token = $this->userModel->createPasswordResetToken($email);
            
            if ($token) {
                // In production, send email with reset link
                // For now, show token in session (development only)
                $_SESSION['success'] = 'Password reset instructions sent to your email.';
                $_SESSION['reset_token'] = $token; // For development - remove in production
                $_SESSION['reset_email'] = $email;
                header('Location: ' . BASE_URL . '/auth/reset-password');
                exit;
            } else {
                $_SESSION['error'] = 'Email address not found.';
                header('Location: ' . BASE_URL . '/auth/forgot-password');
                exit;
            }
        }
        
        require_once APP_PATH . '/views/auth/forgot-password.php';
    }
    
    /**
     * Show reset password page
     */
    public function resetPassword() {
        AuthMiddleware::guest();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf();
            
            $token = Security::clean($_POST['token'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            // Validation
            $validator = new Validator($_POST);
            $validator->required('token', 'Reset token is required')
                     ->required('password', 'Password is required')
                     ->min('password', 6, 'Password must be at least 6 characters')
                     ->matches('password_confirm', 'password', 'Passwords do not match');
            
            if ($validator->fails()) {
                $_SESSION['error'] = $validator->getFirstError();
                $_SESSION['old'] = ['token' => $token];
                header('Location: ' . BASE_URL . '/auth/reset-password');
                exit;
            }
            
            // Reset password
            $success = $this->userModel->resetPassword($token, $password);
            
            if ($success) {
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_email']);
                $_SESSION['success'] = 'Password reset successful! Please login with your new password.';
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            } else {
                $_SESSION['error'] = 'Invalid or expired reset token.';
                $_SESSION['old'] = ['token' => $token];
                header('Location: ' . BASE_URL . '/auth/reset-password');
                exit;
            }
        }
        
        require_once APP_PATH . '/views/auth/reset-password.php';
    }
}

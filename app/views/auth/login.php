<?php
$pageTitle = 'Login - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2>Welcome Back! 🌱</h2>
                        <p class="text-muted">Login to your AgriSmart account</p>
                    </div>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/login">
                        <?php echo Security::csrfField(); ?>
                        
                        <div class="form-floating mb-3">
                            <input type="text" 
                                   class="form-control" 
                                   id="login" 
                                   name="login" 
                                   placeholder="Email or Username"
                                   value="<?php echo Security::escape($_SESSION['old']['login'] ?? ''); ?>"
                                   required>
                            <label for="login">
                                <i class="bi bi-person"></i> Email or Username
                            </label>
                        </div>
                        
                        <div class="mb-3 position-relative">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Password"
                                   style="padding-right: 3rem;"
                                   required>
                            <i class="bi bi-eye position-absolute" id="password-icon" onclick="togglePassword('password')" style="right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 5; font-size: 1.2rem; color: #6c757d;"></i>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="<?php echo BASE_URL; ?>/auth/forgot-password" class="text-decoration-none">
                                Forgot Password?
                            </a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                        
                        <div class="text-center">
                            <p class="text-muted">
                                Don't have an account? 
                                <a href="<?php echo BASE_URL; ?>/auth/register">Register here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

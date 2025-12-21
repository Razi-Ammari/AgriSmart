<?php
$pageTitle = 'Forgot Password - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2>Forgot Password? 🔐</h2>
                        <p class="text-muted">Enter your email to reset your password</p>
                    </div>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/forgot-password">
                        <?php echo Security::csrfField(); ?>
                        
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                            <label for="email"><i class="bi bi-envelope"></i> Email Address</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-send"></i> Send Reset Link
                        </button>
                        
                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>/auth/login">← Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

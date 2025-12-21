<?php
$pageTitle = 'Reset Password - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2>Reset Password 🔑</h2>
                        <p class="text-muted">Enter your new password</p>
                    </div>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/reset-password">
                        <?php echo Security::csrfField(); ?>
                        
                        <input type="hidden" name="token" value="<?php echo Security::escape($_SESSION['reset_token'] ?? $_GET['token'] ?? ''); ?>">
                        
                        <div class="mb-3 position-relative">
                            <input type="password" class="form-control" id="password" name="password" placeholder="New Password" style="padding-right: 3rem;" required>
                            <i class="bi bi-eye position-absolute" id="password-icon" onclick="togglePassword('password')" style="right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 5; font-size: 1.2rem; color: #6c757d;"></i>
                        </div>
                        
                        <div class="mb-4 position-relative">
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password" style="padding-right: 3rem;" required>
                            <i class="bi bi-eye position-absolute" id="password_confirm-icon" onclick="togglePassword('password_confirm')" style="right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 5; font-size: 1.2rem; color: #6c757d;"></i>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

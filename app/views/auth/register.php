<?php
$pageTitle = 'Register - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2>Join AgriSmart 🌾</h2>
                        <p class="text-muted">Create your account and start trading</p>
                    </div>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/register">
                        <?php echo Security::csrfField(); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           placeholder="Username"
                                           value="<?php echo Security::escape($_SESSION['old']['username'] ?? ''); ?>"
                                           required>
                                    <label for="username">Username</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" 
                                           class="form-control" 
                                           id="full_name" 
                                           name="full_name" 
                                           placeholder="Full Name"
                                           value="<?php echo Security::escape($_SESSION['old']['full_name'] ?? ''); ?>"
                                           required>
                                    <label for="full_name">Full Name</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Email"
                                   value="<?php echo Security::escape($_SESSION['old']['email'] ?? ''); ?>"
                                   required>
                            <label for="email">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone" 
                                           placeholder="Phone"
                                           value="<?php echo Security::escape($_SESSION['old']['phone'] ?? ''); ?>">
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="user" <?php echo (($_SESSION['old']['role'] ?? 'user') === 'user') ? 'selected' : ''; ?>>Buyer</option>
                                        <option value="buyer" <?php echo (($_SESSION['old']['role'] ?? '') === 'buyer') ? 'selected' : ''; ?>>Seller</option>
                                    </select>
                                    <label for="role">Account Type</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <textarea class="form-control" 
                                      id="address" 
                                      name="address" 
                                      placeholder="Address"
                                      style="height: 80px;"><?php echo Security::escape($_SESSION['old']['address'] ?? ''); ?></textarea>
                            <label for="address">Address</label>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 position-relative">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirm" 
                                           name="password_confirm" 
                                           placeholder="Confirm Password"
                                           style="padding-right: 3rem;"
                                           required>
                                    <i class="bi bi-eye position-absolute" id="password_confirm-icon" onclick="togglePassword('password_confirm')" style="right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 5; font-size: 1.2rem; color: #6c757d;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-person-plus"></i> Create Account
                        </button>
                        
                        <div class="text-center">
                            <p class="text-muted">
                                Already have an account? 
                                <a href="<?php echo BASE_URL; ?>/auth/login">Login here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

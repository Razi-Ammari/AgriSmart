    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-shop"></i> AgriSmart
                    </h5>
                    <p class="text-light opacity-75">
                        Your trusted marketplace for agricultural products. 
                        Buy and sell fresh vegetables, fruits, seeds, and more.
                    </p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>/products">Products</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>/about">About Us</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>/contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h6 class="mb-3">Categories</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Vegetables</a></li>
                        <li class="mb-2"><a href="#">Fruits</a></li>
                        <li class="mb-2"><a href="#">Seeds</a></li>
                        <li class="mb-2"><a href="#">Plants</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h6 class="mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt"></i>
                            123 Farm Street, Agricultural District
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone"></i>
                            +1 (234) 567-8900
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope"></i>
                            info@agrismart.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-light opacity-25">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 opacity-75">
                        &copy; <?php echo date('Y'); ?> AgriSmart. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-3">Privacy Policy</a>
                    <a href="#" class="me-3">Terms of Service</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Agro Assistant Chatbot -->
    <?php require_once APP_PATH . '/views/partials/chatbot.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>/assets/js/app.js"></script>
    
    <?php if (isset($customJS)): ?>
        <?php echo $customJS; ?>
    <?php endif; ?>
    
</body>
</html>
<?php
// Clear old input data
unset($_SESSION['old']);
?>

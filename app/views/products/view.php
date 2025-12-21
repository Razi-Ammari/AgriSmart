<?php
$pageTitle = $product['name'] . ' - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
/* ========================================
   PRODUCT DETAILS PAGE - PREMIUM DESIGN
   ======================================== */

.product-details-page {
    background: #fafafa;
    min-height: 100vh;
    padding: 2rem 0 4rem;
}

/* Breadcrumb */
.pd-breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.pd-breadcrumb a {
    color: #2e7d32;
    text-decoration: none;
    transition: color 0.2s;
}

.pd-breadcrumb a:hover {
    color: #1b5e20;
}

.pd-breadcrumb-separator {
    margin: 0 0.5rem;
    color: #999;
}

/* Main Container */
.pd-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Product Card */
.pd-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

/* Two Column Layout */
.pd-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

/* Left Column - Image */
.pd-image-section {
    padding: 2rem;
}

.pd-image-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #f5f5f5;
    aspect-ratio: 1;
}

.pd-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.pd-image:hover {
    transform: scale(1.05);
}

.pd-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #ddd;
}

.pd-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Right Column - Details */
.pd-details-section {
    padding: 2rem 2rem 2rem 0;
    display: flex;
    flex-direction: column;
}

.pd-category {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #e8f5e9;
    color: #2e7d32;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 1rem;
    width: fit-content;
}

.pd-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.pd-price-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-radius: 12px;
}

.pd-price {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2e7d32;
    margin-bottom: 0.5rem;
}

.pd-unit {
    font-size: 1.1rem;
    color: #666;
}

.pd-description {
    margin-bottom: 2rem;
}

.pd-description h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.75rem;
}

.pd-description p {
    color: #666;
    line-height: 1.7;
}

/* Agricultural Details Grid */
.pd-ag-details {
    margin-bottom: 2rem;
}

.pd-ag-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.pd-ag-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.pd-ag-item {
    background: #f9f9f9;
    padding: 1rem;
    border-radius: 8px;
    border-left: 3px solid #2e7d32;
}

.pd-ag-label {
    font-size: 0.85rem;
    color: #777;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.pd-ag-label i {
    color: #2e7d32;
}

.pd-ag-value {
    font-weight: 600;
    color: #333;
}

/* Stock Info */
.pd-stock-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f9f9f9;
    border-radius: 8px;
}

.pd-stock-label {
    font-size: 0.9rem;
    color: #777;
    margin-bottom: 0.5rem;
}

.pd-stock-available {
    font-weight: 600;
    color: #2e7d32;
}

.pd-stock-unavailable {
    font-weight: 600;
    color: #d32f2f;
}

/* Quantity Selector */
.pd-purchase-form {
    margin-top: auto;
}

.pd-quantity-selector {
    margin-bottom: 1.5rem;
}

.pd-quantity-label {
    font-weight: 500;
    color: #555;
    margin-bottom: 0.5rem;
    display: block;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-btn {
    width: 36px;
    height: 36px;
    border: 2px solid #2e7d32;
    background: white;
    color: #2e7d32;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    font-size: 1.1rem;
}

.qty-btn:hover {
    background: #2e7d32;
    color: white;
}

.qty-btn:active {
    transform: scale(0.95);
}

.pd-quantity-input {
    width: 80px;
    text-align: center;
    padding: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 1.1rem;
    font-weight: 600;
}

.pd-quantity-input:focus {
    outline: none;
    border-color: #2e7d32;
}

.pd-seller-notice {
    background: #fff3e0;
    border: 2px solid #f57c00;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    color: #f57c00;
    font-weight: 500;
}

.pd-seller-notice i {
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

.pd-cta-button {
    width: 100%;
    padding: 1rem 1.5rem;
    background: #2e7d32;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.pd-cta-button:hover {
    background: #1b5e20;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
}

.pd-cta-button:active {
    transform: translateY(0);
}

.pd-cta-button i {
    font-size: 1.2rem;
}

/* Seller Info */
.pd-seller-card {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 1.25rem;
    border: 1px solid #e5e5e5;
    margin-top: 1.5rem;
}

.pd-seller-label {
    font-size: 0.85rem;
    color: #777;
    margin-bottom: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.pd-seller-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.4rem;
}

.pd-seller-contact {
    font-size: 0.95rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.pd-seller-contact i {
    color: #2e7d32;
}

/* Recommended Products */
.pd-recommended {
    margin-top: 3rem;
}

.pd-recommended h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
}

.pd-rec-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.pd-rec-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
}

.pd-rec-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.pd-rec-image {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
}

.pd-rec-body {
    padding: 1rem;
}

.pd-rec-category {
    font-size: 0.8rem;
    color: #2e7d32;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.pd-rec-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.75rem;
}

.pd-rec-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pd-rec-price {
    font-weight: 700;
    color: #2e7d32;
}

.pd-rec-link {
    color: #2e7d32;
    text-decoration: none;
    font-size: 0.9rem;
}

.pd-rec-link:hover {
    text-decoration: underline;
}

/* Cart Modal */
.cart-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease-out;
}

.cart-modal-overlay.active {
    display: flex;
}

.cart-modal-box {
    background: white;
    border-radius: 16px;
    padding: 2.5rem;
    max-width: 500px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
}

.cart-modal-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.cart-modal-icon i {
    font-size: 3rem;
    color: #2e7d32;
}

.cart-modal-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.75rem;
}

.cart-modal-message {
    color: #666;
    font-size: 1.05rem;
    margin-bottom: 2rem;
}

.cart-modal-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.cart-modal-btn {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    min-width: 180px;
}

.cart-modal-continue {
    background: #f5f5f5;
    color: #333;
}

.cart-modal-continue:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
}

.cart-modal-go {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
}

.cart-modal-go:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
    color: white;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive Design */
@media (max-width: 992px) {
    .pd-layout {
        grid-template-columns: 1fr;
    }
    
    .pd-details-section {
        padding: 2rem;
    }
    
    .pd-rec-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .pd-title {
        font-size: 1.8rem;
    }
    
    .pd-price {
        font-size: 2rem;
    }
    
    .pd-ag-grid {
        grid-template-columns: 1fr;
    }
    
    .pd-rec-grid {
        grid-template-columns: 1fr;
    }
    
    .cart-modal-actions {
        flex-direction: column;
    }
    
    .cart-modal-btn {
        width: 100%;
    }
}
</style>

<div class="product-details-page">
    <div class="pd-container">
        <!-- Breadcrumb -->
        <div class="pd-breadcrumb">
            <a href="<?php echo BASE_URL; ?>">Home</a>
            <span class="pd-breadcrumb-separator">/</span>
            <a href="<?php echo BASE_URL; ?>/products">Products</a>
            <span class="pd-breadcrumb-separator">/</span>
            <a href="<?php echo BASE_URL; ?>/products?category=<?php echo $product['category_id']; ?>">
                <?php echo Security::escape($product['category_name']); ?>
            </a>
            <span class="pd-breadcrumb-separator">/</span>
            <span><?php echo Security::escape($product['name']); ?></span>
        </div>

        <!-- Main Product Card -->
        <div class="pd-card">
            <div class="pd-layout">
                <!-- Left: Image -->
                <div class="pd-image-section">
                    <div class="pd-image-container">
                        <?php if ($product['is_featured']): ?>
                            <div class="pd-badge">Featured</div>
                        <?php endif; ?>
                        
                        <?php if ($product['image']): ?>
                            <img src="<?php echo BASE_URL . '/assets/uploads/products/' . $product['image']; ?>" 
                                 alt="<?php echo Security::escape($product['name']); ?>" 
                                 class="pd-image">
                        <?php else: ?>
                            <div class="pd-image-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right: Details -->
                <div class="pd-details-section">
                    <div class="pd-category">
                        <i class="bi bi-tag-fill"></i>
                        <?php echo Security::escape($product['category_name']); ?>
                    </div>

                    <h1 class="pd-title"><?php echo Security::escape($product['name']); ?></h1>

                    <div class="pd-price-section">
                        <div class="pd-price">
                            $<?php echo number_format($product['price'], 2); ?>
                        </div>
                        <div class="pd-unit">per <?php echo Security::escape($product['unit']); ?></div>
                    </div>

                    <div class="pd-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(Security::escape($product['description'])); ?></p>
                    </div>

                    <?php if (!empty($product['min_temperature']) || !empty($product['growing_season'])): ?>
                    <div class="pd-ag-details">
                        <div class="pd-ag-title">Growing Information</div>
                        <div class="pd-ag-grid">
                            <?php if (!empty($product['min_temperature']) && !empty($product['max_temperature'])): ?>
                            <div class="pd-ag-item">
                                <div class="pd-ag-label">
                                    <i class="bi bi-thermometer-half"></i>
                                    Temperature Range
                                </div>
                                <div class="pd-ag-value">
                                    <?php echo $product['min_temperature']; ?>°C - <?php echo $product['max_temperature']; ?>°C
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($product['min_humidity']) && !empty($product['max_humidity'])): ?>
                            <div class="pd-ag-item">
                                <div class="pd-ag-label">
                                    <i class="bi bi-droplet-half"></i>
                                    Humidity Range
                                </div>
                                <div class="pd-ag-value">
                                    <?php echo $product['min_humidity']; ?>% - <?php echo $product['max_humidity']; ?>%
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($product['growing_season'])): ?>
                            <div class="pd-ag-item">
                                <div class="pd-ag-label">
                                    <i class="bi bi-calendar-range"></i>
                                    Growing Season
                                </div>
                                <div class="pd-ag-value">
                                    <?php echo ucfirst($product['growing_season']); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($product['sunlight_requirement'])): ?>
                            <div class="pd-ag-item">
                                <div class="pd-ag-label">
                                    <i class="bi bi-sun"></i>
                                    Sunlight
                                </div>
                                <div class="pd-ag-value">
                                    <?php echo ucfirst($product['sunlight_requirement']); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($product['watering_frequency'])): ?>
                            <div class="pd-ag-item">
                                <div class="pd-ag-label">
                                    <i class="bi bi-droplet"></i>
                                    Watering
                                </div>
                                <div class="pd-ag-value">
                                    <?php echo ucfirst($product['watering_frequency']); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($product['soil_type'])): ?>
                            <div class="pd-ag-item">
                                <div class="pd-ag-label">
                                    <i class="bi bi-tree"></i>
                                    Soil Type
                                </div>
                                <div class="pd-ag-value">
                                    <?php echo ucfirst($product['soil_type']); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="pd-stock-section">
                        <div class="pd-stock-label">Availability:</div>
                        <div>
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <span class="pd-stock-available"><?php echo $product['stock_quantity']; ?> <?php echo Security::escape($product['unit']); ?> available</span>
                            <?php else: ?>
                                <span class="pd-stock-unavailable">Out of stock</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Purchase Form -->
                    <?php if ($product['stock_quantity'] > 0): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>/cart/add" class="pd-purchase-form" id="addToCartForm">
                        <?php echo Security::csrfField(); ?>
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="ajax" value="1">
                        
                        <div class="pd-quantity-selector">
                            <label class="pd-quantity-label">Quantity:</label>
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn" id="qtyMinus">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" 
                                       class="pd-quantity-input" 
                                       name="quantity" 
                                       id="quantityInput"
                                       value="1" 
                                       min="1" 
                                       max="<?php echo $product['stock_quantity']; ?>">
                                <button type="button" class="qty-btn" id="qtyPlus">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <?php if (AuthMiddleware::check()): ?>
                            <?php if ($product['seller_id'] == AuthMiddleware::userId()): ?>
                                <div class="pd-seller-notice">
                                    <i class="bi bi-info-circle"></i>
                                    This is your own product
                                </div>
                            <?php else: ?>
                                <button type="submit" class="pd-cta-button">
                                    <i class="bi bi-cart-plus"></i>
                                    Add to Cart
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/auth/login" class="pd-cta-button" style="text-decoration: none;">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login to Purchase
                            </a>
                        <?php endif; ?>
                    </form>
                    <?php endif; ?>

                    <!-- Seller Info -->
                    <div class="pd-seller-card">
                        <div class="pd-seller-label">
                            <i class="bi bi-person-badge"></i>
                            Seller Information
                        </div>
                        <div class="pd-seller-name"><?php echo Security::escape($product['seller_name']); ?></div>
                        <div class="pd-seller-contact">
                            <i class="bi bi-telephone"></i>
                            <?php echo Security::escape($product['seller_phone']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Products -->
        <?php if (!empty($recommended)): ?>
        <div class="pd-recommended">
            <h3>You May Also Like</h3>
            <div class="pd-rec-grid">
                <?php foreach ($recommended as $rec): ?>
                <div class="pd-rec-card">
                    <?php if ($rec['image']): ?>
                        <img src="<?php echo BASE_URL . '/assets/uploads/products/' . $rec['image']; ?>" 
                             alt="<?php echo Security::escape($rec['name']); ?>" 
                             class="pd-rec-image">
                    <?php else: ?>
                        <div class="pd-image-placeholder" style="aspect-ratio: 4/3;">
                            <i class="bi bi-image"></i>
                        </div>
                    <?php endif; ?>
                    <div class="pd-rec-body">
                        <div class="pd-rec-category">
                            <i class="bi bi-tag"></i>
                            <?php echo Security::escape($rec['category_name']); ?>
                        </div>
                        <div class="pd-rec-name"><?php echo Security::escape($rec['name']); ?></div>
                        <div class="pd-rec-footer">
                            <div class="pd-rec-price">$<?php echo number_format($rec['price'], 2); ?></div>
                            <a href="<?php echo BASE_URL; ?>/products/view?id=<?php echo $rec['id']; ?>" class="pd-rec-link">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add to Cart Success Modal -->
<div class="cart-modal-overlay" id="cartModal">
    <div class="cart-modal-box">
        <div class="cart-modal-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h3 class="cart-modal-title">Added to Cart!</h3>
        <p class="cart-modal-message" id="cartModalMessage">Product has been added to your cart</p>
        <div class="cart-modal-actions">
            <button type="button" class="cart-modal-btn cart-modal-continue" onclick="closeCartModal()">
                <i class="bi bi-arrow-left"></i> Continue Shopping
            </button>
            <button type="button" class="cart-modal-btn cart-modal-go" onclick="goToCart()">
                <i class="bi bi-cart"></i> Go to Cart
            </button>
        </div>
    </div>
</div>

<script>
// Quantity controls
document.getElementById('qtyMinus')?.addEventListener('click', function() {
    const input = document.getElementById('quantityInput');
    const min = parseInt(input.min);
    const current = parseInt(input.value);
    if (current > min) {
        input.value = current - 1;
    }
});

document.getElementById('qtyPlus')?.addEventListener('click', function() {
    const input = document.getElementById('quantityInput');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
});

// Add to cart AJAX
document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            document.getElementById('cartModalMessage').textContent = 
                `"${data.product_name}" has been added to your cart`;
            document.getElementById('cartModal').classList.add('active');
        } else {
            alert(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

function closeCartModal() {
    document.getElementById('cartModal').classList.remove('active');
}

function goToCart() {
    window.location.href = '<?php echo BASE_URL; ?>/cart';
}

// Close modal on overlay click
document.getElementById('cartModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCartModal();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

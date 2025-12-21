<?php
$pageTitle = 'Checkout - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 1rem;
}

.checkout-header {
    text-align: center;
    margin-bottom: 3rem;
}

.checkout-header h1 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.checkout-steps {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
}

.checkout-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #999;
}

.checkout-step.active {
    color: #2e7d32;
    font-weight: 600;
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.checkout-step.active .step-number {
    background: #2e7d32;
    color: white;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    align-items: start;
}

.checkout-form {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f5f5f5;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: #2e7d32;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    font-weight: 500;
    color: #555;
    margin-bottom: 0.5rem;
}

.form-group label .required {
    color: #d32f2f;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.payment-option {
    position: relative;
}

.payment-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.payment-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.25rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

.payment-label i {
    font-size: 2rem;
    color: #666;
}

.payment-option input[type="radio"]:checked + .payment-label {
    border-color: #2e7d32;
    background: #e8f5e9;
}

.payment-option input[type="radio"]:checked + .payment-label i {
    color: #2e7d32;
}

.order-summary {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 100px;
}

.order-summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f5f5f5;
}

.order-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f5f5f5;
}

.order-item:last-of-type {
    border-bottom: 2px solid #f5f5f5;
}

.order-item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
}

.order-item-details {
    flex: 1;
}

.order-item-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.order-item-qty {
    font-size: 0.9rem;
    color: #666;
}

.order-item-price {
    font-weight: 600;
    color: #2e7d32;
    white-space: nowrap;
}

.summary-totals {
    margin-top: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    color: #666;
}

.summary-row.total {
    font-size: 1.4rem;
    font-weight: 700;
    color: #333;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 2px solid #2e7d32;
}

.summary-total-amount {
    color: #2e7d32;
}

.place-order-btn {
    width: 100%;
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    border: none;
    padding: 1.2rem;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1.5rem;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.place-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
}

.security-note {
    text-align: center;
    margin-top: 1rem;
    padding: 1rem;
    background: #f5f5f5;
    border-radius: 8px;
    font-size: 0.9rem;
    color: #666;
}

.security-note i {
    color: #2e7d32;
}

@media (max-width: 992px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
    
    .order-summary {
        position: static;
    }
}
</style>

<div class="checkout-container">
    <div class="checkout-header">
        <h1><i class="bi bi-bag-check"></i> Checkout</h1>
        <div class="checkout-steps">
            <div class="checkout-step">
                <div class="step-number">1</div>
                <span>Cart</span>
            </div>
            <div class="checkout-step active">
                <div class="step-number">2</div>
                <span>Checkout</span>
            </div>
            <div class="checkout-step">
                <div class="step-number">3</div>
                <span>Complete</span>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo BASE_URL; ?>/cart/checkout" class="checkout-content">
        <?php echo Security::csrfField(); ?>
        
        <div class="checkout-form">
            <!-- Shipping Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-truck"></i>
                    Shipping Information
                </div>
                
                <div class="form-group">
                    <label>
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           name="full_name" 
                           value="<?php echo Security::escape(AuthMiddleware::user()['full_name']); ?>" 
                           readonly>
                </div>
                
                <div class="form-group">
                    <label>
                        Phone Number <span class="required">*</span>
                    </label>
                    <input type="tel" 
                           class="form-control" 
                           name="shipping_phone" 
                           value="<?php echo Security::escape(AuthMiddleware::user()['phone'] ?? ''); ?>"
                           placeholder="Enter your phone number"
                           required>
                </div>
                
                <div class="form-group">
                    <label>
                        Shipping Address <span class="required">*</span>
                    </label>
                    <textarea class="form-control" 
                              name="shipping_address" 
                              placeholder="Enter your complete shipping address"
                              required><?php echo Security::escape(AuthMiddleware::user()['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Order Notes (Optional)</label>
                    <textarea class="form-control" 
                              name="notes" 
                              placeholder="Any special instructions for your order?"></textarea>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-credit-card"></i>
                    Payment Method
                </div>
                
                <div class="payment-methods">
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="cash" id="cash" checked>
                        <label for="cash" class="payment-label">
                            <i class="bi bi-cash-coin"></i>
                            <span>Cash on Delivery</span>
                        </label>
                    </div>
                    
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="bank" id="bank">
                        <label for="bank" class="payment-label">
                            <i class="bi bi-bank"></i>
                            <span>Bank Transfer</span>
                        </label>
                    </div>
                    
                    <div class="payment-option">
                        <input type="radio" name="payment_method" value="card" id="card">
                        <label for="card" class="payment-label">
                            <i class="bi bi-credit-card-2-front"></i>
                            <span>Credit/Debit Card</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="order-summary">
            <div class="order-summary-title">Order Summary</div>
            
            <?php foreach ($cartItems as $item): ?>
            <div class="order-item">
                <img src="<?php echo $item['image'] ? BASE_URL . '/assets/uploads/products/' . $item['image'] : 'https://via.placeholder.com/60?text=Product'; ?>" 
                     alt="<?php echo Security::escape($item['name']); ?>" 
                     class="order-item-image">
                <div class="order-item-details">
                    <div class="order-item-name"><?php echo Security::escape($item['name']); ?></div>
                    <div class="order-item-qty">Qty: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></div>
                </div>
                <div class="order-item-price">
                    $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="summary-totals">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="summary-row">
                    <span>Tax:</span>
                    <span>$0.00</span>
                </div>
                
                <div class="summary-row total">
                    <span>Total:</span>
                    <span class="summary-total-amount">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
            
            <button type="submit" class="place-order-btn">
                <i class="bi bi-check-circle"></i> Place Order
            </button>
            
            <div class="security-note">
                <i class="bi bi-shield-check"></i>
                Your information is secure and encrypted
            </div>
        </div>
    </form>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

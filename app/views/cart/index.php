<?php
/**
 * Shopping Cart Page
 * AgriSmart - Agriculture Marketplace
 */

require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
    .cart-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .cart-header {
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.2);
    }

    .cart-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }

    .cart-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    .cart-items {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .cart-empty {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .cart-empty i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .cart-empty h3 {
        color: #666;
        margin-bottom: 0.5rem;
    }

    .cart-empty p {
        color: #999;
        margin-bottom: 2rem;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        align-items: center;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        background: #f5f5f5;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        color: #333;
    }

    .cart-item-details p {
        margin: 0.25rem 0;
        color: #666;
        font-size: 0.9rem;
    }

    .cart-item-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2e7d32;
    }

    .cart-item-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: flex-end;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f5f5f5;
        border-radius: 8px;
        padding: 0.25rem;
    }

    .quantity-controls button {
        width: 32px;
        height: 32px;
        border: none;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        color: #2e7d32;
        font-size: 1.1rem;
        font-weight: bold;
        transition: all 0.3s;
    }

    .quantity-controls button:hover {
        background: #2e7d32;
        color: white;
    }

    .quantity-controls button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-controls input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #333;
        font-size: 1rem;
    }

    .remove-item-btn {
        background: none;
        border: none;
        color: #d32f2f;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.3s;
    }

    .remove-item-btn:hover {
        background: #ffebee;
    }

    .cart-summary {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        position: sticky;
        top: 2rem;
    }

    .cart-summary h3 {
        margin: 0 0 1.5rem 0;
        font-size: 1.3rem;
        color: #333;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: #666;
    }

    .summary-row.total {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #eee;
    }

    .summary-row.total .amount {
        color: #2e7d32;
    }

    .checkout-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1.5rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
    }

    .continue-shopping {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #2e7d32;
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        border: 2px solid #2e7d32;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .continue-shopping:hover {
        background: #2e7d32;
        color: white;
    }

    @media (max-width: 768px) {
        .cart-content {
            grid-template-columns: 1fr;
        }

        .cart-summary {
            position: static;
        }

        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .cart-item-price {
            grid-column: 2;
            font-size: 1rem;
        }

        .cart-item-actions {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
        }
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <h1><i class="bi bi-cart3"></i> Shopping Cart</h1>
        <p>Review and manage your items before checkout</p>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="cart-empty">
            <i class="bi bi-cart-x"></i>
            <h3>Your cart is empty</h3>
            <p>Start adding products to your cart</p>
            <a href="<?php echo BASE_URL; ?>/products" class="continue-shopping">
                <i class="bi bi-arrow-left"></i> Browse Products
            </a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item" data-cart-id="<?php echo $item['id']; ?>">
                        <div class="cart-item-image">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo BASE_URL; ?>/assets/uploads/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.jpg" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-details">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p><i class="bi bi-tag"></i> <?php echo htmlspecialchars($item['category_name']); ?></p>
                            <?php if ($item['stock'] < 10): ?>
                                <p style="color: #f57c00;">
                                    <i class="bi bi-exclamation-triangle"></i> Only <?php echo $item['stock']; ?> left in stock
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-price">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>

                        <div class="cart-item-actions">
                            <div class="quantity-controls">
                                <button type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, -1, <?php echo $item['stock']; ?>)">-</button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
                                <button type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, 1, <?php echo $item['stock']; ?>)">+</button>
                            </div>
                            <button type="button" class="remove-item-btn" onclick="removeItem(<?php echo $item['id']; ?>)">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div style="margin-top: 2rem;">
                    <a href="<?php echo BASE_URL; ?>/products" class="continue-shopping">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal (<?php echo count($cartItems); ?> items)</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>Calculated at checkout</span>
                </div>
                
                <div class="summary-row total">
                    <span>Total</span>
                    <span class="amount">$<?php echo number_format($total, 2); ?></span>
                </div>

                <a href="<?php echo BASE_URL; ?>/cart/checkout" class="checkout-btn" style="text-decoration: none; display: block; text-align: center;">
                    <i class="bi bi-check-circle"></i> Proceed to Checkout
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(cartId, change, maxStock) {
    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
    const input = cartItem.querySelector('input[type="number"]');
    const currentQty = parseInt(input.value);
    const newQty = currentQty + change;

    if (newQty < 1 || newQty > maxStock) {
        if (newQty > maxStock) {
            alert('Cannot exceed available stock');
        }
        return;
    }

    // Send AJAX request to update quantity
    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('quantity', newQty);
    formData.append('csrf_token', '<?php echo Security::generateCsrfToken(); ?>');

    fetch('<?php echo BASE_URL; ?>/cart/update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update totals
        } else {
            alert(data.message || 'Failed to update quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function removeItem(cartId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }

    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('csrf_token', '<?php echo Security::generateCsrfToken(); ?>');

    fetch('<?php echo BASE_URL; ?>/cart/remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

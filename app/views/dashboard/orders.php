<?php
/**
 * Orders Page - User Dashboard
 * AgriSmart - Agriculture Marketplace
 */

require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
    .orders-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .orders-header {
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.2);
    }

    .orders-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }

    .orders-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .orders-list {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .order-card {
        border: 2px solid #eee;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .order-card:hover {
        border-color: #2e7d32;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .order-id {
        font-weight: 600;
        color: #2e7d32;
        font-size: 1.1rem;
    }

    .order-date {
        color: #666;
        font-size: 0.9rem;
    }

    .order-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3e0;
        color: #f57c00;
    }

    .status-processing {
        background: #e3f2fd;
        color: #1976d2;
    }

    .status-shipped {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .status-delivered {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-cancelled {
        background: #ffebee;
        color: #d32f2f;
    }

    .order-items {
        margin-bottom: 1rem;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .order-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .order-item-image img {
        width: 100%;
        height: 100%;
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
        color: #666;
        font-size: 0.9rem;
    }

    .order-item-price {
        font-weight: 600;
        color: #2e7d32;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .order-total {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }

    .order-total span {
        color: #2e7d32;
    }

    .order-actions {
        display: flex;
        gap: 0.5rem;
    }

    .order-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .order-btn-view {
        background: #f5f5f5;
        color: #333;
    }

    .order-btn-view:hover {
        background: #e0e0e0;
    }

    .order-btn-track {
        background: #2e7d32;
        color: white;
    }

    .order-btn-track:hover {
        background: #1b5e20;
    }

    .order-btn-confirm {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
        color: white;
    }

    .order-btn-confirm:hover {
        background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
    }

    .empty-orders {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-orders i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .empty-orders h3 {
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-orders p {
        color: #999;
        margin-bottom: 2rem;
    }

    .browse-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .browse-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination a,
    .pagination span {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: #2e7d32;
        color: white;
        border-color: #2e7d32;
    }

    .pagination .active {
        background: #2e7d32;
        color: white;
        border-color: #2e7d32;
    }

    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .order-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .order-item {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="orders-container">
    <div class="orders-header">
        <h1><i class="bi bi-box-seam"></i> My Orders</h1>
        <p>Track and manage your orders</p>
    </div>

    <div class="orders-list">
        <?php if (empty($orders['data'])): ?>
            <div class="empty-orders">
                <i class="bi bi-inbox"></i>
                <h3>No orders yet</h3>
                <p>You haven't placed any orders yet</p>
                <a href="<?php echo BASE_URL; ?>/products" class="browse-btn">
                    <i class="bi bi-shop"></i> Start Shopping
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($orders['data'] as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></div>
                            <div class="order-date">
                                <i class="bi bi-calendar"></i> 
                                <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                        <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                    </div>

                    <div class="order-items">
                        <?php 
                        // Display order items
                        if (!empty($order['items'])):
                            foreach ($order['items'] as $item): 
                        ?>
                            <div class="order-item">
                                <div class="order-item-image">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?php echo BASE_URL; ?>/assets/uploads/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.jpg" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="order-item-details">
                                    <div class="order-item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                    <div class="order-item-qty">Quantity: <?php echo $item['quantity']; ?></div>
                                </div>
                                <div class="order-item-price">
                                    $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="order-footer">
                        <div class="order-total">
                            Total: <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                        <div class="order-actions">
                            <?php if (!empty($order['shipping_address'])): ?>
                                <button class="order-btn order-btn-view" onclick="viewShippingDetails(<?php echo $order['id']; ?>, '<?php echo htmlspecialchars($order['shipping_address'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($order['shipping_phone'] ?? '', ENT_QUOTES); ?>')">
                                    <i class="bi bi-geo-alt"></i> Shipping Details
                                </button>
                            <?php endif; ?>
                            
                            <?php 
                            // Show different actions for sellers vs buyers
                            $user = AuthMiddleware::user();
                            if ($user['role'] === 'buyer'): 
                                // Seller actions - confirm/update order status
                                if ($order['status'] === 'pending'): ?>
                                    <button class="order-btn order-btn-confirm" onclick="confirmOrder(<?php echo $order['id']; ?>, 'processing')">
                                        <i class="bi bi-check-circle"></i> Confirm Order
                                    </button>
                                <?php elseif ($order['status'] === 'processing'): ?>
                                    <button class="order-btn order-btn-confirm" onclick="confirmOrder(<?php echo $order['id']; ?>, 'shipped')">
                                        <i class="bi bi-truck"></i> Mark as Shipped
                                    </button>
                                <?php elseif ($order['status'] === 'shipped'): ?>
                                    <button class="order-btn order-btn-confirm" onclick="confirmOrder(<?php echo $order['id']; ?>, 'delivered')">
                                        <i class="bi bi-box-seam"></i> Mark as Delivered
                                    </button>
                                <?php endif;
                            else:
                                // Buyer actions - track order
                                if (in_array($order['status'], ['processing', 'shipped'])): ?>
                                    <a href="#" class="order-btn order-btn-track">
                                        <i class="bi bi-truck"></i> Track Order
                                    </a>
                                <?php endif;
                            endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?php if ($orders['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($orders['current_page'] > 1): ?>
                        <a href="?page=<?php echo $orders['current_page'] - 1; ?>">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $orders['total_pages']; $i++): ?>
                        <?php if ($i == $orders['current_page']): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($orders['current_page'] < $orders['total_pages']): ?>
                        <a href="?page=<?php echo $orders['current_page'] + 1; ?>">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Shipping Details Modal -->
<div class="modal-overlay" id="shippingModal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="bi bi-geo-alt"></i> Shipping Details</h3>
            <button class="modal-close" onclick="closeShippingModal()">&times;</button>
        </div>
        <div class="modal-body" id="shippingDetails">
            <!-- Shipping details will be loaded here -->
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 2px solid #eee;
    }

    .modal-header h3 {
        margin: 0;
        color: #2e7d32;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #999;
        cursor: pointer;
        line-height: 1;
    }

    .modal-close:hover {
        color: #333;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .shipping-info {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 10px;
    }

    .shipping-info p {
        margin: 0.5rem 0;
        color: #666;
    }

    .shipping-info strong {
        color: #333;
    }
</style>

<script>
function viewShippingDetails(orderId, address, phone) {
    const modal = document.getElementById('shippingModal');
    const detailsDiv = document.getElementById('shippingDetails');
    
    detailsDiv.innerHTML = `
        <div class="shipping-info">
            <p><strong>Order ID:</strong> #${String(orderId).padStart(6, '0')}</p>
            <p><strong><i class="bi bi-geo-alt"></i> Shipping Address:</strong><br>${address || 'Not provided'}</p>
            <p><strong><i class="bi bi-telephone"></i> Contact Phone:</strong> ${phone || 'Not provided'}</p>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function confirmOrder(orderId, newStatus) {
    const statusMessages = {
        'processing': 'confirm this order',
        'shipped': 'mark this order as shipped',
        'delivered': 'mark this order as delivered'
    };
    
    const statusLabels = {
        'processing': 'Confirmed',
        'shipped': 'Shipped',
        'delivered': 'Delivered'
    };
    
    if (!confirm(`Are you sure you want to ${statusMessages[newStatus]}?`)) {
        return;
    }
    
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>/dashboard/update-order-status';
    
    const orderIdInput = document.createElement('input');
    orderIdInput.type = 'hidden';
    orderIdInput.name = 'order_id';
    orderIdInput.value = orderId;
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = newStatus;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = 'csrf_token';
    csrfInput.value = '<?php echo Security::getCsrfToken(); ?>';
    
    form.appendChild(orderIdInput);
    form.appendChild(statusInput);
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
}

function closeShippingModal() {
    document.getElementById('shippingModal').style.display = 'none';
}

// Close modal on overlay click
document.getElementById('shippingModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeShippingModal();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

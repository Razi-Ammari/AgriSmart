<?php
$pageTitle = 'Buyer Dashboard - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="dashboard">
    <aside class="sidebar">
        <h5 class="mb-4">Seller Dashboard</h5>
        <ul class="sidebar-nav">
            <li><a href="<?php echo BASE_URL; ?>/dashboard" class="sidebar-link active">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/dashboard/products" class="sidebar-link">
                <i class="bi bi-box-seam"></i> My Products
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/products/create" class="sidebar-link">
                <i class="bi bi-plus-circle"></i> Add Product
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/dashboard/orders" class="sidebar-link">
                <i class="bi bi-bag"></i> Orders
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/dashboard/profile" class="sidebar-link">
                <i class="bi bi-person"></i> Profile
            </a></li>
        </ul>
    </aside>
    
    <main class="dashboard-content">
        <h2 class="mb-4">Seller Dashboard 📊</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value counter"><?php echo $stats['total_products'] ?? 0; ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                <div class="stat-value counter"><?php echo $stats['order_stats']['total_orders'] ?? 0; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="stat-value">$<?php echo number_format($stats['order_stats']['total_revenue'] ?? 0, 2); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="stat-value counter"><?php echo $stats['order_stats']['pending_orders'] ?? 0; ?></div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-box"></i> My Products</h5>
                        <?php if (!empty($myProducts) && count($myProducts) > 5): ?>
                            <a href="<?php echo BASE_URL; ?>/dashboard/products" class="btn btn-sm btn-outline-primary">
                                View All (<?php echo count($myProducts); ?>)
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($myProducts)): ?>
                        <div class="list-group">
                            <?php foreach (array_slice($myProducts, 0, 5) as $product): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo Security::escape($product['name']); ?></strong>
                                    <br>
                                    <small class="text-muted">$<?php echo number_format($product['price'], 2); ?> | Stock: <?php echo $product['stock_quantity']; ?></small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo BASE_URL; ?>/products/edit?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmProductDelete(<?php echo $product['id']; ?>, '<?php echo Security::escape($product['name']); ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($myProducts) > 5): ?>
                            <a href="<?php echo BASE_URL; ?>/dashboard/products" class="btn btn-outline-primary w-100 mt-3">
                                <i class="bi bi-grid"></i> View All <?php echo count($myProducts); ?> Products
                            </a>
                        <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/products/create" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-plus-circle"></i> Add New Product
                        </a>
                        <?php endif; ?>
                        <?php else: ?>
                        <p class="text-center text-muted py-4">No products yet.</p>
                        <a href="<?php echo BASE_URL; ?>/products/create" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Add Your First Product
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bag"></i> Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($myOrders)): ?>
                        <div class="list-group">
                            <?php foreach (array_slice($myOrders, 0, 5) as $order): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong><?php echo Security::escape($order['order_number']); ?></strong>
                                    <span class="badge badge-<?php echo $order['status'] === 'delivered' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?> | 
                                    Customer: <?php echo Security::escape($order['customer_name']); ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-center text-muted py-4">No orders yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Delete Modal -->
<div id="modalOverlay" class="modal-overlay">
    <div class="modal-box">
        <div id="modalIcon" class="modal-icon"></div>
        <h3 id="modalTitle" class="modal-title"></h3>
        <p id="modalBody" class="modal-body"></p>
        <div id="modalFooter" class="modal-footer"></div>
    </div>
</div>

<!-- Hidden delete form -->
<form id="deleteForm" method="POST" action="<?php echo BASE_URL; ?>/products/delete" style="display: none;">
    <?php echo Security::csrfField(); ?>
    <input type="hidden" name="product_id" id="deleteProductId">
</form>

<style>
.d-flex { display: flex; }
.gap-2 { gap: 0.5rem; }
.btn-danger {
    background: #d32f2f;
    color: white;
    border: none;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.2s;
}
.btn-danger:hover {
    background: #b71c1c;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(211, 47, 47, 0.3);
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
    animation: fadeIn 0.2s ease-out;
}

.modal-box {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
    text-align: center;
}

.modal-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.modal-icon.success { background: #e8f5e9; color: #2e7d32; }
.modal-icon.error { background: #ffebee; color: #d32f2f; }
.modal-icon.warning { background: #fff3e0; color: #f57c00; }
.modal-icon.info { background: #e3f2fd; color: #1976d2; }

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.modal-body {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.modal-footer {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.modal-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-btn-primary {
    background: #2e7d32;
    color: white;
}

.modal-btn-primary:hover {
    background: #1b5e20;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.modal-btn-danger {
    background: #d32f2f;
    color: white;
}

.modal-btn-danger:hover {
    background: #b71c1c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
}

.modal-btn-secondary {
    background: #f5f5f5;
    color: #666;
}

.modal-btn-secondary:hover {
    background: #e0e0e0;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
const modal = {
    overlay: null,
    icon: null,
    title: null,
    body: null,
    footer: null,
    
    init() {
        this.overlay = document.getElementById('modalOverlay');
        this.icon = document.getElementById('modalIcon');
        this.title = document.getElementById('modalTitle');
        this.body = document.getElementById('modalBody');
        this.footer = document.getElementById('modalFooter');
        
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) this.hide();
        });
    },
    
    show(type, title, message, buttons) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        
        this.icon.className = 'modal-icon ' + type;
        this.icon.textContent = icons[type] || icons.info;
        this.title.textContent = title;
        this.body.textContent = message;
        
        this.footer.innerHTML = '';
        buttons.forEach(btn => {
            const button = document.createElement('button');
            button.className = 'modal-btn modal-btn-' + btn.type;
            button.textContent = btn.text;
            button.onclick = btn.action;
            this.footer.appendChild(button);
        });
        
        this.overlay.classList.add('active');
    },
    
    hide() {
        this.overlay.classList.remove('active');
    },
    
    confirm(title, message, onConfirm) {
        this.show('warning', title, message, [
            {
                type: 'secondary',
                text: 'Cancel',
                action: () => this.hide()
            },
            {
                type: 'danger',
                text: 'Confirm',
                action: () => {
                    this.hide();
                    onConfirm();
                }
            }
        ]);
    },
    
    success(title, message) {
        this.show('success', title, message, [
            {
                type: 'primary',
                text: 'OK',
                action: () => this.hide()
            }
        ]);
    },
    
    error(title, message) {
        this.show('error', title, message, [
            {
                type: 'primary',
                text: 'OK',
                action: () => this.hide()
            }
        ]);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    modal.init();
});

function confirmProductDelete(productId, productName) {
    modal.confirm(
        'Delete Product',
        `Are you sure you want to delete "${productName}"? This action cannot be undone.`,
        () => {
            document.getElementById('deleteProductId').value = productId;
            document.getElementById('deleteForm').submit();
        }
    );
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

<?php
$pageTitle = 'Admin Dashboard - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
/* Professional Admin Theme - Dark Blue */
.admin-dashboard {
    display: flex;
    min-height: calc(100vh - 120px);
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
}

.admin-sidebar {
    width: 260px;
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    padding: 30px 0;
    position: sticky;
    top: 80px;
    height: fit-content;
}

.admin-sidebar-header {
    padding: 0 25px 20px;
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 20px;
}

.admin-sidebar-header h5 {
    color: #1e3c72;
    font-weight: 700;
    margin: 0;
    font-size: 18px;
}

.admin-sidebar-header .admin-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    margin-top: 5px;
}

.admin-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.admin-nav li {
    margin: 0;
}

.admin-nav-link {
    display: flex;
    align-items: center;
    padding: 14px 25px;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    border-left: 3px solid transparent;
}

.admin-nav-link:hover {
    background: rgba(102, 126, 234, 0.08);
    color: #667eea;
    border-left-color: #667eea;
}

.admin-nav-link.active {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.15) 0%, transparent 100%);
    color: #667eea;
    border-left-color: #667eea;
}

.admin-nav-link i {
    font-size: 18px;
    width: 24px;
    margin-right: 12px;
}

.admin-content {
    flex: 1;
    padding: 30px 40px;
}

.admin-header {
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.admin-header h2 {
    margin: 0;
    color: #1e3c72;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-header .header-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
}

/* Enhanced Stats Cards */
.admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.admin-stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.admin-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.admin-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.admin-stat-card.users::before {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.admin-stat-card.products::before {
    background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
}

.admin-stat-card.orders::before {
    background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
}

.admin-stat-card.revenue::before {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
}

.stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin-bottom: 15px;
}

.admin-stat-card.users .stat-icon {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
    color: #667eea;
}

.admin-stat-card.products .stat-icon {
    background: linear-gradient(135deg, rgba(240, 147, 251, 0.2) 0%, rgba(245, 87, 108, 0.2) 100%);
    color: #f5576c;
}

.admin-stat-card.orders .stat-icon {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.2) 0%, rgba(0, 242, 254, 0.2) 100%);
    color: #4facfe;
}

.admin-stat-card.revenue .stat-icon {
    background: linear-gradient(135deg, rgba(67, 233, 123, 0.2) 0%, rgba(56, 249, 215, 0.2) 100%);
    color: #43e97b;
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #212529;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.stat-change {
    font-size: 13px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-change.positive {
    color: #28a745;
}

.stat-change.negative {
    color: #dc3545;
}

/* Data Cards */
.admin-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
}

.admin-card-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-card-header h5 {
    margin: 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.admin-card-body {
    padding: 25px;
}

.admin-table {
    width: 100%;
    margin: 0;
}

.admin-table thead {
    background: #f8f9fa;
}

.admin-table th {
    padding: 14px 16px;
    font-weight: 600;
    color: #495057;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.admin-table td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.admin-table tbody tr {
    transition: all 0.2s ease;
}

.admin-table tbody tr:hover {
    background: #f8f9fa;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.user-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.product-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.badge-admin {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge-admin.role-admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.badge-admin.role-buyer {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.badge-admin.role-user {
    background: #e9ecef;
    color: #495057;
}

.badge-admin.status-pending {
    background: #fff3cd;
    color: #856404;
}

.badge-admin.status-processing {
    background: #cfe2ff;
    color: #084298;
}

.badge-admin.status-shipped {
    background: #d1e7dd;
    color: #0a3622;
}

.badge-admin.status-delivered {
    background: #d1e7dd;
    color: #0a3622;
}

.stock-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.stock-badge.in-stock {
    background: #d1f2eb;
    color: #0c5460;
}

.stock-badge.low-stock {
    background: #fff3cd;
    color: #856404;
}

.stock-badge.out-of-stock {
    background: #f8d7da;
    color: #721c24;
}

.admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.product-card-admin {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.product-card-admin:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.product-card-admin img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-card-admin .card-body {
    padding: 15px;
}

.product-card-admin .product-title {
    font-weight: 600;
    color: #212529;
    margin-bottom: 8px;
    font-size: 15px;
}

.product-card-admin .product-seller {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 10px;
}

.product-card-admin .product-price {
    font-size: 18px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 10px;
}

.chart-container {
    height: 300px;
    margin-top: 20px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 10px;
}

.pagination-admin {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 25px;
}

.pagination-admin a,
.pagination-admin span {
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    background: #f8f9fa;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination-admin a:hover {
    background: #667eea;
    color: white;
}

.pagination-admin .active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
</style>

<div class="admin-dashboard">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h5><i class="bi bi-shield-lock-fill"></i> Admin Panel</h5>
            <span class="admin-badge">SUPER ADMIN</span>
        </div>
        <ul class="admin-nav">
            <li>
                <a href="#overview" class="admin-nav-link active" onclick="showSection('overview'); return false;">
                    <i class="bi bi-speedometer2"></i> Overview
                </a>
            </li>
            <li>
                <a href="#users" class="admin-nav-link" onclick="showSection('users'); return false;">
                    <i class="bi bi-people-fill"></i> Users
                </a>
            </li>
            <li>
                <a href="#products" class="admin-nav-link" onclick="showSection('products'); return false;">
                    <i class="bi bi-box-seam-fill"></i> Products
                </a>
            </li>
            <li>
                <a href="#orders" class="admin-nav-link" onclick="showSection('orders'); return false;">
                    <i class="bi bi-bag-check-fill"></i> Orders
                </a>
            </li>
        </ul>
    </aside>
    
    <main class="admin-content">
        <!-- Overview Section -->
        <div id="section-overview" class="admin-section">
            <div class="admin-header">
                <h2>
                    <div class="header-icon"><i class="bi bi-grid-fill"></i></div>
                    Dashboard Overview
                </h2>
            </div>
            
            <!-- Statistics Cards -->
            <div class="admin-stats-grid">
                <div class="admin-stat-card users">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo count($allUsers ?? []); ?></div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up-short"></i> Active accounts
                        </div>
                    </div>
                </div>
                
                <div class="admin-stat-card products">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo count($allProducts ?? []); ?></div>
                        <div class="stat-label">Total Products</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up-short"></i> Listed products
                        </div>
                    </div>
                </div>
                
                <div class="admin-stat-card orders">
                    <div class="stat-icon">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-change">
                            <?php 
                            $pendingOrders = 0;
                            if (!empty($stats['order_stats']['by_status'])) {
                                foreach ($stats['order_stats']['by_status'] as $status) {
                                    if ($status['status'] === 'pending') {
                                        $pendingOrders = $status['count'];
                                        break;
                                    }
                                }
                            }
                            ?>
                            <i class="bi bi-clock"></i> <?php echo $pendingOrders; ?> pending
                        </div>
                    </div>
                </div>
                
                <div class="admin-stat-card revenue">
                    <div class="stat-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value">$<?php echo number_format($stats['order_stats']['total_revenue'] ?? 0, 0); ?></div>
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-change positive">
                            <i class="bi bi-graph-up"></i> Gross earnings
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-clock-history"></i> Recent Orders</h5>
                    <span style="font-size: 13px; opacity: 0.9;">Last 5 orders</span>
                </div>
                <div class="admin-card-body">
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo Security::escape($order['order_number']); ?></strong></td>
                                        <td><?php echo Security::escape($order['customer_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                        <td>
                                            <span class="badge-admin status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo isset($order['items']) ? count($order['items']) : 0; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h4>No Orders Yet</h4>
                            <p>Orders will appear here once customers make purchases</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Users Section -->
        <div id="section-users" class="admin-section" style="display: none;">
            <div class="admin-header">
                <h2>
                    <div class="header-icon"><i class="bi bi-people-fill"></i></div>
                    User Management
                </h2>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-list-ul"></i> All Users</h5>
                    <span style="font-size: 13px; opacity: 0.9;"><?php echo count($allUsers ?? []); ?> registered users</span>
                </div>
                <div class="admin-card-body">
                    <?php if (!empty($allUsers)): ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allUsers as $user): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <?php if (!empty($user['profile_image'])): ?>
                                                    <img src="<?php echo BASE_URL; ?>/assets/uploads/profiles/<?php echo Security::escape($user['profile_image']); ?>" 
                                                         alt="Profile" class="user-avatar">
                                                <?php else: ?>
                                                    <div class="user-avatar-placeholder">
                                                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <strong><?php echo Security::escape($user['full_name']); ?></strong>
                                            </div>
                                        </td>
                                        <td><?php echo Security::escape($user['email']); ?></td>
                                        <td>
                                            <span class="badge-admin role-<?php echo $user['role']; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <?php if ($user['is_active']): ?>
                                                <span class="badge-admin" style="background: #d1f2eb; color: #0c5460;">
                                                    <i class="bi bi-check-circle-fill"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-admin" style="background: #f8d7da; color: #721c24;">
                                                    <i class="bi bi-x-circle-fill"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                            <?php if (empty($user['email_verified'])): ?>
                                                <span class="badge-admin" style="background: #fff3cd; color: #856404; margin-left: 5px;">
                                                    <i class="bi bi-exclamation-circle"></i> Unverified
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 8px;">
                                                <?php if (empty($user['email_verified'])): ?>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/dashboard/verify-user" style="display: inline;">
                                                    <input type="hidden" name="csrf_token" value="<?php echo Security::getCsrfToken(); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="btn btn-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                                        <i class="bi bi-check-circle"></i> Verify
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/dashboard/toggle-user-status" style="display: inline;">
                                                    <input type="hidden" name="csrf_token" value="<?php echo Security::getCsrfToken(); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <input type="hidden" name="status" value="<?php echo $user['is_active'] ? 0 : 1; ?>">
                                                    <button type="submit" class="btn btn-sm" style="background: <?php echo $user['is_active'] ? '#dc3545' : '#28a745'; ?>; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                                        <i class="bi bi-<?php echo $user['is_active'] ? 'x-circle' : 'check-circle'; ?>"></i> 
                                                        <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h4>No Users Found</h4>
                            <p>User accounts will appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Products Section -->
        <div id="section-products" class="admin-section" style="display: none;">
            <div class="admin-header">
                <h2>
                    <div class="header-icon"><i class="bi bi-box-seam-fill"></i></div>
                    Product Management
                </h2>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-grid-3x3-gap-fill"></i> All Products</h5>
                    <span style="font-size: 13px; opacity: 0.9;"><?php echo count($allProducts ?? []); ?> products listed</span>
                </div>
                <div class="admin-card-body">
                    <?php if (!empty($allProducts)): ?>
                        <div class="admin-grid">
                            <?php foreach ($allProducts as $product): ?>
                            <div class="product-card-admin">
                                <?php 
                                $imagePath = !empty($product['image']) ? BASE_URL . '/assets/uploads/products/' . $product['image'] : BASE_URL . '/assets/images/placeholder.jpg';
                                ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo Security::escape($product['name']); ?>">
                                <div class="card-body">
                                    <div class="product-title"><?php echo Security::escape($product['name']); ?></div>
                                    <div class="product-seller">
                                        <i class="bi bi-shop"></i> <?php echo Security::escape($product['seller_name'] ?? 'Unknown Seller'); ?>
                                    </div>
                                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php 
                                        $stock = $product['stock_quantity'];
                                        if ($stock > 20): 
                                        ?>
                                            <span class="stock-badge in-stock">In Stock (<?php echo $stock; ?>)</span>
                                        <?php elseif ($stock > 0): ?>
                                            <span class="stock-badge low-stock">Low (<?php echo $stock; ?>)</span>
                                        <?php else: ?>
                                            <span class="stock-badge out-of-stock">Out of Stock</span>
                                        <?php endif; ?>
                                        
                                        <small class="text-muted">
                                            <i class="bi bi-tag"></i> <?php echo Security::escape($product['category_name'] ?? 'N/A'); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <h4>No Products Listed</h4>
                            <p>Products from sellers will appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Orders Section -->
        <div id="section-orders" class="admin-section" style="display: none;">
            <div class="admin-header">
                <h2>
                    <div class="header-icon"><i class="bi bi-bag-check-fill"></i></div>
                    Order Management
                </h2>
            </div>
            
            <!-- Order Statistics -->
            <div class="row mb-4">
                <?php if (!empty($stats['order_stats']['by_status'])): ?>
                    <?php foreach ($stats['order_stats']['by_status'] as $statusData): ?>
                    <div class="col-md-3 mb-3">
                        <div class="admin-stat-card">
                            <div class="stat-value"><?php echo $statusData['count']; ?></div>
                            <div class="stat-label"><?php echo ucfirst($statusData['status']); ?> Orders</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-list-check"></i> All Orders</h5>
                    <span style="font-size: 13px; opacity: 0.9;">Complete order history</span>
                </div>
                <div class="admin-card-body">
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo Security::escape($order['order_number']); ?></strong></td>
                                        <td><?php echo Security::escape($order['customer_name']); ?></td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></td>
                                        <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                        <td>
                                            <span class="badge-admin" style="background: #e9ecef; color: #495057;">
                                                <?php echo ucfirst($order['payment_method'] ?? 'N/A'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-admin status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo isset($order['items']) ? count($order['items']) : 0; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="bi bi-bag-x"></i>
                            <h4>No Orders Yet</h4>
                            <p>Customer orders will appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.admin-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Remove active class from all nav links
    document.querySelectorAll('.admin-nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById('section-' + sectionName).style.display = 'block';
    
    // Add active class to clicked nav link
    event.target.closest('.admin-nav-link').classList.add('active');
}

// Counter animation for stats
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-value');
    
    counters.forEach(counter => {
        const target = parseInt(counter.innerText.replace(/[^0-9]/g, ''));
        if (isNaN(target)) return;
        
        const duration = 1500;
        const increment = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.innerText = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        
        updateCounter();
    });
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

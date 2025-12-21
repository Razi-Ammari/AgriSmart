<?php
$pageTitle = 'Dashboard - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="dashboard">
    <aside class="sidebar">
        <h5 class="mb-4">User Dashboard</h5>
        <ul class="sidebar-nav">
            <li><a href="<?php echo BASE_URL; ?>/dashboard" class="sidebar-link active">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/dashboard/profile" class="sidebar-link">
                <i class="bi bi-person"></i> Profile
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/dashboard/orders" class="sidebar-link">
                <i class="bi bi-bag"></i> My Orders
            </a></li>
            <li><a href="<?php echo BASE_URL; ?>/cart" class="sidebar-link">
                <i class="bi bi-cart"></i> Shopping Cart
            </a></li>
        </ul>
    </aside>
    
    <main class="dashboard-content">
        <h2 class="mb-4">Welcome, <?php echo Security::escape($user['full_name']); ?>! 👋</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value counter"><?php echo $stats['total_orders'] ?? 0; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                <div class="stat-value">$<?php echo number_format($stats['total_spent'] ?? 0, 2); ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="stat-value counter"><?php echo $stats['total_reviews'] ?? 0; ?></div>
                <div class="stat-label">Reviews Given</div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Orders</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($myOrders)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myOrders as $order): ?>
                            <tr>
                                <td><strong><?php echo Security::escape($order['order_number']); ?></strong></td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $order['status'] === 'delivered' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/dashboard/orders" class="btn btn-sm btn-outline">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-center text-muted py-4">No orders yet. <a href="<?php echo BASE_URL; ?>/products">Start shopping!</a></p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

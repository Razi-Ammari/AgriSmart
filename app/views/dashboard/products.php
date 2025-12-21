<?php
/**
 * My Products Page - Seller Dashboard
 * AgriSmart - Agriculture Marketplace
 */

require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
    .products-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .products-header {
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .products-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }

    .products-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-add-product {
        background: white;
        color: #2e7d32;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-add-product:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .product-image {
        width: 100%;
        height: 200px;
        background: #f5f5f5;
        position: relative;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(46, 125, 50, 0.9);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .product-badge.low-stock {
        background: rgba(255, 152, 0, 0.9);
    }

    .product-badge.out-of-stock {
        background: rgba(211, 47, 47, 0.9);
    }

    .product-content {
        padding: 1.25rem;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin: 0 0 0.5rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #666;
    }

    .product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2e7d32;
        margin-bottom: 1rem;
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit {
        flex: 1;
        background: #2e7d32;
        color: white;
        border: none;
        padding: 0.6rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
    }

    .btn-edit:hover {
        background: #1b5e20;
        color: white;
    }

    .btn-delete {
        background: #d32f2f;
        color: white;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-delete:hover {
        background: #b71c1c;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .empty-state i {
        font-size: 5rem;
        color: #ccc;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #999;
        margin-bottom: 2rem;
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
        .products-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="products-container">
    <div class="products-header">
        <div>
            <h1><i class="bi bi-box-seam"></i> My Products</h1>
            <p>Manage your product listings</p>
        </div>
        <div class="header-actions">
            <a href="<?php echo BASE_URL; ?>/dashboard" class="btn-add-product" style="background: rgba(255,255,255,0.2); color: white;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>/products/create" class="btn-add-product">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No products listed yet</h3>
            <p>Start selling by adding your first product</p>
            <a href="<?php echo BASE_URL; ?>/products/create" class="btn-add-product" style="display: inline-flex;">
                <i class="bi bi-plus-circle"></i> Add Your First Product
            </a>
        </div>
    <?php else: ?>
        <div style="background: white; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <strong>Total Products: <?php echo $total; ?></strong>
        </div>

        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo BASE_URL; ?>/assets/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.jpg" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php endif; ?>
                        
                        <?php if ($product['stock_quantity'] <= 0): ?>
                            <span class="product-badge out-of-stock">Out of Stock</span>
                        <?php elseif ($product['stock_quantity'] < 10): ?>
                            <span class="product-badge low-stock">Low Stock</span>
                        <?php else: ?>
                            <span class="product-badge">In Stock</span>
                        <?php endif; ?>
                    </div>

                    <div class="product-content">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        
                        <div class="product-meta">
                            <span><i class="bi bi-tag"></i> <?php echo htmlspecialchars($product['category_name']); ?></span>
                            <span><i class="bi bi-box"></i> Stock: <?php echo $product['stock_quantity']; ?></span>
                        </div>

                        <div class="product-price">
                            $<?php echo number_format($product['price'], 2); ?>
                        </div>

                        <div class="product-actions">
                            <a href="<?php echo BASE_URL; ?>/products/view?slug=<?php echo $product['slug']; ?>" 
                               class="btn-edit" style="background: #1976d2;">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="<?php echo BASE_URL; ?>/products/edit?id=<?php echo $product['id']; ?>" 
                               class="btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button" 
                                    class="btn-delete" 
                                    onclick="confirmProductDelete(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?php echo $currentPage - 1; ?>">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?php echo $currentPage + 1; ?>">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
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
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-box {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    max-width: 400px;
    width: 90%;
    text-align: center;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.modal-title {
    margin: 0 0 1rem 0;
    color: #333;
}

.modal-body {
    color: #666;
    margin-bottom: 1.5rem;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.modal-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.modal-btn-confirm {
    background: #d32f2f;
    color: white;
}

.modal-btn-confirm:hover {
    background: #b71c1c;
}

.modal-btn-cancel {
    background: #e0e0e0;
    color: #333;
}

.modal-btn-cancel:hover {
    background: #bdbdbd;
}
</style>

<script>
function confirmProductDelete(productId, productName) {
    const modal = document.getElementById('modalOverlay');
    const icon = document.getElementById('modalIcon');
    const title = document.getElementById('modalTitle');
    const body = document.getElementById('modalBody');
    const footer = document.getElementById('modalFooter');
    
    icon.innerHTML = '<i class="bi bi-exclamation-triangle-fill" style="color: #f57c00;"></i>';
    title.textContent = 'Delete Product?';
    body.innerHTML = `Are you sure you want to delete "<strong>${productName}</strong>"?<br>This action cannot be undone.`;
    footer.innerHTML = `
        <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
        <button type="button" class="modal-btn modal-btn-confirm" onclick="deleteProduct(${productId})">
            <i class="bi bi-trash"></i> Delete
        </button>
    `;
    
    modal.classList.add('active');
}

function deleteProduct(productId) {
    document.getElementById('deleteProductId').value = productId;
    document.getElementById('deleteForm').submit();
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

// Close modal on overlay click
document.getElementById('modalOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

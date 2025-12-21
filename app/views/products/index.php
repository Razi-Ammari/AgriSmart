<?php
$pageTitle = 'Products - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container py-5">
    
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>/products">
                        <div class="mb-4">
                            <h6 class="mb-3">Categories</h6>
                            <?php foreach ($categories as $cat): ?>
                            <div class="form-check mb-2">
                                <input type="radio" class="form-check-input" name="category" 
                                       value="<?php echo $cat['id']; ?>" 
                                       id="cat<?php echo $cat['id']; ?>"
                                       <?php echo (($_GET['category'] ?? '') == $cat['id']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="cat<?php echo $cat['id']; ?>">
                                    <?php echo Security::escape($cat['name']); ?>
                                    <span class="text-muted">(<?php echo $cat['products_count']; ?>)</span>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="mb-3">Price Range</h6>
                            <input type="number" class="form-control mb-2" name="min_price" 
                                   placeholder="Min Price" value="<?php echo $_GET['min_price'] ?? ''; ?>">
                            <input type="number" class="form-control" name="max_price" 
                                   placeholder="Max Price" value="<?php echo $_GET['max_price'] ?? ''; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline w-100 mt-2">Clear</a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Products</h2>
                <div class="d-flex gap-2">
                    <select class="form-control" id="sortSelect" style="width: auto;">
                        <option value="latest" <?php echo (($_GET['sort'] ?? 'latest') === 'latest') ? 'selected' : ''; ?>>Latest</option>
                        <option value="price_asc" <?php echo (($_GET['sort'] ?? '') === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo (($_GET['sort'] ?? '') === 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="popular" <?php echo (($_GET['sort'] ?? '') === 'popular') ? 'selected' : ''; ?>>Most Popular</option>
                    </select>
                </div>
            </div>
            
            <div class="product-grid">
                <?php foreach ($products['data'] as $product): ?>
                <div class="card product-card">
                    <?php if ($product['is_featured']): ?>
                    <span class="product-badge">Featured</span>
                    <?php endif; ?>
                    
                    <div style="overflow: hidden;">
                        <img src="<?php echo $product['image'] ? BASE_URL . '/assets/uploads/products/' . $product['image'] : 'https://via.placeholder.com/400x300?text=' . urlencode($product['name']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo Security::escape($product['name']); ?>">
                    </div>
                    
                    <div class="card-body">
                        <div class="product-category">
                            <i class="bi bi-tag"></i>
                            <?php echo Security::escape($product['category_name']); ?>
                        </div>
                        <h5 class="product-title">
                            <?php echo Security::escape($product['name']); ?>
                        </h5>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-person"></i> <?php echo Security::escape($product['seller_name']); ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">
                                $<?php echo number_format($product['price'], 2); ?>
                            </span>
                            <a href="<?php echo BASE_URL; ?>/products/view?id=<?php echo $product['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($products['data'])): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: var(--text-light);"></i>
                <p class="text-secondary mt-3">No products found</p>
            </div>
            <?php endif; ?>
            
            <!-- Pagination -->
            <?php if ($products['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php 
                    $queryParams = [];
                    if (isset($_GET['category'])) $queryParams[] = 'category=' . $_GET['category'];
                    if (isset($_GET['sort'])) $queryParams[] = 'sort=' . $_GET['sort'];
                    if (isset($_GET['min_price'])) $queryParams[] = 'min_price=' . $_GET['min_price'];
                    if (isset($_GET['max_price'])) $queryParams[] = 'max_price=' . $_GET['max_price'];
                    $queryString = !empty($queryParams) ? '&' . implode('&', $queryParams) : '';
                    ?>
                    <?php for ($i = 1; $i <= $products['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i == $products['page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo $queryString; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Handle sort dropdown
document.getElementById('sortSelect')?.addEventListener('change', function() {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', this.value);
    urlParams.delete('page'); // Reset to page 1 when sorting
    window.location.search = urlParams.toString();
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

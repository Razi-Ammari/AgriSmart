<?php
$pageTitle = 'Home - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content container">
        <h1>🌱 Fresh from Farm to Your Table</h1>
        <p>Discover quality agricultural products from trusted local farmers</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-primary btn-lg">
                <i class="bi bi-grid"></i> Browse Products
            </a>
            <?php if (!AuthMiddleware::check()): ?>
            <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-accent btn-lg">
                <i class="bi bi-person-plus"></i> Join Now
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- AI Recommendations Banner -->
<section class="py-4" style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 style="color: white; margin-bottom: 0.5rem; font-weight: 600;">
                    <i class="bi bi-stars"></i> New: Smart AI Plant Recommendations
                </h3>
                <p style="color: rgba(255,255,255,0.9); margin-bottom: 0; font-size: 1.05rem;">
                    Tell us your growing conditions and get personalized plant recommendations matched to your environment
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?php echo BASE_URL; ?>/products/recommendations" 
                   class="btn btn-light btn-lg" 
                   style="font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    <i class="bi bi-magic"></i> Try It Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>⭐ Featured Products</h2>
            <p class="text-secondary">Handpicked selection of premium agricultural products</p>
        </div>
        
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="product-price">
                            $<?php echo number_format($product['price'], 2); ?>
                            <small class="text-muted">/ <?php echo $product['unit']; ?></small>
                        </span>
                        <a href="<?php echo BASE_URL; ?>/products/view?id=<?php echo $product['id']; ?>" 
                           class="btn btn-primary btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($featuredProducts)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-light);"></i>
                <p class="text-secondary mt-3">No featured products available</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline">
                View All Products <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>🏷️ Shop by Category</h2>
            <p class="text-secondary">Explore our wide range of agricultural products</p>
        </div>
        
        <div class="row g-4">
            <?php foreach (array_slice($categories, 0, 6) as $category): ?>
            <div class="col-md-4 col-lg-2">
                <a href="<?php echo BASE_URL; ?>/products?category=<?php echo $category['id']; ?>" 
                   class="text-decoration-none">
                    <div class="card text-center h-100 category-card">
                        <div class="card-body">
                            <i class="bi <?php echo $category['icon'] ?? 'bi-box'; ?>" 
                               style="font-size: 3rem; color: var(--primary);"></i>
                            <h6 class="mt-3 mb-2"><?php echo Security::escape($category['name']); ?></h6>
                            <small class="text-muted">
                                <?php echo $category['products_count']; ?> products
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Products -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>🆕 Latest Arrivals</h2>
            <p class="text-secondary">Fresh products added recently</p>
        </div>
        
        <div class="product-grid">
            <?php foreach ($latestProducts as $product): ?>
            <div class="card product-card">
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
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white;">
    <div class="container text-center">
        <h2 class="mb-4" style="color: white;">Ready to Start Selling?</h2>
        <p class="lead mb-4" style="color: rgba(255,255,255,0.9);">
            Join hundreds of farmers and agricultural sellers on AgriSmart
        </p>
        <?php if (!AuthMiddleware::check()): ?>
        <a href="<?php echo BASE_URL; ?>/auth/register?role=buyer" class="btn btn-accent btn-lg">
            <i class="bi bi-shop"></i> Become a Seller
        </a>
        <?php elseif (!RoleMiddleware::isBuyer() && !RoleMiddleware::isAdmin()): ?>
        <a href="<?php echo BASE_URL; ?>/dashboard/profile" class="btn btn-accent btn-lg">
            <i class="bi bi-shop"></i> Upgrade to Seller
        </a>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

<?php
$pageTitle = 'Smart Plant Recommendations - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
.recommendations-hero {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 3rem;
}

.recommendations-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.recommendations-hero p {
    font-size: 1.1rem;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto;
}

.input-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.input-section {
    margin-bottom: 2rem;
}

.input-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2e7d32;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    font-size: 1.5rem;
}

.range-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    font-weight: 500;
    color: #555;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
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

.btn-recommend {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    padding: 1rem 3rem;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    width: 100%;
}

.btn-recommend:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
}

.btn-recommend i {
    margin-right: 0.5rem;
}

.results-container {
    margin-top: 3rem;
}

.result-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.result-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.match-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.3);
}

.match-badge.high { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); }
.match-badge.medium { background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); }
.match-badge.low { background: linear-gradient(135deg, #757575 0%, #424242 100%); }

.result-content {
    display: flex;
    gap: 1.5rem;
    align-items: start;
}

.result-image {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
}

.result-info {
    flex: 1;
}

.result-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.result-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    color: #666;
    font-size: 0.9rem;
}

.meta-item i {
    color: #2e7d32;
}

.match-details {
    background: #f5f5f5;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.match-details h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.75rem;
}

.match-factors {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
}

.factor {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
}

.factor-label {
    color: #666;
}

.factor-score {
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    background: white;
}

.factor-score.excellent { color: #2e7d32; }
.factor-score.good { color: #f57c00; }
.factor-score.fair { color: #757575; }

.result-actions {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
}

.btn-view {
    background: #2e7d32;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-view:hover {
    background: #1b5e20;
    transform: translateY(-1px);
    color: white;
}

.no-results {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.no-results i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.no-results h3 {
    color: #333;
    margin-bottom: 0.5rem;
}

.no-results p {
    color: #666;
}

.loading {
    text-align: center;
    padding: 3rem;
}

.loading i {
    font-size: 3rem;
    color: #2e7d32;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .range-inputs {
        grid-template-columns: 1fr;
    }
    
    .result-content {
        flex-direction: column;
    }
    
    .result-image {
        width: 100%;
        height: 200px;
    }
    
    .match-badge {
        position: static;
        display: inline-block;
        margin-bottom: 1rem;
    }
}
</style>

<div class="recommendations-hero">
    <div class="container">
        <h1><i class="bi bi-stars"></i> Smart Plant Recommendations</h1>
        <p>Tell us about your growing conditions, and our AI will recommend the perfect plants for your environment</p>
    </div>
</div>

<div class="container pb-5">
    <form method="GET" action="<?php echo BASE_URL; ?>/products/recommendations" id="recommendForm">
        <div class="input-card">
            <div class="input-section">
                <div class="section-title">
                    <i class="bi bi-thermometer-half"></i>
                    Temperature Range
                </div>
                <div class="range-inputs">
                    <div class="form-group">
                        <label for="min_temp">Minimum Temperature (°C)</label>
                        <input type="number" class="form-control" id="min_temp" name="min_temp" 
                               value="<?php echo $_GET['min_temp'] ?? ''; ?>" placeholder="e.g., 15" step="0.1">
                    </div>
                    <div class="form-group">
                        <label for="max_temp">Maximum Temperature (°C)</label>
                        <input type="number" class="form-control" id="max_temp" name="max_temp" 
                               value="<?php echo $_GET['max_temp'] ?? ''; ?>" placeholder="e.g., 30" step="0.1">
                    </div>
                </div>
            </div>

            <div class="input-section">
                <div class="section-title">
                    <i class="bi bi-droplet-half"></i>
                    Humidity Range
                </div>
                <div class="range-inputs">
                    <div class="form-group">
                        <label for="min_humidity">Minimum Humidity (%)</label>
                        <input type="number" class="form-control" id="min_humidity" name="min_humidity" 
                               value="<?php echo $_GET['min_humidity'] ?? ''; ?>" placeholder="e.g., 40" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label for="max_humidity">Maximum Humidity (%)</label>
                        <input type="number" class="form-control" id="max_humidity" name="max_humidity" 
                               value="<?php echo $_GET['max_humidity'] ?? ''; ?>" placeholder="e.g., 70" min="0" max="100">
                    </div>
                </div>
            </div>

            <div class="input-section">
                <div class="section-title">
                    <i class="bi bi-calendar-range"></i>
                    Growing Season
                </div>
                <div class="form-group">
                    <select class="form-control" name="season" id="season">
                        <option value="">All Seasons</option>
                        <option value="spring" <?php echo ($_GET['season'] ?? '') === 'spring' ? 'selected' : ''; ?>>Spring</option>
                        <option value="summer" <?php echo ($_GET['season'] ?? '') === 'summer' ? 'selected' : ''; ?>>Summer</option>
                        <option value="fall" <?php echo ($_GET['season'] ?? '') === 'fall' ? 'selected' : ''; ?>>Fall</option>
                        <option value="winter" <?php echo ($_GET['season'] ?? '') === 'winter' ? 'selected' : ''; ?>>Winter</option>
                        <option value="year-round" <?php echo ($_GET['season'] ?? '') === 'year-round' ? 'selected' : ''; ?>>Year-round</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-recommend">
                <i class="bi bi-magic"></i> Get Smart Recommendations
            </button>
        </div>
    </form>

    <?php if (isset($recommendations)): ?>
    <div class="results-container">
        <?php if (!empty($recommendations)): ?>
            <h2 style="margin-bottom: 1.5rem; color: #333;">
                <i class="bi bi-check-circle" style="color: #2e7d32;"></i> 
                Found <?php echo count($recommendations); ?> Perfect Matches
            </h2>
            
            <?php foreach ($recommendations as $product): ?>
            <div class="result-card">
                <span class="match-badge <?php 
                    echo $product['match_score'] >= 80 ? 'high' : ($product['match_score'] >= 50 ? 'medium' : 'low'); 
                ?>">
                    <?php echo round($product['match_score']); ?>% Match
                </span>
                
                <div class="result-content">
                    <img src="<?php echo $product['image'] ? BASE_URL . '/assets/uploads/products/' . $product['image'] : 'https://via.placeholder.com/120?text=' . urlencode($product['name']); ?>" 
                         alt="<?php echo Security::escape($product['name']); ?>" 
                         class="result-image">
                    
                    <div class="result-info">
                        <h3 class="result-title"><?php echo Security::escape($product['name']); ?></h3>
                        
                        <div class="result-meta">
                            <div class="meta-item">
                                <i class="bi bi-tag-fill"></i>
                                <span><?php echo Security::escape($product['category_name']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-cash-coin"></i>
                                <span>$<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-thermometer"></i>
                                <span><?php echo $product['min_temperature']; ?>°C - <?php echo $product['max_temperature']; ?>°C</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-droplet"></i>
                                <span><?php echo $product['min_humidity']; ?>% - <?php echo $product['max_humidity']; ?>% humidity</span>
                            </div>
                        </div>
                        
                        <div class="match-details">
                            <h4><i class="bi bi-graph-up"></i> Match Breakdown</h4>
                            <div class="match-factors">
                                <div class="factor">
                                    <span class="factor-label">Temperature:</span>
                                    <span class="factor-score <?php echo $product['scores']['temperature'] >= 80 ? 'excellent' : ($product['scores']['temperature'] >= 50 ? 'good' : 'fair'); ?>">
                                        <?php echo round($product['scores']['temperature']); ?>%
                                    </span>
                                </div>
                                <div class="factor">
                                    <span class="factor-label">Humidity:</span>
                                    <span class="factor-score <?php echo $product['scores']['humidity'] >= 80 ? 'excellent' : ($product['scores']['humidity'] >= 50 ? 'good' : 'fair'); ?>">
                                        <?php echo round($product['scores']['humidity']); ?>%
                                    </span>
                                </div>
                                <div class="factor">
                                    <span class="factor-label">Season:</span>
                                    <span class="factor-score <?php echo $product['scores']['season'] >= 80 ? 'excellent' : ($product['scores']['season'] >= 50 ? 'good' : 'fair'); ?>">
                                        <?php echo round($product['scores']['season']); ?>%
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="result-actions">
                            <a href="<?php echo BASE_URL; ?>/products/view?id=<?php echo $product['id']; ?>" class="btn-view">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <i class="bi bi-search"></i>
                <h3>No Perfect Matches Found</h3>
                <p>Try adjusting your growing conditions to find more compatible plants.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

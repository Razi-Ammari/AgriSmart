<?php
$pageTitle = 'Add New Product - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';

// Fetch actual categories from database
$productModel = new Product();
$categories = $productModel->getCategories();

// Convert to array for display if not already
if (!is_array($categories) || empty($categories)) {
    $categories = [];
}

$units = [
    'kg' => 'Kilogram (kg)',
    'g' => 'Gram (g)',
    'liter' => 'Liter (L)',
    'ml' => 'Milliliter (ml)',
    'piece' => 'Piece',
    'bundle' => 'Bundle',
    'box' => 'Box'
];

$seasons = [
    'spring' => 'Spring',
    'summer' => 'Summer',
    'fall' => 'Fall',
    'winter' => 'Winter',
    'year-round' => 'Year-round'
];

$soilTypes = [
    'loamy' => 'Loamy Soil',
    'sandy' => 'Sandy Soil',
    'clay' => 'Clay Soil',
    'silty' => 'Silty Soil',
    'peat' => 'Peat Soil',
    'mixed' => 'Mixed Soil'
];

$sunlight = [
    'full-sun' => 'Full Sun (6+ hours)',
    'partial-shade' => 'Partial Shade (3-6 hours)',
    'shade' => 'Full Shade (<3 hours)'
];

$wateringFreq = [
    'daily' => 'Daily',
    '3-4-days' => 'Every 3-4 days',
    'weekly' => 'Weekly',
    'bi-weekly' => 'Bi-weekly',
    'monthly' => 'Monthly',
    'as-needed' => 'As Needed'
];
?>

<style>
/* =========================================
   ADD PRODUCT PAGE - CUSTOM STYLES
   ========================================= */

.add-product-page {
    background-color: var(--background);
    min-height: 100vh;
    padding: 2rem 0;
}

.add-product-container {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 1200px) {
    .add-product-container {
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .add-product-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* =========================================
   PAGE HEADER
   ========================================= */
.add-product-header {
    margin-bottom: 2rem;
    animation: fadeInDown 0.5s ease;
}

.add-product-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.add-product-header h1 i {
    color: var(--primary);
    font-size: 2.2rem;
}

.add-product-header p {
    color: var(--text-secondary);
    font-size: 1rem;
    margin: 0;
}

/* =========================================
   FORM SECTIONS / CARDS
   ========================================= */
.form-section {
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border);
    transition: all var(--transition-base);
    animation: fadeInUp 0.5s ease;
}

.form-section:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border);
}

.section-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.section-header i {
    font-size: 1.5rem;
    color: var(--primary);
    min-width: 24px;
}

.section-helper {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-left: 2.25rem;
    margin-bottom: 1.25rem;
    font-style: italic;
}

/* =========================================
   FORM CONTROLS
   ========================================= */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group label .required {
    color: var(--danger);
    margin-left: 0.25rem;
}

.form-group .form-text {
    display: block;
    font-size: 0.8rem;
    color: var(--text-light);
    margin-top: 0.4rem;
}

.form-control,
.form-select {
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    color: var(--text-primary);
    transition: all var(--transition-fast);
    font-family: inherit;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    outline: none;
}

.form-control::placeholder {
    color: var(--text-light);
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
    font-family: 'Poppins', sans-serif;
}

/* Character Counter */
.char-counter {
    text-align: right;
    font-size: 0.8rem;
    color: var(--text-light);
    margin-top: 0.4rem;
}

.char-counter.warning {
    color: var(--warning);
}

.char-counter.danger {
    color: var(--danger);
}

/* =========================================
   GRID LAYOUTS
   ========================================= */
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-row.three-col {
    grid-template-columns: repeat(3, 1fr);
}

@media (max-width: 768px) {
    .form-row,
    .form-row.three-col {
        grid-template-columns: 1fr;
    }
}

/* =========================================
   IMAGE UPLOAD
   ========================================= */
.image-upload-wrapper {
    margin-bottom: 1rem;
}

.image-upload-zone {
    border: 2px dashed var(--border);
    border-radius: var(--radius-md);
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all var(--transition-base);
    background-color: rgba(46, 125, 50, 0.02);
}

.image-upload-zone:hover {
    border-color: var(--primary);
    background-color: rgba(46, 125, 50, 0.05);
}

.image-upload-zone.drag-over {
    border-color: var(--primary);
    background-color: rgba(46, 125, 50, 0.1);
    box-shadow: var(--shadow-md);
}

.upload-icon {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 0.75rem;
    display: block;
}

.upload-text {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.upload-hint {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.upload-input {
    display: none;
}

.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.image-preview-card {
    position: relative;
    border-radius: var(--radius-md);
    overflow: hidden;
    background-color: var(--background);
    border: 1px solid var(--border);
    aspect-ratio: 1;
    animation: scaleIn 0.3s ease;
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.image-preview-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity var(--transition-fast);
}

.image-preview-card:hover .image-preview-overlay {
    opacity: 1;
}

.remove-image-btn {
    background-color: var(--danger);
    color: white;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all var(--transition-fast);
}

.remove-image-btn:hover {
    background-color: #c82333;
    transform: scale(1.1);
}

/* =========================================
   STICKY PANEL (RIGHT COLUMN)
   ========================================= */
.sticky-panel {
    position: sticky;
    top: 2rem;
}

@media (max-width: 768px) {
    .sticky-panel {
        position: static;
        top: auto;
        order: -1;
    }
}

.panel-card {
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 1.75rem;
    border: 1px solid var(--border);
    margin-bottom: 1.5rem;
    animation: fadeInUp 0.5s ease 0.1s backwards;
}

/* =========================================
   PRODUCT PREVIEW
   ========================================= */
.preview-section {
    margin-bottom: 1.5rem;
}

.preview-section h3 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.preview-card {
    border-radius: var(--radius-md);
    overflow: hidden;
    background-color: var(--background);
    border: 1px solid var(--border);
}

.preview-image {
    width: 100%;
    aspect-ratio: 1;
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-light);
    font-size: 1.25rem;
    font-weight: 500;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-content {
    padding: 1rem;
}

.preview-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
    margin-bottom: 0.4rem;
    word-break: break-word;
}

.preview-category {
    font-size: 0.8rem;
    color: var(--text-light);
    margin-bottom: 0.6rem;
}

.preview-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary);
}

/* =========================================
   PROGRESS INDICATOR
   ========================================= */
.progress-section {
    margin-bottom: 1.5rem;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.progress-text {
    font-weight: 600;
    color: var(--text-primary);
}

.progress-percentage {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
}

.progress {
    height: 8px;
    background-color: var(--border);
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 4px;
    transition: width var(--transition-base);
}

/* =========================================
   ACTION BUTTONS
   ========================================= */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-primary-lg {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--transition-base);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
}

.btn-primary-lg:hover:not(:disabled) {
    background-color: var(--primary-dark);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.btn-primary-lg:active:not(:disabled) {
    transform: translateY(0);
}

.btn-primary-lg:disabled {
    background-color: var(--text-light);
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-secondary-lg {
    background-color: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
    padding: 0.875rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all var(--transition-base);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
}

.btn-secondary-lg:hover {
    background-color: var(--primary);
    color: white;
}

/* =========================================
   VALIDATION FEEDBACK
   ========================================= */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: var(--danger);
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.form-control.is-valid,
.form-select.is-valid {
    border-color: var(--success);
}

.form-control.is-valid:focus,
.form-select.is-valid:focus {
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

.invalid-feedback,
.valid-feedback {
    display: block;
    font-size: 0.85rem;
    margin-top: 0.4rem;
}

.invalid-feedback {
    color: var(--danger);
}

.valid-feedback {
    color: var(--success);
}

/* =========================================
   ANIMATIONS
   ========================================= */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* =========================================
   MOBILE ACTION BAR
   ========================================= */
@media (max-width: 768px) {
    .sticky-panel {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: auto;
        border-radius: 0;
        padding: 1rem;
        margin-bottom: 0;
        background: var(--surface);
        border-top: 1px solid var(--border);
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        z-index: 100;
    }

    .add-product-container {
        padding-bottom: 120px;
    }

    .action-buttons {
        flex-direction: row;
        gap: 1rem;
    }

    .btn-primary-lg,
    .btn-secondary-lg {
        flex: 1;
        padding: 0.875rem 1rem;
        font-size: 0.9rem;
    }
}

/* =========================================
   TRANSITIONS & POLISH
   ========================================= */
.form-control:disabled {
    background-color: var(--background);
    color: var(--text-light);
}

.form-select:disabled {
    background-color: var(--background);
    color: var(--text-light);
}

label {
    user-select: none;
}

/* =========================================
   LOADING STATE
   ========================================= */
.btn-loading {
    pointer-events: none;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.2em;
}

/* =========================================
   MODAL STYLES
   ========================================= */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    animation: fadeIn 0.2s ease;
}

.modal-overlay.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    max-width: 450px;
    width: 90%;
    animation: slideUp 0.3s ease;
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-icon {
    font-size: 1.5rem;
}

.modal-icon.success {
    color: #2e7d32;
}

.modal-icon.error {
    color: #d32f2f;
}

.modal-icon.warning {
    color: #f57c00;
}

.modal-icon.confirm {
    color: #1976d2;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.modal-body {
    padding: 2rem;
    color: #555;
    line-height: 1.6;
}

.modal-footer {
    padding: 1rem 2rem 1.5rem;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.modal-btn {
    padding: 0.65rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 0.95rem;
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
}

.modal-btn-danger {
    background: #d32f2f;
    color: white;
}

.modal-btn-danger:hover {
    background: #b71c1c;
}

.modal-btn-secondary {
    background: #f5f5f5;
    color: #555;
}

.modal-btn-secondary:hover {
    background: #e0e0e0;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<div class="add-product-page">
    <div class="container">
        <!-- Page Header -->
        <div class="add-product-header">
            <h1><i class="bi bi-plus-circle"></i> Add New Product</h1>
            <p>Fill in the details below to list your product on AgriSmart</p>
        </div>

        <!-- Main Form -->
        <form id="addProductForm" method="POST" action="<?php echo BASE_URL; ?>/products/create" enctype="multipart/form-data" class="add-product-container" novalidate>
            
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo Security::getCsrfToken(); ?>">
            
            <!-- LEFT COLUMN: FORM SECTIONS -->
            <div class="form-content">

                <!-- ===== SECTION 1: Basic Product Info ===== -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="bi bi-box"></i>
                        <h2>Basic Information</h2>
                    </div>
                    <p class="section-helper">Essential product details</p>

                    <div class="form-group">
                        <label for="productName">
                            Product Name
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="productName" 
                            name="name" 
                            placeholder="e.g., Organic Tomato Seeds"
                            required
                            maxlength="200"
                        >
                        <small class="form-text">Give your product a clear, descriptive name</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">
                                Category
                                <span class="required">*</span>
                            </label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo isset($category['id']) ? $category['id'] : $category; ?>">
                                        <?php echo isset($category['name']) ? $category['name'] : $category; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text">Choose the most relevant category</small>
                        </div>

                        <div class="form-group">
                            <label for="unit">
                                Unit of Measurement
                                <span class="required">*</span>
                            </label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="">Select unit</option>
                                <?php foreach ($units as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text">How your product is measured</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">
                                Price per Unit
                                <span class="required">*</span>
                            </label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-weight: 500;">$</span>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="price" 
                                    name="price" 
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    required
                                    style="padding-left: 2rem;"
                                >
                            </div>
                            <small class="form-text">Set a competitive price</small>
                        </div>

                        <div class="form-group">
                            <label for="stock">
                                Stock Quantity
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="stock" 
                                name="stock_quantity" 
                                placeholder="0"
                                min="0"
                                required
                            >
                            <small class="form-text">Available units to sell</small>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 2: Product Description ===== -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="bi bi-file-text"></i>
                        <h2>Description</h2>
                    </div>
                    <p class="section-helper">Help buyers understand your product</p>

                    <div class="form-group">
                        <label for="description">
                            Product Description
                            <span class="required">*</span>
                        </label>
                        <textarea 
                            class="form-control" 
                            id="description" 
                            name="description" 
                            placeholder="Describe your product in detail. Include quality, characteristics, growing methods, storage tips, etc."
                            maxlength="1000"
                            required
                        ></textarea>
                        <div class="char-counter">
                            <span id="charCount">0</span> / 1000 characters
                        </div>
                        <small class="form-text">Be descriptive to attract buyers</small>
                    </div>
                </div>

                <!-- ===== SECTION 3: Agriculture Details ===== -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="bi bi-leaf"></i>
                        <h2>Agriculture Details</h2>
                    </div>
                    <p class="section-helper">Growing information (optional)</p>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="growingSeason">
                                Growing Season
                            </label>
                            <select class="form-select" id="growingSeason" name="growing_season">
                                <option value="">Select season</option>
                                <?php foreach ($seasons as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="growingTime">
                                Growing Time (days)
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="growingTime" 
                                name="harvest_time" 
                                placeholder="e.g., 60"
                                min="0"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="sunlight">
                                Sunlight Requirement
                            </label>
                            <select class="form-select" id="sunlight" name="sunlight_requirement">
                                <option value="">Select sunlight type</option>
                                <?php foreach ($sunlight as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="soilType">
                                Soil Type
                            </label>
                            <select class="form-select" id="soilType" name="soil_type">
                                <option value="">Select soil type</option>
                                <?php foreach ($soilTypes as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-col">
                        <div class="form-group">
                            <label for="minTemp">
                                Min Temperature (°C)
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="minTemp" 
                                name="min_temperature" 
                                placeholder="e.g., 15"
                                step="0.1"
                            >
                        </div>

                        <div class="form-group">
                            <label for="maxTemp">
                                Max Temperature (°C)
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="maxTemp" 
                                name="max_temperature" 
                                placeholder="e.g., 28"
                                step="0.1"
                            >
                        </div>

                        <div class="form-group">
                            <label for="wateringFreq">
                                Watering Frequency
                            </label>
                            <select class="form-select" id="wateringFreq" name="watering_frequency">
                                <option value="">Select frequency</option>
                                <?php foreach ($wateringFreq as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="minHumidity">
                                Min Humidity (%)
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="minHumidity" 
                                name="min_humidity" 
                                placeholder="e.g., 40"
                                min="0"
                                max="100"
                                step="0.1"
                            >
                        </div>

                        <div class="form-group">
                            <label for="maxHumidity">
                                Max Humidity (%)
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="maxHumidity" 
                                name="max_humidity" 
                                placeholder="e.g., 80"
                                min="0"
                                max="100"
                                step="0.1"
                            >
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 4: Product Images ===== -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="bi bi-image"></i>
                        <h2>Product Images</h2>
                    </div>
                    <p class="section-helper">High-quality images help sell better</p>

                    <div class="image-upload-wrapper">
                        <div class="image-upload-zone" id="uploadZone">
                            <span class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </span>
                            <div class="upload-text">Drop image here or click to upload</div>
                            <div class="upload-hint">JPG, PNG up to 5MB (only 1 main image)</div>
                            <input 
                                type="file" 
                                class="upload-input" 
                                id="imageUploadInput" 
                                name="image" 
                                accept="image/*"
                            >
                        </div>

                        <div class="image-preview-grid" id="imagePreviewGrid"></div>
                    </div>

                    <small class="form-text">Upload a clear product image for better visibility</small>
                </div>

            </div>

            <!-- RIGHT COLUMN: STICKY PANEL -->
            <div class="sticky-panel">

                <!-- Product Preview Card -->
                <div class="panel-card">
                    <div class="preview-section">
                        <h3>Preview</h3>
                        <div class="preview-card">
                            <div class="preview-image" id="previewImage">
                                <i class="bi bi-image"></i>
                            </div>
                            <div class="preview-content">
                                <div class="preview-name" id="previewName">Product Name</div>
                                <div class="preview-category" id="previewCategory">Category</div>
                                <div class="preview-price" id="previewPrice">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Indicator -->
                <div class="panel-card">
                    <div class="progress-section">
                        <div class="progress-label">
                            <span class="progress-text">Form Completion</span>
                            <span class="progress-percentage" id="progressPercent">0%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="panel-card">
                    <div class="action-buttons">
                        <button 
                            type="submit" 
                            class="btn-primary-lg" 
                            id="submitBtn"
                            disabled
                        >
                            <i class="bi bi-check-circle"></i>
                            Create Product
                        </button>
                        <button 
                            type="button" 
                            class="btn-secondary-lg"
                            onclick="saveDraft()"
                        >
                            <i class="bi bi-bookmark"></i>
                            Save as Draft
                        </button>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-container">
        <div class="modal-header">
            <i class="bi modal-icon" id="modalIcon"></i>
            <h3 class="modal-title" id="modalTitle"></h3>
        </div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer" id="modalFooter"></div>
    </div>
</div>

<script>
// Modern Modal System
const modal = {
    overlay: document.getElementById('modalOverlay'),
    icon: document.getElementById('modalIcon'),
    title: document.getElementById('modalTitle'),
    body: document.getElementById('modalBody'),
    footer: document.getElementById('modalFooter'),
    
    show(options) {
        const { type = 'info', title, message, buttons = [] } = options;
        
        // Set icon
        this.icon.className = `bi modal-icon ${type}`;
        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            confirm: 'bi-question-circle-fill'
        };
        this.icon.classList.add(icons[type] || 'bi-info-circle-fill');
        
        // Set content
        this.title.textContent = title;
        this.body.textContent = message;
        
        // Set buttons
        this.footer.innerHTML = '';
        buttons.forEach(btn => {
            const button = document.createElement('button');
            button.className = `modal-btn ${btn.class || 'modal-btn-secondary'}`;
            button.textContent = btn.text;
            button.onclick = () => {
                if (btn.onClick) btn.onClick();
                this.hide();
            };
            this.footer.appendChild(button);
        });
        
        // Show modal
        this.overlay.classList.add('active');
    },
    
    hide() {
        this.overlay.classList.remove('active');
    },
    
    alert(message, title = 'Notice', type = 'warning') {
        this.show({
            type,
            title,
            message,
            buttons: [
                { text: 'OK', class: 'modal-btn-primary' }
            ]
        });
    },
    
    confirm(message, onConfirm, title = 'Confirm Action') {
        this.show({
            type: 'confirm',
            title,
            message,
            buttons: [
                { text: 'Cancel', class: 'modal-btn-secondary' },
                { text: 'Confirm', class: 'modal-btn-primary', onClick: onConfirm }
            ]
        });
    },
    
    success(message, title = 'Success') {
        this.show({
            type: 'success',
            title,
            message,
            buttons: [
                { text: 'OK', class: 'modal-btn-primary' }
            ]
        });
    },
    
    error(message, title = 'Error') {
        this.show({
            type: 'error',
            title,
            message,
            buttons: [
                { text: 'OK', class: 'modal-btn-primary' }
            ]
        });
    }
};

// Close modal on overlay click
modal.overlay.addEventListener('click', (e) => {
    if (e.target === modal.overlay) {
        modal.hide();
    }
});

/**
 * Add Product Form - Interactive Functionality
 */

const form = document.getElementById('addProductForm');
const submitBtn = document.getElementById('submitBtn');
const progressBar = document.getElementById('progressBar');
const progressPercent = document.getElementById('progressPercent');

// Preview elements
const previewName = document.getElementById('previewName');
const previewCategory = document.getElementById('previewCategory');
const previewPrice = document.getElementById('previewPrice');
const previewImage = document.getElementById('previewImage');

// Form fields
const productName = document.getElementById('productName');
const category = document.getElementById('category');
const price = document.getElementById('price');
const stock = document.getElementById('stock');
const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

// Image upload
const uploadZone = document.getElementById('uploadZone');
const imageUploadInput = document.getElementById('imageUploadInput');
const imagePreviewGrid = document.getElementById('imagePreviewGrid');

let uploadedImages = [];

/**
 * ========================================
 * FORM VALIDATION & COMPLETION TRACKING
 * ========================================
 */

// Required fields for form submission
const requiredFields = [
    'productName',
    'category',
    'price',
    'stock',
    'description',
    'unit'
];

function updateProgress() {
    let filledFields = 0;
    const totalRequired = requiredFields.length;

    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value.trim() !== '') {
            filledFields++;
        }
    });

    // Calculate percentage (images bonus: up to 10%)
    let percentage = Math.round((filledFields / totalRequired) * 90);
    if (uploadedImages.length > 0) {
        percentage = Math.min(100, percentage + 10);
    }

    // Update progress bar
    progressBar.style.width = percentage + '%';
    progressPercent.textContent = percentage + '%';

    // Enable/disable submit button
    const allRequiredFilled = filledFields === totalRequired;
    submitBtn.disabled = !allRequiredFilled;

    return {
        filled: filledFields,
        total: totalRequired,
        percentage: percentage
    };
}

/**
 * ========================================
 * PREVIEW UPDATES
 * ========================================
 */

function updatePreview() {
    // Name
    previewName.textContent = productName.value || 'Product Name';

    // Category
    if (category.value) {
        const categoryLabel = category.options[category.selectedIndex].text;
        previewCategory.textContent = categoryLabel;
    } else {
        previewCategory.textContent = 'Category';
    }

    // Price
    const priceValue = price.value ? parseFloat(price.value) : 0;
    previewPrice.textContent = '$' + priceValue.toFixed(2);
}

/**
 * ========================================
 * CHARACTER COUNTER
 * ========================================
 */

description.addEventListener('input', function() {
    const count = this.value.length;
    charCount.textContent = count;

    // Add warning at 80%
    const counter = charCount.parentElement;
    if (count > 800) {
        counter.classList.add('warning');
    } else if (count > 950) {
        counter.classList.add('danger');
        counter.classList.remove('warning');
    } else {
        counter.classList.remove('warning', 'danger');
    }

    updateProgress();
    updatePreview();
});

/**
 * ========================================
 * FORM FIELD LISTENERS
 * ========================================
 */

productName.addEventListener('input', function() {
    updateProgress();
    updatePreview();
});

category.addEventListener('change', function() {
    updateProgress();
    updatePreview();
});

price.addEventListener('input', function() {
    updateProgress();
    updatePreview();
});

stock.addEventListener('input', function() {
    updateProgress();
});

document.getElementById('unit').addEventListener('change', function() {
    updateProgress();
});

/**
 * ========================================
 * IMAGE UPLOAD HANDLING
 * ========================================
 */

// Click to upload
uploadZone.addEventListener('click', function() {
    imageUploadInput.click();
});

// Drag and drop
uploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadZone.classList.add('drag-over');
});

uploadZone.addEventListener('dragleave', function() {
    uploadZone.classList.remove('drag-over');
});

uploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    handleImageFiles(files);
});

// File input change
imageUploadInput.addEventListener('change', function() {
    handleImageFiles(this.files);
});

function handleImageFiles(files) {
    if (files.length === 0) return;

    const file = files[0];

    // Validate file type
    if (!file.type.startsWith('image/')) {
        modal.error('Please upload a valid image file (JPG, PNG, GIF)', 'Invalid File Type');
        imageUploadInput.value = '';
        return;
    }

    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        modal.error('Image size must not exceed 5MB. Please choose a smaller file.', 'File Too Large');
        imageUploadInput.value = '';
        return;
    }

    // Read and display image
    const reader = new FileReader();
    reader.onload = function(e) {
        uploadedImages = [{
            file: file,
            src: e.target.result,
            id: Date.now()
        }];

        renderImagePreviewGrid();
        updateProgress();
        setFirstImageAsPreview();
    };

    reader.readAsDataURL(file);

    // Don't reset input - we need the file to be submitted with the form
}

function renderImagePreview(imageData) {
    const card = document.createElement('div');
    card.className = 'image-preview-card';
    card.innerHTML = `
        <img src="${imageData.src}" alt="Product image">
        <div class="image-preview-overlay">
            <button type="button" class="remove-image-btn" onclick="removeImage('${imageData.id}')">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    imagePreviewGrid.appendChild(card);
}

function removeImage(imageId) {
    uploadedImages = uploadedImages.filter(img => img.id !== imageId);
    renderImagePreviewGrid();
    updateProgress();
    
    // Clear the file input
    imageUploadInput.value = '';
    
    // Update preview
    if (uploadedImages.length > 0) {
        setFirstImageAsPreview();
    } else {
        previewImage.innerHTML = '<i class="bi bi-image"></i>';
    }
}

function renderImagePreviewGrid() {
    imagePreviewGrid.innerHTML = '';
    uploadedImages.forEach(imageData => {
        renderImagePreview(imageData);
    });
}

function setFirstImageAsPreview() {
    if (uploadedImages.length > 0) {
        previewImage.innerHTML = `<img src="${uploadedImages[0].src}" alt="Product preview">`;
    }
}

/**
 * ========================================
 * FORM SUBMISSION
 * ========================================
 */

form.addEventListener('submit', function(e) {
    // Validate required fields
    if (!validateForm()) {
        e.preventDefault();
        modal.error('Please fill in all required fields (Name, Category, Price, Stock)', 'Missing Required Fields');
        return;
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-loading');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating...';

    // Let the form submit naturally with the file input
});

function resetSubmitButton() {
    submitBtn.disabled = false;
    submitBtn.classList.remove('btn-loading');
    submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Create Product';
}

function validateForm() {
    let isValid = true;

    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value.trim() === '') {
            field.classList.add('is-invalid');
            isValid = false;
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

/**
 * ========================================
 * DRAFT SAVING
 * ========================================
 */

function saveDraft() {
    const formData = new FormData(form);
    
    // Save to localStorage for later recovery
    const draftData = {
        timestamp: new Date().toISOString(),
        data: {
            name: productName.value,
            category_id: category.value,
            price: price.value,
            stock_quantity: stock.value,
            unit: document.getElementById('unit').value,
            description: description.value,
            growing_season: document.getElementById('growingSeason').value,
            harvest_time: document.getElementById('growingTime').value,
            sunlight_requirement: document.getElementById('sunlight').value,
            soil_type: document.getElementById('soilType').value,
            min_temperature: document.getElementById('minTemp').value,
            max_temperature: document.getElementById('maxTemp').value,
            min_humidity: document.getElementById('minHumidity').value,
            max_humidity: document.getElementById('maxHumidity').value,
            watering_frequency: document.getElementById('wateringFreq').value
        }
    };

    localStorage.setItem('product_draft', JSON.stringify(draftData));
    modal.success('Draft saved successfully! You can continue editing later.', 'Draft Saved');
}

/**
 * ========================================
 * LOAD DRAFT ON PAGE LOAD
 * ========================================
 */

function loadDraft() {
    const savedDraft = localStorage.getItem('product_draft');
    if (savedDraft) {
        try {
            const { data } = JSON.parse(savedDraft);
            
            // Load form data
            productName.value = data.name || '';
            category.value = data.category_id || '';
            price.value = data.price || '';
            stock.value = data.stock_quantity || '';
            document.getElementById('unit').value = data.unit || '';
            description.value = data.description || '';
            document.getElementById('growingSeason').value = data.growing_season || '';
            document.getElementById('growingTime').value = data.harvest_time || '';
            document.getElementById('sunlight').value = data.sunlight_requirement || '';
            document.getElementById('soilType').value = data.soil_type || '';
            document.getElementById('minTemp').value = data.min_temperature || '';
            document.getElementById('maxTemp').value = data.max_temperature || '';
            document.getElementById('minHumidity').value = data.min_humidity || '';
            document.getElementById('maxHumidity').value = data.max_humidity || '';
            document.getElementById('wateringFreq').value = data.watering_frequency || '';

            // Update UI
            updateProgress();
            updatePreview();
            charCount.textContent = description.value.length;

            console.log('Draft loaded successfully');
        } catch (error) {
            console.error('Error loading draft:', error);
        }
    }
}

/**
 * ========================================
 * INITIALIZATION
 * ========================================
 */

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    loadDraft();
    updateProgress();
    updatePreview();
});

// Auto-save draft every 30 seconds
setInterval(saveDraft, 30000);
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

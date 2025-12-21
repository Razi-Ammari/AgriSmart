<?php
$pageTitle = 'Edit Product - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';

// Get old values or current product values
$old = $_SESSION['old'] ?? $product;
unset($_SESSION['old']);

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
/* Edit Product Page - Premium Theme */
.edit-product-page {
    background: #fafafa;
    min-height: 100vh;
    padding: 2rem 0 4rem;
}

.edit-product-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-title i {
    color: #2e7d32;
}

.page-subtitle {
    color: #666;
    font-size: 1rem;
}

.edit-form-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 992px) {
    .edit-form-layout {
        grid-template-columns: 1fr;
    }
}

/* Form Sections */
.form-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-title i {
    color: #2e7d32;
    font-size: 1.3rem;
}

/* Form Groups */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 500;
    color: #555;
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: '*';
    color: #d32f2f;
    margin-left: 0.25rem;
}

.form-control,
.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: #2e7d32;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
    font-family: inherit;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

/* Current Image Preview */
.current-image-preview {
    margin-bottom: 1rem;
}

.current-image-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
    display: block;
}

.current-image-wrapper {
    position: relative;
    display: inline-block;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.current-image {
    max-width: 300px;
    width: 100%;
    height: auto;
    display: block;
}

.no-image-placeholder {
    width: 300px;
    height: 200px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 3rem;
    border-radius: 8px;
}

/* Image Upload */
.image-upload-zone {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: #fafafa;
}

.image-upload-zone:hover {
    border-color: #2e7d32;
    background: #f1f8f4;
}

.upload-icon {
    font-size: 2.5rem;
    color: #2e7d32;
    margin-bottom: 0.75rem;
}

.upload-text {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.25rem;
}

.upload-hint {
    font-size: 0.85rem;
    color: #999;
}

.upload-input {
    display: none;
}

.new-image-preview {
    margin-top: 1rem;
    display: none;
}

.new-image-preview.active {
    display: block;
}

.new-image {
    max-width: 300px;
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Checkbox/Switch */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    background: #f9f9f9;
    border-radius: 8px;
}

.checkbox-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-label {
    font-size: 0.95rem;
    color: #555;
    cursor: pointer;
    margin: 0;
}

/* Sticky Panel */
.sticky-panel {
    position: sticky;
    top: 2rem;
}

.panel-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

.panel-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-primary-lg,
.btn-secondary-lg,
.btn-danger-lg {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-primary-lg {
    background: #2e7d32;
    color: white;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.btn-primary-lg:hover {
    background: #1b5e20;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
}

.btn-secondary-lg {
    background: #f5f5f5;
    color: #555;
    border: 2px solid #ddd;
}

.btn-secondary-lg:hover {
    background: #e8e8e8;
    border-color: #ccc;
}

.btn-danger-lg {
    background: #d32f2f;
    color: white;
}

.btn-danger-lg:hover {
    background: #b71c1c;
}

.btn-primary-lg:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Alert Messages */
.alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #2e7d32;
}

.alert-error {
    background: #fdecea;
    color: #d32f2f;
    border-left: 4px solid #d32f2f;
}

.char-counter {
    text-align: right;
    font-size: 0.85rem;
    color: #999;
    margin-top: 0.25rem;
}

/* Modal Styles */
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

<div class="edit-product-page">
    <div class="container">
        <div class="edit-product-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="bi bi-pencil-square"></i>
                    Edit Product
                </h1>
                <p class="page-subtitle">Update your product information</p>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" enctype="multipart/form-data" id="editProductForm" class="edit-form-layout">
                <?php echo Security::csrfField(); ?>

                <!-- LEFT COLUMN -->
                <div class="form-main">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="bi bi-info-circle"></i>
                            Basic Information
                        </h2>

                        <div class="form-group">
                            <label class="form-label required">Product Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   id="productName"
                                   value="<?php echo Security::escape($old['name'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Category</label>
                                <select class="form-select" name="category_id" id="category" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"
                                                <?php echo ($old['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                            <?php echo Security::escape($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Unit</label>
                                <select class="form-select" name="unit" id="unit" required>
                                    <?php foreach ($units as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"
                                                <?php echo ($old['unit'] ?? 'piece') == $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Price ($)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="price" 
                                       id="price"
                                       value="<?php echo Security::escape($old['price'] ?? ''); ?>"
                                       step="0.01"
                                       min="0"
                                       required>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Stock Quantity</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="stock_quantity" 
                                       id="stock"
                                       value="<?php echo Security::escape($old['stock_quantity'] ?? 0); ?>"
                                       min="0"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" 
                                      name="description" 
                                      id="description"
                                      maxlength="1000"><?php echo Security::escape($old['description'] ?? ''); ?></textarea>
                            <div class="char-counter" id="charCount">0 / 1000</div>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="bi bi-image"></i>
                            Product Image
                        </h2>

                        <?php if (!empty($product['image'])): ?>
                            <div class="current-image-preview">
                                <label class="current-image-label">Current Image:</label>
                                <div class="current-image-wrapper">
                                    <img src="<?php echo BASE_URL . '/assets/uploads/products/' . $product['image']; ?>" 
                                         alt="Current product image" 
                                         class="current-image">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="current-image-preview">
                                <label class="current-image-label">Current Image:</label>
                                <div class="no-image-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="image-upload-zone" id="uploadZone">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <div class="upload-text">Click to upload new image or drop here</div>
                            <div class="upload-hint">JPG, PNG up to 5MB (optional)</div>
                            <input type="file" 
                                   class="upload-input" 
                                   id="imageInput" 
                                   name="image" 
                                   accept="image/*">
                        </div>

                        <div class="new-image-preview" id="newImagePreview">
                            <label class="current-image-label">New Image Preview:</label>
                            <img src="" alt="New image preview" class="new-image" id="newImageDisplay">
                        </div>
                    </div>

                    <!-- Growing Information -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="bi bi-flower1"></i>
                            Growing Information (Optional)
                        </h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Sunlight Requirement</label>
                                <select class="form-select" name="sunlight_requirement">
                                    <option value="">Select...</option>
                                    <?php foreach ($sunlight as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"
                                                <?php echo ($old['sunlight_requirement'] ?? '') == $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Watering Frequency</label>
                                <select class="form-select" name="watering_frequency">
                                    <option value="">Select...</option>
                                    <?php foreach ($wateringFreq as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"
                                                <?php echo ($old['watering_frequency'] ?? '') == $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Soil Type</label>
                                <select class="form-select" name="soil_type">
                                    <option value="">Select...</option>
                                    <?php foreach ($soilTypes as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"
                                                <?php echo ($old['soil_type'] ?? '') == $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Growing Season</label>
                                <select class="form-select" name="growing_season">
                                    <option value="">Select...</option>
                                    <?php foreach ($seasons as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"
                                                <?php echo ($old['growing_season'] ?? '') == $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Min Temperature (°C)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="min_temperature"
                                       value="<?php echo Security::escape($old['min_temperature'] ?? ''); ?>"
                                       step="0.1">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Max Temperature (°C)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="max_temperature"
                                       value="<?php echo Security::escape($old['max_temperature'] ?? ''); ?>"
                                       step="0.1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Min Humidity (%)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="min_humidity"
                                       value="<?php echo Security::escape($old['min_humidity'] ?? ''); ?>"
                                       min="0"
                                       max="100">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Max Humidity (%)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="max_humidity"
                                       value="<?php echo Security::escape($old['max_humidity'] ?? ''); ?>"
                                       min="0"
                                       max="100">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Harvest Time</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="harvest_time"
                                   value="<?php echo Security::escape($old['harvest_time'] ?? ''); ?>"
                                   placeholder="e.g., 60-90 days">
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN - Sticky Panel -->
                <div class="sticky-panel">
                    <!-- Product Status -->
                    <div class="panel-card">
                        <h3 class="panel-title">Product Status</h3>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" 
                                   class="checkbox-input" 
                                   name="is_active" 
                                   id="isActive"
                                   <?php echo ($old['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="checkbox-label" for="isActive">
                                Product is Active (visible to buyers)
                            </label>
                        </div>

                        <div class="checkbox-group" style="margin-top: 0.75rem;">
                            <input type="checkbox" 
                                   class="checkbox-input" 
                                   name="is_featured" 
                                   id="isFeatured"
                                   <?php echo ($old['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                            <label class="checkbox-label" for="isFeatured">
                                Featured Product (highlight on homepage)
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="panel-card">
                        <div class="action-buttons">
                            <button type="submit" class="btn-primary-lg">
                                <i class="bi bi-check-circle"></i>
                                Update Product
                            </button>
                            
                            <a href="<?php echo BASE_URL; ?>/products/view?id=<?php echo $product['id']; ?>" 
                               class="btn-secondary-lg">
                                <i class="bi bi-x-circle"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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

<!-- Delete Form (hidden) -->
<form method="POST" action="<?php echo BASE_URL; ?>/products/delete" id="deleteForm" style="display: none;">
    <?php echo Security::csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
</form>

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
        product_
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
                { text: 'Confirm', class: 'modal-btn-danger', onClick: onConfirm }
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

// Character counter for description
const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

function updateCharCount() {
    const length = description.value.length;
    charCount.textContent = `${length} / 1000`;
}

description.addEventListener('input', updateCharCount);
updateCharCount();

// Image upload handling
const uploadZone = document.getElementById('uploadZone');
const imageInput = document.getElementById('imageInput');
const newImagePreview = document.getElementById('newImagePreview');
const newImageDisplay = document.getElementById('newImageDisplay');

uploadZone.addEventListener('click', () => {
    imageInput.click();
});

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.style.borderColor = '#2e7d32';
    uploadZone.style.background = '#f1f8f4';
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.style.borderColor = '#ddd';
    uploadZone.style.background = '#fafafa';
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.style.borderColor = '#ddd';
    uploadZone.style.background = '#fafafa';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        imageInput.files = files;
        handleImageUpload(files[0]);
    }
});

imageInput.addEventListener('change', () => {
    if (imageInput.files.length > 0) {
        handleImageUpload(imageInput.files[0]);
    }
});

function handleImageUpload(file) {
    if (!file.type.startsWith('image/')) {
        modal.error('Please upload a valid image file (JPG, PNG, GIF)', 'Invalid File Type');
        imageInput.value = '';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        modal.error('Image size must not exceed 5MB. Please choose a smaller file.', 'File Too Large');
        imageInput.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        newImageDisplay.src = e.target.result;
        newImagePreview.classList.add('active');
    };
    reader.readAsDataURL(file);
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

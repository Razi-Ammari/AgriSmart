<?php
/**
 * Profile Page - User Dashboard
 * AgriSmart - Agriculture Marketplace
 */

$pageTitle = 'My Profile - AgriSmart';
require_once APP_PATH . '/views/layouts/header.php';
?>

<style>
    .profile-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .profile-header {
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.2);
    }

    .profile-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }

    .profile-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
    }

    .profile-sidebar {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        height: fit-content;
        text-align: center;
    }

    .profile-picture-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 1.5rem;
    }

    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e8f5e9;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
    }

    .profile-picture-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(46, 125, 50, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        cursor: pointer;
    }

    .profile-picture-wrapper:hover .profile-picture-overlay {
        opacity: 1;
    }

    .profile-picture-overlay i {
        font-size: 2rem;
        color: white;
    }

    .upload-btn {
        background: #2e7d32;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        margin-top: 1rem;
        width: 100%;
        transition: all 0.3s;
    }

    .upload-btn:hover {
        background: #1b5e20;
        transform: translateY(-2px);
    }

    .user-role {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .user-role.buyer {
        background: #e3f2fd;
        color: #1976d2;
    }

    .role-change-box {
        background: #fff3e0;
        border: 2px dashed #f57c00;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        text-align: center;
    }

    .role-change-box h4 {
        color: #f57c00;
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
    }

    .role-change-box p {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .btn-upgrade {
        background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-upgrade:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 124, 0, 0.3);
    }

    .profile-main {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .section-title {
        font-size: 1.3rem;
        color: #333;
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s;
        font-family: 'Poppins', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #2e7d32;
        box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
        color: white;
        padding: 0.85rem 2rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(to right, transparent, #e0e0e0, transparent);
        margin: 2rem 0;
    }

    .info-box {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #2e7d32;
    }

    .info-box p {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .profile-sidebar {
            order: -1;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <h1><i class="bi bi-person-circle"></i> My Profile</h1>
        <p>Manage your account information and settings</p>
    </div>

    <div class="profile-content">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-picture-wrapper">
                <?php if (!empty($user['profile_image']) && file_exists(PUBLIC_PATH . '/assets/uploads/profiles/' . $user['profile_image'])): ?>
                    <img src="<?php echo BASE_URL; ?>/assets/uploads/profiles/<?php echo $user['profile_image']; ?>" 
                         alt="Profile Picture" 
                         class="profile-picture"
                         id="profilePreview">
                <?php else: ?>
                    <div class="profile-picture" id="profilePreview" style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">
                        <i class="bi bi-person-circle"></i>
                    </div>
                <?php endif; ?>
                <div class="profile-picture-overlay" onclick="document.getElementById('profileImageInput').click()">
                    <i class="bi bi-camera-fill"></i>
                </div>
            </div>

            <h3 style="margin: 0 0 0.5rem 0;"><?php echo Security::escape($user['full_name']); ?></h3>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">
                <?php echo Security::escape($user['email']); ?>
            </p>

            <span class="user-role <?php echo $user['role'] === 'user' ? '' : 'buyer'; ?>">
                <i class="bi bi-<?php echo $user['role'] === 'buyer' ? 'shop' : 'person'; ?>"></i>
                <?php echo ucfirst($user['role']); ?>
            </span>

            <?php if ($user['role'] === 'user'): ?>
                <div class="role-change-box">
                    <h4><i class="bi bi-arrow-up-circle"></i> Become a Seller</h4>
                    <p>Start selling your agricultural products and grow your business!</p>
                    <button type="button" class="btn-upgrade" onclick="upgradeToSeller()">
                        <i class="bi bi-rocket-takeoff"></i> Upgrade Account
                    </button>
                </div>
            <?php endif; ?>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
                <p style="font-size: 0.8rem; color: #999;">
                    Member since<br>
                    <strong style="color: #333;"><?php echo date('F Y', strtotime($user['created_at'])); ?></strong>
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="profile-main">
            <form method="POST" enctype="multipart/form-data">
                <?php echo Security::csrfField(); ?>
                
                <!-- Hidden file input for profile picture -->
                <input type="file" 
                       name="profile_image" 
                       id="profileImageInput" 
                       accept="image/jpeg,image/png,image/jpg" 
                       style="display: none;"
                       onchange="previewProfileImage(this)">

                <!-- Personal Information -->
                <h2 class="section-title">
                    <i class="bi bi-person-badge"></i> Personal Information
                </h2>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" 
                               name="full_name" 
                               class="form-control" 
                               value="<?php echo Security::escape($user['full_name']); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" 
                               class="form-control" 
                               value="<?php echo Security::escape($user['email']); ?>"
                               disabled
                               style="background: #f5f5f5;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" 
                               name="phone" 
                               class="form-control" 
                               value="<?php echo Security::escape($user['phone'] ?? ''); ?>"
                               placeholder="+1 (234) 567-8900">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account Role</label>
                        <input type="text" 
                               class="form-control" 
                               value="<?php echo ucfirst($user['role']); ?>"
                               disabled
                               style="background: #f5f5f5;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" 
                              class="form-control" 
                              rows="3" 
                              placeholder="Enter your full address"><?php echo Security::escape($user['address'] ?? ''); ?></textarea>
                </div>

                <div class="section-divider"></div>

                <!-- Password Change -->
                <h2 class="section-title">
                    <i class="bi bi-shield-lock"></i> Change Password
                </h2>

                <div class="info-box">
                    <p><i class="bi bi-info-circle"></i> Leave password fields empty if you don't want to change your password</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" 
                           name="current_password" 
                           class="form-control" 
                           placeholder="Enter current password">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" 
                               name="new_password" 
                               class="form-control" 
                               placeholder="Minimum 6 characters">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" 
                               name="password_confirm" 
                               class="form-control" 
                               placeholder="Re-enter new password">
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upgrade Modal -->
<div class="modal-overlay" id="upgradeModal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="bi bi-rocket-takeoff"></i> Upgrade to Seller Account</h3>
            <button class="modal-close" onclick="closeUpgradeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 4rem; color: #f57c00; margin-bottom: 1rem;">
                    <i class="bi bi-shop"></i>
                </div>
                <h4 style="margin-bottom: 1rem;">Start Selling Today!</h4>
                <p style="color: #666; margin-bottom: 1.5rem;">
                    Upgrade your account to start listing and selling agricultural products on AgriSmart.
                </p>
                <div style="background: #f9f9f9; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: left;">
                    <p style="margin: 0.5rem 0;"><i class="bi bi-check-circle-fill" style="color: #2e7d32;"></i> List unlimited products</p>
                    <p style="margin: 0.5rem 0;"><i class="bi bi-check-circle-fill" style="color: #2e7d32;"></i> Manage your inventory</p>
                    <p style="margin: 0.5rem 0;"><i class="bi bi-check-circle-fill" style="color: #2e7d32;"></i> Track your orders</p>
                    <p style="margin: 0.5rem 0;"><i class="bi bi-check-circle-fill" style="color: #2e7d32;"></i> Grow your business</p>
                </div>
                <form method="POST" action="<?php echo BASE_URL; ?>/dashboard/upgrade-to-seller">
                    <?php echo Security::csrfField(); ?>
                    <button type="submit" class="btn-upgrade" style="width: 100%; padding: 1rem;">
                        <i class="bi bi-arrow-up-circle"></i> Confirm Upgrade
                    </button>
                </form>
                <button type="button" 
                        onclick="closeUpgradeModal()" 
                        style="margin-top: 1rem; background: #e0e0e0; color: #333; padding: 0.75rem; border: none; border-radius: 8px; width: 100%; cursor: pointer;">
                    Maybe Later
                </button>
            </div>
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
    color: #f57c00;
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
</style>

<script>
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePreview');
            preview.style.backgroundImage = 'none';
            preview.innerHTML = '';
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'profile-picture';
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            
            preview.parentNode.replaceChild(img, preview);
            img.id = 'profilePreview';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function upgradeToSeller() {
    document.getElementById('upgradeModal').style.display = 'flex';
}

function closeUpgradeModal() {
    document.getElementById('upgradeModal').style.display = 'none';
}

// Close modal on overlay click
document.getElementById('upgradeModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpgradeModal();
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

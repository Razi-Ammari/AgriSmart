/**
 * AgriSmart - Interactive JavaScript
 * Modern, Smooth Animations and User Interactions
 */

// ========================================
// INITIALIZATION
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initAnimations();
    initForms();
    initAlerts();
    initTooltips();
    initImagePreview();
    initPasswordToggle();
    initCounters();
    initSearchFilters();
});

// ========================================
// NAVBAR SCROLL EFFECT
// ========================================
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        // Add shadow on scroll
        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });
}

// ========================================
// SCROLL ANIMATIONS
// ========================================
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all cards and elements with fade-in class
    document.querySelectorAll('.card, .fade-in, .product-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// ========================================
// FORM ENHANCEMENTS
// ========================================
function initForms() {
    // Floating labels
    const floatingInputs = document.querySelectorAll('.form-floating input, .form-floating textarea');
    floatingInputs.forEach(input => {
        // Check if already has value on load
        if (input.value) {
            input.classList.add('has-value');
        }
        
        input.addEventListener('blur', () => {
            if (input.value) {
                input.classList.add('has-value');
            } else {
                input.classList.remove('has-value');
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            }
        });
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                input.style.borderColor = 'var(--danger)';
            } else {
                input.style.borderColor = 'var(--primary)';
            }
        });
    });
}

// ========================================
// AUTO-DISMISS ALERTS
// ========================================
function initAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Add close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = 'btn-close';
        closeBtn.style.cssText = 'background: none; border: none; font-size: 1.5rem; cursor: pointer; margin-left: auto;';
        
        closeBtn.addEventListener('click', () => {
            alert.style.animation = 'slideOutUp 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        });
        
        alert.appendChild(closeBtn);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alert.parentElement) {
                alert.style.animation = 'slideOutUp 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
}

// ========================================
// TOOLTIPS (Simple Implementation)
// ========================================
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(el => {
        el.style.position = 'relative';
        
        el.addEventListener('mouseenter', (e) => {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-popup';
            tooltip.textContent = el.getAttribute('data-tooltip');
            tooltip.style.cssText = `
                position: absolute;
                background: var(--text-primary);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: var(--radius-sm);
                font-size: 0.875rem;
                white-space: nowrap;
                z-index: 1000;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%) translateY(-8px);
                opacity: 0;
                animation: fadeInUp 0.2s ease forwards;
            `;
            
            el.appendChild(tooltip);
        });
        
        el.addEventListener('mouseleave', () => {
            const tooltip = el.querySelector('.tooltip-popup');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
}

// ========================================
// IMAGE PREVIEW FOR FILE UPLOADS
// ========================================
function initImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                let preview = input.parentElement.querySelector('.image-preview');
                
                if (!preview) {
                    preview = document.createElement('div');
                    preview.className = 'image-preview mt-2';
                    preview.style.cssText = `
                        width: 200px;
                        height: 200px;
                        border-radius: var(--radius-md);
                        overflow: hidden;
                        box-shadow: var(--shadow-md);
                    `;
                    input.parentElement.appendChild(preview);
                }
                
                preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;" alt="Preview">`;
            };
            
            reader.readAsDataURL(file);
        });
    });
}

// ========================================
// PASSWORD VISIBILITY TOGGLE
// ========================================
function initPasswordToggle() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
        toggleBtn.className = 'password-toggle';
        toggleBtn.style.cssText = `
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 1.2rem;
        `;
        
        toggleBtn.addEventListener('click', () => {
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = 'password';
                toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
        
        wrapper.appendChild(toggleBtn);
    });
}

// ========================================
// ANIMATED COUNTERS
// ========================================
function initCounters() {
    const counters = document.querySelectorAll('.stat-value, .counter');
    
    const animateCounter = (element) => {
        const target = parseInt(element.textContent.replace(/[^0-9]/g, ''));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString();
            }
        };
        
        updateCounter();
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
}

// ========================================
// SEARCH & FILTER
// ========================================
function initSearchFilters() {
    const searchInput = document.querySelector('#search-input');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Implement search logic here
                console.log('Searching for:', e.target.value);
            }, 500);
        });
    }
    
    if (filterButtons.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Implement filter logic here
            });
        });
    }
}

// ========================================
// CART FUNCTIONALITY
// ========================================
function updateCartQuantity(cartId, quantity) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/projet/public/cart/update';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="${csrfToken}">
        <input type="hidden" name="cart_id" value="${cartId}">
        <input type="hidden" name="quantity" value="${quantity}">
    `;
    
    document.body.appendChild(form);
    form.submit();
}

function removeFromCart(cartId) {
    if (!confirm('Remove this item from cart?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/projet/public/cart/remove';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="${csrfToken}">
        <input type="hidden" name="cart_id" value="${cartId}">
    `;
    
    document.body.appendChild(form);
    form.submit();
}

// ========================================
// CONFIRM DELETE
// ========================================
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// ========================================
// SMOOTH SCROLL
// ========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ========================================
// MOBILE MENU TOGGLE
// ========================================
const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const navMenu = document.querySelector('.navbar-nav');

if (mobileMenuBtn && navMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        mobileMenuBtn.classList.toggle('active');
    });
}

// ========================================
// IMAGE LAZY LOADING
// ========================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// ========================================
// PRICE RANGE SLIDER
// ========================================
function initPriceRange() {
    const minPrice = document.querySelector('#min-price');
    const maxPrice = document.querySelector('#max-price');
    const minDisplay = document.querySelector('#min-price-display');
    const maxDisplay = document.querySelector('#max-price-display');
    
    if (minPrice && maxPrice) {
        minPrice.addEventListener('input', () => {
            if (minDisplay) minDisplay.textContent = `$${minPrice.value}`;
        });
        
        maxPrice.addEventListener('input', () => {
            if (maxDisplay) maxDisplay.textContent = `$${maxPrice.value}`;
        });
    }
}

initPriceRange();

// ========================================
// ADD TO CART ANIMATION
// ========================================
function addToCartAnimation(productId) {
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    if (!productCard) return;
    
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.textContent = '✓ Added to cart!';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--success);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// ========================================
// PASSWORD TOGGLE VISIBILITY
// ========================================
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// ========================================
// EXPORT FUNCTIONS FOR GLOBAL USE
// ========================================
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.confirmDelete = confirmDelete;
window.addToCartAnimation = addToCartAnimation;
window.togglePassword = togglePassword;

/**
 * Visual Enhancements Library for School Management System
 * Provides loading animations, error handling, and UI improvements
 */

class VisualEnhancements {
    constructor() {
        this.loadingOverlay = null;
        this.init();
    }

    init() {
        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupEnhancements());
        } else {
            this.setupEnhancements();
        }
    }

    setupEnhancements() {
        this.setupImageLoading();
        this.setupFormEnhancements();
        this.setupPageTransitions();
        this.setupPrintOptimization();
    }

    // ==================== LOADING ANIMATIONS ====================

    /**
     * Show global loading overlay
     * @param {string} message - Loading message to display
     */
    showLoading(message = 'Loading') {
        if (this.loadingOverlay) return;

        this.loadingOverlay = document.createElement('div');
        this.loadingOverlay.className = 'loading-overlay';
        this.loadingOverlay.innerHTML = `
            <div>
                <div class="loading-spinner"></div>
                <div class="loading-text">${message}</div>
            </div>
        `;
        document.body.appendChild(this.loadingOverlay);
    }

    /**
     * Hide global loading overlay
     */
    hideLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.remove();
            this.loadingOverlay = null;
        }
    }

    /**
     * Show loading state on specific button
     * @param {HTMLElement} button - Button element
     */
    buttonLoading(button) {
        if (!button) return;
        button.classList.add('btn-loading');
        button.disabled = true;
    }

    /**
     * Hide loading state on button
     * @param {HTMLElement} button - Button element
     */
    buttonLoaded(button) {
        if (!button) return;
        button.classList.remove('btn-loading');
        button.disabled = false;
    }

    /**
     * Show loading state on form
     * @param {HTMLElement} form - Form element
     */
    formLoading(form) {
        if (!form) return;
        form.classList.add('form-loading');
        // Disable all form inputs
        const inputs = form.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => input.disabled = true);
    }

    /**
     * Hide loading state on form
     * @param {HTMLElement} form - Form element
     */
    formLoaded(form) {
        if (!form) return;
        form.classList.remove('form-loading');
        // Re-enable all form inputs
        const inputs = form.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => input.disabled = false);
    }

    // ==================== IMAGE LOADING ====================

    setupImageLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        // Intersection Observer for lazy loading
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    imageObserver.unobserve(entry.target);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    /**
     * Load image with loading placeholder
     * @param {HTMLElement} img - Image element
     */
    loadImage(img) {
        const container = img.parentElement;
        
        // Add loading placeholder if container has img-container class
        if (container.classList.contains('img-container')) {
            const placeholder = document.createElement('div');
            placeholder.className = 'img-loading';
            placeholder.innerHTML = '<i class="fas fa-image"></i>';
            container.appendChild(placeholder);
        }

        // Load the actual image
        const actualImg = new Image();
        actualImg.onload = () => {
            img.src = actualImg.src;
            img.classList.add('responsive-img');
            // Remove placeholder
            const placeholder = container.querySelector('.img-loading');
            if (placeholder) placeholder.remove();
        };
        
        actualImg.onerror = () => {
            // Show error placeholder
            const errorPlaceholder = document.createElement('div');
            errorPlaceholder.className = 'img-placeholder';
            errorPlaceholder.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            img.style.display = 'none';
            container.appendChild(errorPlaceholder);
        };
        
        actualImg.src = img.dataset.src;
    }

    // ==================== FORM ENHANCEMENTS ====================

    setupFormEnhancements() {
        // Auto-submit protection
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            let isSubmitting = false;
            
            form.addEventListener('submit', (e) => {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }
                
                isSubmitting = true;
                this.formLoading(form);
                
                // Re-enable after 5 seconds as safety measure
                setTimeout(() => {
                    isSubmitting = false;
                    this.formLoaded(form);
                }, 5000);
            });
        });

        // Enhanced input interactions
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            // Add focus effects
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('input-focused');
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('input-focused');
            });
        });
    }

    // ==================== PAGE TRANSITIONS ====================

    setupPageTransitions() {
        // Add page transition class to main content
        const mainContent = document.querySelector('.main-content, main');
        if (mainContent) {
            mainContent.classList.add('page-transition');
        }

        // Smooth navigation with loading
        const navLinks = document.querySelectorAll('a:not([href^="#"]):not([target="_blank"])');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Only for internal navigation
                if (link.hostname === window.location.hostname) {
                    this.showLoading('Navigating...');
                }
            });
        });
    }

    // ==================== PRINT OPTIMIZATION ====================

    setupPrintOptimization() {
        // Add print header/footer
        const printHeader = document.createElement('div');
        printHeader.className = 'print-header d-none';
        printHeader.innerHTML = `
            <h2>School Management System</h2>
            <p>Academic Report - Generated on ${new Date().toLocaleDateString()}</p>
        `;
        document.body.insertBefore(printHeader, document.body.firstChild);

        const printFooter = document.createElement('div');
        printFooter.className = 'print-footer d-none';
        printFooter.innerHTML = `
            <p>© ${new Date().getFullYear()} School Management System - Page <span class="page-number"></span></p>
        `;
        document.body.appendChild(printFooter);

        // Print button functionality
        const printButtons = document.querySelectorAll('[data-print]');
        printButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                this.optimizeForPrint();
                window.print();
            });
        });
    }

    optimizeForPrint() {
        // Show print-specific elements
        document.querySelectorAll('.print-header, .print-footer').forEach(el => {
            el.classList.remove('d-none');
        });

        // Add page breaks for large content
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            if (index > 0 && index % 3 === 0) {
                card.classList.add('page-break');
            }
        });
    }

    // ==================== SKELETON LOADING ====================

    /**
     * Create skeleton loading placeholder
     * @param {HTMLElement} container - Container to add skeleton
     * @param {number} lines - Number of skeleton lines
     */
    showSkeleton(container, lines = 3) {
        if (!container) return;
        
        container.innerHTML = '';
        for (let i = 0; i < lines; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = 'skeleton skeleton-text';
            container.appendChild(skeleton);
        }
    }

    /**
     * Create skeleton for a card layout
     * @param {HTMLElement} container - Container element
     */
    showCardSkeleton(container) {
        if (!container) return;
        
        container.innerHTML = `
            <div class="skeleton skeleton-circle mb-3" style="width: 60px; height: 60px;"></div>
            <div class="skeleton skeleton-text mb-2"></div>
            <div class="skeleton skeleton-text mb-2"></div>
            <div class="skeleton skeleton-text" style="width: 60%;"></div>
        `;
    }

    // ==================== NOTIFICATION SYSTEM ====================

    /**
     * Show toast notification
     * @param {string} message - Notification message
     * @param {string} type - success, error, warning, info
     * @param {number} duration - Duration in milliseconds
     */
    showToast(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${this.getAlertType(type)} modern-alert position-fixed`;
        toast.style.cssText = `
            top: 20px; right: 20px; z-index: 9999; 
            min-width: 300px; animation: slideInRight 0.3s ease;
        `;
        
        toast.innerHTML = `
            <div class="alert-icon">
                <i class="fas ${this.getToastIcon(type)}"></i>
            </div>
            <div class="alert-content">
                <p class="alert-message mb-0">${message}</p>
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;

        document.body.appendChild(toast);

        // Auto-remove after duration
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }
        }, duration);
    }

    getAlertType(type) {
        const types = {
            success: 'success',
            error: 'danger',
            warning: 'warning',
            info: 'info'
        };
        return types[type] || 'info';
    }

    getToastIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-triangle',
            warning: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        return icons[type] || 'fa-info-circle';
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Smooth scroll to element
     * @param {string} selector - CSS selector
     */
    scrollToElement(selector) {
        const element = document.querySelector(selector);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    /**
     * Animate number counter
     * @param {HTMLElement} element - Element containing number
     * @param {number} target - Target number
     * @param {number} duration - Animation duration in ms
     */
    animateCounter(element, target, duration = 2000) {
        const start = parseInt(element.textContent) || 0;
        const range = target - start;
        const startTime = performance.now();

        const updateCounter = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(start + (range * this.easeOutCubic(progress)));
            element.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        };

        requestAnimationFrame(updateCounter);
    }

    easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }
}

// Initialize Visual Enhancements
const visualEnhancements = new VisualEnhancements();

// Expose globally for use in other scripts
window.VE = visualEnhancements;

// CSS animations for notifications
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .input-focused {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
`;
document.head.appendChild(notificationStyles);
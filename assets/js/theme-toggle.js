/**
 * Dark Mode Theme Toggle System
 * Provides complete theme switching functionality for the School Management System
 */

class ThemeToggle {
    constructor() {
        this.storageKey = 'school-erp-theme';
        this.currentTheme = this.getStoredTheme() || this.getPreferredTheme();
        this.init();
    }

    init() {
        // Apply initial theme
        this.applyTheme(this.currentTheme);
        
        // Setup when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupToggle());
        } else {
            this.setupToggle();
        }

        // Listen for system theme changes
        this.watchSystemTheme();
    }

    setupToggle() {
        // Create theme toggle button if it doesn't exist
        this.createToggleButton();
        
        // Setup existing toggle buttons
        const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => this.toggleTheme());
        });

        // Update toggle button state
        this.updateToggleButtons();
    }

    createToggleButton() {
        // Add to school header if it exists
        const schoolHeader = document.querySelector('.school-header .ms-auto');
        if (schoolHeader && !schoolHeader.querySelector('[data-theme-toggle]')) {
            const toggleContainer = document.createElement('div');
            toggleContainer.className = 'theme-toggle me-3';
            toggleContainer.innerHTML = this.getToggleHTML();
            toggleContainer.setAttribute('data-theme-toggle', '');
            
            // Insert before the welcome text
            const welcomeText = schoolHeader.querySelector('.welcome-text, .text-dark');
            if (welcomeText) {
                schoolHeader.insertBefore(toggleContainer, welcomeText);
            } else {
                schoolHeader.insertBefore(toggleContainer, schoolHeader.firstChild);
            }
        }

        // Add to main navigation if school header doesn't exist
        const navbar = document.querySelector('.navbar .container-fluid, .navbar .d-flex');
        if (navbar && !navbar.querySelector('[data-theme-toggle]') && !schoolHeader) {
            const toggleContainer = document.createElement('div');
            toggleContainer.className = 'theme-toggle';
            toggleContainer.innerHTML = this.getToggleHTML();
            toggleContainer.setAttribute('data-theme-toggle', '');
            navbar.appendChild(toggleContainer);
        }
    }

    getToggleHTML() {
        return `
            <span class="theme-toggle-label d-none d-md-inline">Theme</span>
            <div class="theme-toggle-switch">
                <div class="theme-toggle-slider">
                    <i class="fas fa-sun icon-light"></i>
                    <i class="fas fa-moon icon-dark"></i>
                </div>
            </div>
        `;
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
        
        // Animate the toggle
        this.animateToggle();
        
        // Show notification
        if (window.VE) {
            const message = newTheme === 'dark' ? 
                '🌙 Switched to dark mode' : 
                '☀️ Switched to light mode';
            window.VE.showToast(message, 'info', 2000);
        }
    }

    setTheme(theme) {
        this.currentTheme = theme;
        this.applyTheme(theme);
        this.storeTheme(theme);
        this.updateToggleButtons();
        
        // Dispatch custom event
        window.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme } 
        }));
    }

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.body.className = document.body.className.replace(/theme-\w+/g, '') + ` theme-${theme}`;
        
        // Update meta theme-color for mobile browsers
        this.updateMetaThemeColor(theme);
    }

    updateMetaThemeColor(theme) {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        
        const colors = {
            light: '#edaa25', // amber
            dark: '#0f172a'   // dark blue
        };
        
        metaThemeColor.content = colors[theme];
    }

    updateToggleButtons() {
        const toggles = document.querySelectorAll('[data-theme-toggle]');
        toggles.forEach(toggle => {
            const isDark = this.currentTheme === 'dark';
            toggle.setAttribute('data-current-theme', this.currentTheme);
            
            // Update ARIA attributes for accessibility
            toggle.setAttribute('aria-label', `Switch to ${isDark ? 'light' : 'dark'} mode`);
            toggle.setAttribute('title', `Switch to ${isDark ? 'light' : 'dark'} mode`);
        });
    }

    animateToggle() {
        const sliders = document.querySelectorAll('.theme-toggle-slider');
        sliders.forEach(slider => {
            slider.style.transform = 'scale(0.8)';
            setTimeout(() => {
                slider.style.transform = '';
            }, 150);
        });
    }

    getStoredTheme() {
        return localStorage.getItem(this.storageKey);
    }

    storeTheme(theme) {
        localStorage.setItem(this.storageKey, theme);
    }

    getPreferredTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    watchSystemTheme() {
        if (!window.matchMedia) return;
        
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', (e) => {
            // Only auto-switch if user hasn't manually set a preference
            if (!this.getStoredTheme()) {
                const newTheme = e.matches ? 'dark' : 'light';
                this.setTheme(newTheme);
            }
        });
    }

    // Public API methods
    getCurrentTheme() {
        return this.currentTheme;
    }

    setLightTheme() {
        this.setTheme('light');
    }

    setDarkTheme() {
        this.setTheme('dark');
    }

    resetToSystemTheme() {
        localStorage.removeItem(this.storageKey);
        const systemTheme = this.getPreferredTheme();
        this.setTheme(systemTheme);
    }
}

// Initialize theme system immediately (before DOM ready)
const themeToggle = new ThemeToggle();

// Expose globally for use in other scripts
window.ThemeToggle = themeToggle;

// Add keyboard shortcut for theme toggle
document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + Shift + D for theme toggle
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
        e.preventDefault();
        themeToggle.toggleTheme();
    }
});

// Automatic theme switching based on time (optional feature)
class AutoThemeScheduler {
    constructor(themeToggle) {
        this.themeToggle = themeToggle;
        this.enabled = false;
        this.lightHour = 7;   // 7 AM
        this.darkHour = 19;   // 7 PM
    }

    enable() {
        this.enabled = true;
        this.checkTimeAndSetTheme();
        
        // Check every hour
        setInterval(() => {
            if (this.enabled) {
                this.checkTimeAndSetTheme();
            }
        }, 60 * 60 * 1000);
    }

    disable() {
        this.enabled = false;
    }

    checkTimeAndSetTheme() {
        const currentHour = new Date().getHours();
        const shouldBeDark = currentHour >= this.darkHour || currentHour < this.lightHour;
        
        const targetTheme = shouldBeDark ? 'dark' : 'light';
        if (this.themeToggle.getCurrentTheme() !== targetTheme) {
            this.themeToggle.setTheme(targetTheme);
        }
    }

    setSchedule(lightHour, darkHour) {
        this.lightHour = lightHour;
        this.darkHour = darkHour;
        if (this.enabled) {
            this.checkTimeAndSetTheme();
        }
    }
}

// Expose auto scheduler
window.AutoThemeScheduler = new AutoThemeScheduler(themeToggle);

// Theme-aware form improvements
document.addEventListener('themeChanged', (e) => {
    const { theme } = e.detail;
    
    // Update charts if they exist (for future chart integration)
    if (window.chartInstances) {
        window.chartInstances.forEach(chart => {
            chart.options.plugins.legend.labels.color = theme === 'dark' ? '#f9fafb' : '#1f2937';
            chart.options.scales.x.ticks.color = theme === 'dark' ? '#d1d5db' : '#374151';
            chart.options.scales.y.ticks.color = theme === 'dark' ? '#d1d5db' : '#374151';
            chart.update();
        });
    }
    
    // Update code highlighting if present
    if (window.Prism) {
        Prism.highlightAll();
    }
});

console.log('🎨 Dark Mode system initialized successfully!');
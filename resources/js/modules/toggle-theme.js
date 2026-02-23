// Toggle Theme Module

/**
 * Global theme initializer — runs immediately (before CSS/Alpine).
 * Reads localStorage.theme or system preference, applies dark class + colorScheme.
 * Exposes window.__setTheme() for programmatic use.
 * Re-applies on Livewire navigated events.
 */
export function initTheme() {
    const d = document.documentElement;

    function computeDark() {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') return true;
        if (saved === 'light') return false;
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function applyTheme() {
        const dark = computeDark();
        d.classList.toggle('dark', dark);
        d.style.colorScheme = dark ? 'dark' : 'light';
    }

    // Apply theme (acts as fallback if <x-bt-theme-head /> is not in <head>)
    applyTheme();

    // Expose global setter for external use
    window.__setTheme = function (mode) {
        const dark = mode === 'dark';
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        applyTheme();
        window.dispatchEvent(new CustomEvent('theme-change', { detail: { theme: dark ? 'dark' : 'light' } }));
    };

    // MutationObserver catches Livewire wire:navigate morphing <html> class.
    // Fires before the browser repaints, preventing the light-mode flash.
    if (!window.__btThemeGuard) {
        window.__btThemeGuard = true;
        new MutationObserver(() => {
            const dark = computeDark();
            if (d.classList.contains('dark') !== dark) {
                d.classList.toggle('dark', dark);
                d.style.colorScheme = dark ? 'dark' : 'light';
            }
        }).observe(d, { attributes: true, attributeFilter: ['class'] });
    }
}

/**
 * Alpine.js component for ToggleTheme.
 * Manages dark state, toggle interaction, rotation animation, and event sync.
 */
export function btToggleTheme() {
    return {
        dark: localStorage.theme === 'dark'
            || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        rotating: false,

        init() {
            window.addEventListener('theme-change', (e) => {
                if (e.detail && e.detail.theme) {
                    this.dark = (e.detail.theme === 'dark');
                }
            });
        },

        toggle() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            document.documentElement.style.colorScheme = this.dark ? 'dark' : 'light';
            localStorage.theme = this.dark ? 'dark' : 'light';
            window.dispatchEvent(new CustomEvent('theme-change', { detail: { theme: this.dark ? 'dark' : 'light' } }));
            this.$nextTick(() => {
                this.rotating = true;
                setTimeout(() => { this.rotating = false; }, 500);
            });
        },
    };
}

// Toggle Theme Module

/**
 * Global theme initializer — runs immediately (before CSS/Alpine).
 * Reads localStorage.theme or system preference, applies dark class + colorScheme.
 * Exposes window.__setTheme() for programmatic use.
 * Re-applies on Livewire navigated events.
 */
export function initTheme() {
    function computeDark() {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') return true;
        if (saved === 'light') return false;
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function applyTheme(dark) {
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
    }

    // Apply before CSS loads to prevent FOUC
    applyTheme(computeDark());

    // Expose global setter for external use
    window.__setTheme = function (mode) {
        const dark = mode === 'dark';
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        applyTheme(dark);
        window.dispatchEvent(new CustomEvent('theme-change', { detail: { theme: dark ? 'dark' : 'light' } }));
    };

    // Reapply on each Livewire navigation
    window.addEventListener('livewire:navigated', () => {
        applyTheme(computeDark());
    });
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

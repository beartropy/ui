/**
 * Alpine.js component for the Command Palette.
 *
 * Provides keyboard-navigable search with fuzzy multi-term filtering,
 * and execute actions (route:, url:, dispatch:, js:, or direct URLs).
 */
export function btCommandPalette({ initial }) {
    return {
        open: false,
        query: '',
        all: initial || [],
        selectedIndex: 0,

        get filtered() {
            const q = (this.query || '').toLowerCase().trim();
            if (!q) return (this.all || []).slice(0, 5);

            const terms = q.split(/\s+/);
            const results = (this.all || []).filter(i => {
                const text = [
                    i.title ?? '',
                    i.description ?? '',
                    Array.isArray(i.tags) ? i.tags.join(' ') : '',
                    i.action ?? ''
                ].join(' ').toLowerCase();
                return terms.every(t => text.includes(t));
            });

            if (results.length && this.selectedIndex >= results.length) this.selectedIndex = 0;
            return results;
        },

        scrollIntoView() {
            this.$nextTick(() => {
                const el = document.querySelector(`[data-cp-index="${this.selectedIndex}"]`);
                if (el) el.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            });
        },

        handleKey(e) {
            if (!this.filtered.length) return;

            if (['ArrowDown', 'Tab'].includes(e.key) && !e.shiftKey) {
                e.preventDefault();
                this.selectedIndex = (this.selectedIndex + 1) % this.filtered.length;
                this.scrollIntoView();
            } else if (['ArrowUp'].includes(e.key) || (e.key === 'Tab' && e.shiftKey)) {
                e.preventDefault();
                this.selectedIndex = (this.selectedIndex - 1 + this.filtered.length) % this.filtered.length;
                this.scrollIntoView();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const item = this.filtered[this.selectedIndex];
                if (item) this.execute(item);
            }
        },

        execute(item) {
            const action  = (item.action || '').trim();
            const routes  = this.routes || {};
            const target  = (item.target || item?.options?.target || '_self').toLowerCase();

            const openUrl = (url) => {
                if (!url) return;
                if (target === '_blank') {
                    window.open(url, '_blank', 'noopener,noreferrer');
                } else {
                    window.location.href = url;
                }
            };

            if (action.startsWith('route:')) {
                const name = action.replace('route:', '').trim();
                let url = null;

                if (typeof window.route === 'function') {
                    const params = item.params || item.route_params || {};
                    url = route(name, params);
                } else if (routes[name]) {
                    url = typeof routes[name] === 'function'
                        ? routes[name](item.params || item.route_params || {})
                        : routes[name];
                } else {
                    console.warn(`Could not resolve route "${name}".`);
                }

                openUrl(url);

            } else if (action.startsWith('url:')) {
                openUrl(action.replace('url:', '').trim());

            } else if (/^(https?:\/\/|\/)/i.test(action)) {
                openUrl(action);

            } else if (action.startsWith('dispatch:')) {
                this.$dispatch(action.replace('dispatch:', '').trim());

            } else if (action.startsWith('js:')) {
                try { eval(action.replace('js:', '')); } catch (e) { console.error(e); }
            }

            this.open = false;
        },
    }
}

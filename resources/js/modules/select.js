// Select Module
export function beartropySelect(cfg) {
    return {
        _cfg: cfg,
        value: cfg.value,
        open: false,
        options: cfg.options,
        search: '',
        highlightedIndex: -1,
        isMulti: cfg.isMulti,
        maxChips: 3,
        perPage: cfg.perPage,
        page: 1,
        hasMore: false,
        loading: false,
        remoteUrl: cfg.remoteUrl,
        initDone: false,
        autosave: cfg.autosave,
        autosaveMethod: cfg.autosaveMethod,
        autosaveKey: cfg.autosaveKey,
        autosaveDebounce: cfg.autosaveDebounce,
        saveState: 'idle',
        _saveT: null,
        hasFieldError: cfg.hasFieldError,
        showSpinner: cfg.showSpinner,

        init() {
            if (this._cfg.hasWireModel) {
                if (this.isMulti) {
                    this.$watch('$wire.' + this._cfg.name, v => { this.value = v; this.syncInput(); });
                } else {
                    this.$watch('$wire.' + this._cfg.name, v => { this.value = v; });
                }
            }

            if (this.isMulti && Array.isArray(this.value)) {
                this.value = this.value.map(String);
            }

            if (!this._cfg.defer && this.remoteUrl) {
                this.fetchOptions(true);
                this.initDone = true;
            }

            this.$watch('search', () => {
                this.page = 1;
                this.highlightedIndex = 0;
                this.fetchOptions(true);
            });

            this.$watch('open', (v) => {
                if (v) { this.focusSearch(); }
            });
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.highlightedIndex = this.filteredOptions().length ? 0 : -1;
                this.focusSearch();
                if (this.remoteUrl && !this.initDone) {
                    this.page = 1;
                    this.fetchOptions(true);
                    this.initDone = true;
                } else if (this.remoteUrl && this.hasMore) {
                    this._fillIfNeeded();
                }
            }
        },

        close() {
            this.open = false;
            this.highlightedIndex = -1;
        },

        move(delta) {
            if (!this.open || !this.filteredOptions().length) { return; }
            const n = this.filteredOptions().length;
            this.highlightedIndex = (this.highlightedIndex + delta + n) % n;
            this.scrollHighlightedIntoView();
        },

        selectHighlighted() {
            const entries = this.filteredOptions();
            if (this.highlightedIndex >= 0 && this.highlightedIndex < entries.length) {
                this.setValue(entries[this.highlightedIndex][0]);
            }
        },

        scrollHighlightedIntoView() {
            this.$nextTick(() => {
                const list = document.getElementById(this._cfg.selectId + '-list');
                if (!list) { return; }
                const el = list.querySelector('[data-select-index="' + this.highlightedIndex + '"]');
                if (el) { el.scrollIntoView({ block: 'nearest' }); }
            });
        },

        triggerAutosave() {
            if (!this.autosave || !this.autosaveMethod || !this.autosaveKey) { return; }

            clearTimeout(this._saveT);
            this.saveState = 'saving';

            this._saveT = setTimeout(() => {
                this.$wire.call(this.autosaveMethod, this.value, this.autosaveKey)
                    .then(() => {
                        this.saveState = 'ok';
                        setTimeout(() => { if (this.saveState === 'ok') { this.saveState = 'idle'; } }, 2000);
                    })
                    .catch(() => {
                        this.saveState = 'error';
                        setTimeout(() => { if (this.saveState === 'error') { this.saveState = 'idle'; } }, 3000);
                    });
            }, this.autosaveDebounce);
        },

        filteredOptions() {
            if (this.remoteUrl) {
                return Object.entries(this.options);
            }
            const entries = Object.entries(this.options);
            if (!this.search) { return entries; }
            return entries.filter(([id, opt]) =>
                (opt.label ?? opt ?? id).toLowerCase().includes(this.search.toLowerCase())
            );
        },

        isSelected(id) {
            if (this.isMulti) {
                return Array.isArray(this.value)
                    ? this.value.map(String).includes(String(id))
                    : false;
            }
            return String(this.value) === String(id);
        },

        setValue(id) {
            id = String(id);
            if (this.isMulti) {
                if (!Array.isArray(this.value)) { this.value = []; }
                const valueStr = this.value.map(String);
                if (valueStr.includes(id)) {
                    this.value = valueStr.filter(v => v !== id);
                } else {
                    this.value = valueStr.concat([id]);
                }
                this.syncInput();
            } else {
                this.value = id;
                this.syncInput();
                this.close();
            }
        },

        removeSelected(id) {
            if (!this.value) { return; }
            this.value = this.value.filter(v => v !== id);
            this.syncInput();
        },

        syncInput() {
            if (!this._cfg.hasWireModel) {
                this.$refs.multiInputs.innerHTML = '';
                if (this.isMulti) {
                    if (Array.isArray(this.value)) {
                        this.value.forEach(val => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = this._cfg.name + '[]';
                            input.value = val;
                            this.$refs.multiInputs.appendChild(input);
                        });
                    }
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = this._cfg.name;
                    input.value = this.value ?? '';
                    this.$refs.multiInputs.appendChild(input);
                }
            }

            if (this._cfg.hasWireModel) {
                this.$wire.set(this._cfg.name, this.value);
                this.triggerAutosave();
            }
        },

        visibleChips() {
            return Array.isArray(this.value) ? this.value.slice(0, this.maxChips) : [];
        },

        hiddenCount() {
            return Array.isArray(this.value) && this.value.length > this.maxChips
                ? this.value.length - this.maxChips
                : 0;
        },

        clearValue() {
            if (this.isMulti) {
                this.value = [];
            } else {
                this.value = '';
            }
            this.syncInput();
        },

        fetchOptions(reset = false) {
            if (!this.remoteUrl || this.loading) { return; }
            this.loading = true;
            let params = new URLSearchParams({
                q: this.search,
                page: this.page,
                per_page: this.perPage,
            }).toString();

            fetch(`${this.remoteUrl}?${params}`)
                .then(res => res.json())
                .then(data => {
                    if (reset) {
                        this.options = data.options || {};
                    } else {
                        this.options = Object.assign({}, this.options, data.options || {});
                    }
                    this.hasMore = data.hasMore;
                    this.loading = false;
                    this._fillIfNeeded();
                });
        },

        _fillIfNeeded() {
            if (!this.hasMore || this.loading || !this.open) { return; }
            setTimeout(() => {
                const el = document.getElementById(this._cfg.selectId + '-list');
                if (el && el.scrollHeight <= el.clientHeight + 10) {
                    this.page++;
                    this.fetchOptions();
                }
            }, 50);
        },

        focusSearch() {
            this.$nextTick(() => {
                requestAnimationFrame(() => {
                    // Try by known ID first (works even when teleported out of $root)
                    let el = document.getElementById(this._cfg.selectId + '-search');

                    if (!el && this.$refs.searchHost) {
                        el = this.$refs.searchHost.querySelector('[data-beartropy-input]');
                    }

                    if (!el) {
                        el = this.$root.querySelector('[data-beartropy-input]');
                    }

                    if (el) {
                        el.focus({ preventScroll: true });
                        try { el.select?.(); } catch (_) {}
                        this._bindSearchKeyboard(el);
                    }
                });
            });
        },

        _bindSearchKeyboard(el) {
            if (el._beartropyKeyBound) { return; }
            el._beartropyKeyBound = true;

            el.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.move(1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.move(-1);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (this.highlightedIndex >= 0) { this.selectHighlighted(); }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    this.close();
                }
            });
        },
    };
}

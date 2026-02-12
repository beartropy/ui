// Lookup Module
export function beartropyLookup(cfg) {
    return {
        // State
        open: false,
        highlighted: -1,
        options: [],
        filtered: [],

        // Config from Blade
        inputId: cfg.inputId,
        isLivewire: cfg.isLivewire ?? false,
        labelKey: cfg.labelKey ?? 'name',
        valueKey: cfg.valueKey ?? 'id',
        wireModelName: cfg.wireModelName ?? null,

        init() {
            this._syncOptionsFromAttr();

            // Watch for data-options attribute changes (Livewire morphdom)
            const obs = new MutationObserver(muts => {
                for (const m of muts) {
                    if (m.type === 'attributes' && m.attributeName === 'data-options') {
                        this._syncOptionsFromAttr();
                    }
                }
            });
            obs.observe(this.$el, { attributes: true });
            this._obs = obs;

            // Livewire 3 value sync via $watch (replaces LW2 message.processed hook)
            if (this.isLivewire && this.wireModelName) {
                this.$watch('$wire.' + this.wireModelName, () => {
                    this._syncFromLivewire();
                });
            }
        },

        // Data helpers
        _syncOptionsFromAttr() {
            const raw = this.$el.getAttribute('data-options') || '[]';
            try {
                const parsed = JSON.parse(raw);
                this.options = Array.isArray(parsed) ? parsed : [];
            } catch (_) {
                this.options = [];
            }
            this.filtered = this.options;
            if (this.highlighted >= this.filtered.length) {
                this.highlighted = this.filtered.length ? 0 : -1;
            }

            if (this.isLivewire) {
                this._syncFromLivewire();
            } else {
                this._reconcileFromVisible();
            }
        },

        _syncFromLivewire() {
            if (!this.isLivewire || !this.wireModelName || !this.$wire) { return; }

            const current = this.$wire.get(this.wireModelName) ?? '';

            if (!current) {
                this.setVisibleValue('');
                return;
            }

            // Find the option whose value matches the wire:model
            const match = this.options.find(
                o => String(this.getValue(o)) === String(current)
            );

            // If option found, use its label; otherwise show the raw value
            const label = match ? this.getLabel(match) : current;
            this.setVisibleValue(label);
        },

        normalize(s) {
            if (!s) { return ''; }
            return s.toString()
                .normalize('NFD').replace(/\p{Diacritic}/gu, '')
                .trim().toLowerCase();
        },

        getLabel(o) {
            return o?.[this.labelKey] ?? '';
        },

        getValue(o) {
            return o?.[this.valueKey] ?? '';
        },

        exactMatch(txt) {
            const t = this.normalize(txt);
            return this.options.find(o => this.normalize(this.getLabel(o)) === t) || null;
        },

        // Events / main logic
        onInput(e) {
            const raw = (e?.target?.value ?? '');
            const t = this.normalize(raw);

            this.filtered = !t
                ? this.options
                : this.options.filter(o => this.normalize(this.getLabel(o)).includes(t));

            this.open = true;
            this.highlighted = this.filtered.length ? 0 : -1;

            // Exact match => id, otherwise => free text
            this._setHiddenFromRawOrMatch(raw);
        },

        move(delta) {
            if (!this.open || !this.filtered.length) { return; }
            const n = this.filtered.length;
            this.highlighted = (this.highlighted + delta + n) % n;
        },

        choose(idx) {
            const opt = this.filtered[idx];
            if (!opt) { return; }

            this.setVisibleValue(this.getLabel(opt));

            if (this.isLivewire && this.$refs.livewireValue) {
                this.$refs.livewireValue.value = this.getValue(opt);
                this.$refs.livewireValue.dispatchEvent(new Event('input', { bubbles: true }));
            }

            this.open = false;
        },

        confirm() {
            if (this.highlighted >= 0) {
                this.choose(this.highlighted);
            } else {
                const raw = document.getElementById(this.inputId)?.value ?? '';
                this._setHiddenFromRawOrMatch(raw);
                this.open = false;
            }
        },

        close() {
            this.open = false;
        },

        setVisibleValue(v) {
            const el = document.getElementById(this.inputId);
            if (!el) { return; }
            el.value = v ?? '';
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },

        clearBoth() {
            this.setVisibleValue('');

            if (this.isLivewire && this.$refs.livewireValue) {
                this.$refs.livewireValue.value = '';
                this.$refs.livewireValue.dispatchEvent(new Event('input', { bubbles: true }));
            }

            this.filtered = this.options;
            this.highlighted = -1;
            this.open = false;
        },

        _setHiddenFromRawOrMatch(raw) {
            if (!this.isLivewire || !this.$refs.livewireValue) { return; }

            const match = this.exactMatch(raw);
            this.$refs.livewireValue.value = match ? this.getValue(match) : raw;
            this.$refs.livewireValue.dispatchEvent(new Event('input', { bubbles: true }));
        },

        _reconcileFromVisible() {
            const el = document.getElementById(this.inputId);
            if (!el) { return; }
            this._setHiddenFromRawOrMatch(el.value || '');
        },
    };
}

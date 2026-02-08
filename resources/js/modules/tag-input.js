// Tag Input Module
export function beartropyTagInput({ initialTags = [], unique = true, maxTags = null, disabled = false, separator = ',' }) {
    // Supports string (",; ") or array ([';', ',', ' '])
    let seps = Array.isArray(separator) ? separator : separator.split('');
    // Build regex like /[;, ]+/
    let sepRegex = new RegExp(`[${seps.map(s => s.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&')).join('')}]+`);
    return {
        tags: initialTags ?? [],
        input: '',
        unique,
        maxTags,
        disabled,
        separator,
        focusInput() { if (!this.disabled) this.$refs.input.focus(); },
        addTag() {
            let val = this.input.trim();
            if (!val) return this.input = '';
            // Split if separator found inside value
            let parts = val.split(sepRegex).map(t => t.trim()).filter(Boolean);
            parts.forEach(tag => this._tryAddTag(tag));
            this.input = '';
        },
        removeTag(i) { if (!this.disabled) this.tags.splice(i, 1); },
        clearAll() { if (!this.disabled) { this.tags = []; this.input = ''; } },
        removeOnBackspace(e) {
            if (!this.input && this.tags.length && !this.disabled) this.tags.pop();
        },
        addTagOnTab(e) {
            if (this.input) { this.addTag(); e.preventDefault(); }
        },
        handlePaste(e) {
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            if (paste && sepRegex.test(paste)) {
                sepRegex.lastIndex = 0;
                let newTags = paste.split(sepRegex).map(t => t.trim()).filter(Boolean);
                newTags.forEach(tag => this._tryAddTag(tag));
                e.preventDefault();
                this.input = '';
            }
        },
        _tryAddTag(tag) {
            if (!tag) return;
            if (this.unique && this.tags.includes(tag)) return;
            if (this.maxTags && this.tags.length >= this.maxTags) return;
            this.tags.push(tag);
        },
    };
}

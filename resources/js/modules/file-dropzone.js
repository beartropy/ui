export function beartropyFileDropzone(cfg) {
    return {
        files: [],
        existingFiles: [],
        dragging: false,
        uploading: false,
        progress: 0,
        errors: [],

        init() {
            if (cfg.existingFiles && Array.isArray(cfg.existingFiles)) {
                this.existingFiles = cfg.existingFiles.map((f, i) => ({
                    ...f,
                    id: 'existing-' + i + '-' + Date.now(),
                }));
            }
        },

        openPicker() {
            if (cfg.disabled) return;
            this.$refs.input.click();
        },

        addFiles(e) {
            const newFiles = Array.from(e.target?.files || e.dataTransfer?.files || []);
            if (!newFiles.length) return;

            this.errors = [];
            const valid = [];

            for (const file of newFiles) {
                if (cfg.accept && !this._matchesAccept(file, cfg.accept)) {
                    this.errors.push(cfg.i18n.file_type_not_accepted.replace(':name', file.name));
                    continue;
                }
                if (cfg.maxFileSize && file.size > cfg.maxFileSize) {
                    this.errors.push(
                        cfg.i18n.file_too_large
                            .replace(':name', file.name)
                            .replace(':max', this.formatSize(cfg.maxFileSize))
                    );
                    continue;
                }
                valid.push(file);
            }

            let toAdd = valid;

            if (cfg.maxFiles) {
                const currentCount = cfg.multiple ? this.files.length : 0;
                if (currentCount + toAdd.length > cfg.maxFiles) {
                    this.errors.push(cfg.i18n.max_files_exceeded.replace(':max', cfg.maxFiles));
                    toAdd = toAdd.slice(0, Math.max(0, cfg.maxFiles - currentCount));
                }
            }

            if (!toAdd.length) {
                // Reset file input so the same file can be re-selected
                this.$refs.input.value = '';
                return;
            }

            const entries = toAdd.map(file => ({
                file,
                id: 'file-' + Math.random().toString(36).slice(2) + Date.now(),
                status: 'pending',
                progress: 0,
                preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
            }));

            if (cfg.multiple) {
                this.files = this.files.concat(entries);
            } else {
                // Single mode: revoke previous previews, replace
                this.files.forEach(f => { if (f.preview) URL.revokeObjectURL(f.preview); });
                this.files = entries.slice(0, 1);
            }

            this._syncInput();
        },

        removeFile(id) {
            const idx = this.files.findIndex(f => f.id === id);
            if (idx === -1) return;
            const file = this.files[idx];
            if (file.preview) URL.revokeObjectURL(file.preview);
            this.files.splice(idx, 1);
            this._syncInput();
        },

        removeExisting(id) {
            this.existingFiles = this.existingFiles.filter(f => f.id !== id);
            this.$dispatch('existing-file-removed', { id });
        },

        clearFiles() {
            this.files.forEach(f => { if (f.preview) URL.revokeObjectURL(f.preview); });
            this.files = [];
            this._syncInput();
        },

        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            if (bytes < 1073741824) return (bytes / 1048576).toFixed(1) + ' MB';
            return (bytes / 1073741824).toFixed(1) + ' GB';
        },

        getFileIcon(mimeType) {
            if (!mimeType) return 'document';
            if (mimeType.startsWith('image/')) return 'photo';
            if (mimeType.startsWith('video/')) return 'film';
            if (mimeType.startsWith('audio/')) return 'musical-note';
            if (mimeType === 'application/pdf') return 'document-text';
            return 'document';
        },

        _syncInput() {
            try {
                const dt = new DataTransfer();
                this.files.forEach(f => dt.items.add(f.file));
                this.$refs.input.files = dt.files;
            } catch (e) {
                // DataTransfer not supported in all environments
            }
        },

        _matchesAccept(file, accept) {
            const types = accept.split(',').map(t => t.trim().toLowerCase());
            const fileName = file.name.toLowerCase();
            const mimeType = file.type.toLowerCase();

            return types.some(type => {
                if (type.startsWith('.')) {
                    return fileName.endsWith(type);
                }
                if (type.endsWith('/*')) {
                    return mimeType.startsWith(type.replace('/*', '/'));
                }
                return mimeType === type;
            });
        },
    };
}

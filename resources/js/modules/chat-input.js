export function beartropyChatInput(cfg) {
    return {
        val: cfg.value ?? '',
        isSingleLine: cfg.isSingleLine ?? true,
        stacked: cfg.stacked ?? false,
        action: cfg.action ?? null,
        submitOnEnter: cfg.submitOnEnter ?? true,
        disabled: cfg.disabled ?? false,
        baseHeight: 0,
        checkLineTimeout: null,

        init() {
            this.$nextTick(() => {
                if (!this.stacked) {
                    this.baseHeight = this.$refs.textarea.clientHeight;
                }
                this.resize();
            });
            this.$watch('val', () => {
                this.$nextTick(() => this.resize());
            });
        },

        resize() {
            const textarea = this.$refs.textarea;
            const currentWidth = textarea.offsetWidth;
            textarea.style.width = currentWidth + 'px';
            textarea.style.height = 'auto';
            const scrollH = textarea.scrollHeight;
            const newHeight = Math.max(scrollH, this.baseHeight || 0);
            textarea.style.height = newHeight + 'px';
            textarea.style.width = '';
            textarea.style.overflowY = newHeight >= 240 ? 'auto' : 'hidden';
            if (!this.stacked) {
                this.debouncedCheckLine(scrollH);
            }
        },

        debouncedCheckLine(scrollH) {
            clearTimeout(this.checkLineTimeout);
            this.checkLineTimeout = setTimeout(() => {
                if (!this.val || this.val.length === 0) {
                    this.isSingleLine = true;
                    return;
                }
                if (this.baseHeight > 0) {
                    if (this.isSingleLine) {
                        if (scrollH > this.baseHeight + 5) {
                            this.isSingleLine = false;
                        }
                    } else {
                        if (scrollH <= this.baseHeight - 5) {
                            this.isSingleLine = true;
                        }
                    }
                }
            }, 150);
        },

        handleEnter(e) {
            if (this.submitOnEnter && this.action && !e.shiftKey) {
                e.preventDefault();
                this.$wire.call(this.action);
            }
        },
    };
}

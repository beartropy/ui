// Time Picker Module
export function timepicker(entangledValue, format = 'H:i') {
    return {
        value: entangledValue,
        open: false,
        hour: '00',
        minute: '00',
        format: format,
        displayLabel: '',

        init() {
            this.setFromValue();
            this.updateDisplay();
            this.$watch('value', () => {
                this.setFromValue();
                this.updateDisplay();
            });
        },

        setFromValue() {
            if (!this.value) {
                this.hour = '00';
                this.minute = '00';
                return;
            }
            let [h, m] = this.value.split(':');
            this.hour = h?.padStart(2, '0') || '00';
            this.minute = m?.padStart(2, '0') || '00';
        },

        updateTime() {
            this.value = `${this.hour}:${this.minute}`;
            this.updateDisplay();
        },

        updateDisplay() {
            if (!this.value) {
                this.displayLabel = '';
                return;
            }
            this.displayLabel = `${this.hour}:${this.minute}`;
        },

        clear() {
            this.value = null;
            this.hour = '00';
            this.minute = '00';
            this.displayLabel = '';
            this.open = false;
        },

        scrollToSelected() {
            if (!this.open) return;
            this.$nextTick(() => {
                const scroll = (refName, value) => {
                    const container = this.$refs[refName];
                    if (!container) return;
                    const selected = container.querySelector(`[data-value="${value}"]`);
                    if (selected) {
                        container.scrollTop = selected.offsetTop - container.offsetTop - (container.clientHeight / 2) + (selected.clientHeight / 2);
                    }
                };
                scroll('hoursColumn', this.hour);
                scroll('minutesColumn', this.minute);
            });
        }
    };
}

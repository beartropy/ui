// Time Picker Module
export function beartropyTimepicker(cfg) {
    return {
        value: cfg.value ?? null,
        open: false,
        hour: null,
        minute: null,
        second: null,
        period: 'AM',
        displayLabel: '',

        is12h: cfg.is12h ?? false,
        showSeconds: cfg.showSeconds ?? false,
        min: cfg.min ?? null,
        max: cfg.max ?? null,
        interval: cfg.interval ?? 1,
        disabled: cfg.disabled ?? false,
        i18n: cfg.i18n ?? {},

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
                this.hour = null;
                this.minute = null;
                this.second = null;
                this.period = 'AM';
                return;
            }
            const parts = this.value.split(':');
            let h = parseInt(parts[0] || '0', 10);
            const m = parseInt(parts[1] || '0', 10);
            const s = parseInt(parts[2] || '0', 10);

            if (this.is12h) {
                this.period = h >= 12 ? 'PM' : 'AM';
                h = h % 12;
                if (h === 0) h = 12;
            }

            this.hour = String(h).padStart(2, '0');
            this.minute = String(m).padStart(2, '0');
            this.second = String(s).padStart(2, '0');
        },

        updateTime() {
            if (this.hour === null || this.minute === null) return;

            let h = parseInt(this.hour, 10);
            const m = parseInt(this.minute, 10);
            const s = parseInt(this.second || '0', 10);

            if (this.is12h) {
                h = this._to24h(h, this.period);
            }

            const hh = String(h).padStart(2, '0');
            const mm = String(m).padStart(2, '0');

            if (this.showSeconds) {
                const ss = String(s).padStart(2, '0');
                this.value = `${hh}:${mm}:${ss}`;
            } else {
                this.value = `${hh}:${mm}`;
            }

            this.updateDisplay();
        },

        updateDisplay() {
            if (!this.value) {
                this.displayLabel = '';
                return;
            }
            if (this.hour === null) {
                this.displayLabel = '';
                return;
            }
            const m = this.minute || '00';
            if (this.is12h) {
                const label = `${this.hour}:${m}`;
                this.displayLabel = this.showSeconds
                    ? `${label}:${this.second || '00'} ${this.period}`
                    : `${label} ${this.period}`;
            } else {
                const parts = this.value.split(':');
                const hh = parts[0] || '00';
                const mm = parts[1] || '00';
                this.displayLabel = this.showSeconds
                    ? `${hh}:${mm}:${parts[2] || '00'}`
                    : `${hh}:${mm}`;
            }
        },

        selectHour(h) {
            if (this.disabled) return;
            this.hour = h;
            if (this.minute === null) this.minute = '00';
            if (this.showSeconds && this.second === null) this.second = '00';
            this.updateTime();
        },

        selectMinute(m, autoClose = true) {
            if (this.disabled) return;
            this.minute = m;
            if (this.hour === null) this.hour = '00';
            if (this.showSeconds && this.second === null) this.second = '00';
            this.updateTime();
            if (autoClose && !this.showSeconds && this.hour !== null) {
                this.open = false;
            }
        },

        selectSecond(s, autoClose = true) {
            if (this.disabled) return;
            this.second = s;
            if (this.hour === null) this.hour = '00';
            if (this.minute === null) this.minute = '00';
            this.updateTime();
            if (autoClose) {
                this.open = false;
            }
        },

        togglePeriod(p) {
            if (this.disabled) return;
            this.period = p;
            if (this.hour !== null && this.minute !== null) {
                this.updateTime();
            }
        },

        clear() {
            this.value = null;
            this.hour = null;
            this.minute = null;
            this.second = null;
            this.period = 'AM';
            this.displayLabel = '';
            this.open = false;
        },

        setNow() {
            if (this.disabled) return;
            const now = new Date();
            let h = now.getHours();
            let m = now.getMinutes();
            const s = now.getSeconds();

            if (this.interval > 1) {
                m = Math.round(m / this.interval) * this.interval;
                if (m >= 60) {
                    m = 0;
                    h = (h + 1) % 24;
                }
            }

            if (this.is12h) {
                this.period = h >= 12 ? 'PM' : 'AM';
                h = h % 12;
                if (h === 0) h = 12;
            }

            this.hour = String(h).padStart(2, '0');
            this.minute = String(m).padStart(2, '0');
            this.second = String(s).padStart(2, '0');
            this.updateTime();
            this.open = false;
        },

        // --- Adjacent value getters (for wheel display) ---

        getAdjacentHour(offset) {
            const hours = this.getHours();
            if (this.hour === null) return '';
            const idx = hours.indexOf(this.hour);
            if (idx === -1) return '';
            let next = idx + offset;
            if (next < 0) next = hours.length - 1;
            if (next >= hours.length) next = 0;
            return hours[next];
        },

        getAdjacentMinute(offset) {
            const minutes = this.getMinutes();
            if (this.minute === null) return '';
            const idx = minutes.indexOf(this.minute);
            if (idx === -1) return '';
            let next = idx + offset;
            if (next < 0) next = minutes.length - 1;
            if (next >= minutes.length) next = 0;
            return minutes[next];
        },

        getAdjacentSecond(offset) {
            const seconds = this.getSeconds();
            if (this.second === null) return '';
            const idx = seconds.indexOf(this.second);
            if (idx === -1) return '';
            let next = idx + offset;
            if (next < 0) next = seconds.length - 1;
            if (next >= seconds.length) next = 0;
            return seconds[next];
        },

        // --- Wheel event handlers ---

        wheelHour(event) {
            if (this.disabled) return;
            this.moveHour(event.deltaY > 0 ? 1 : -1);
        },

        wheelMinute(event) {
            if (this.disabled) return;
            this.moveMinute(event.deltaY > 0 ? 1 : -1, false);
        },

        wheelSecond(event) {
            if (this.disabled) return;
            this.moveSecond(event.deltaY > 0 ? 1 : -1, false);
        },

        // --- Disabled checks ---

        isHourDisabled(h) {
            if (!this.min && !this.max) return false;
            const hInt = parseInt(h, 10);

            let h24 = hInt;
            if (this.is12h) {
                h24 = this._to24h(hInt, this.period);
            }

            if (this.min) {
                const minH = parseInt(this.min.split(':')[0], 10);
                if (h24 < minH) return true;
            }
            if (this.max) {
                const maxH = parseInt(this.max.split(':')[0], 10);
                if (h24 > maxH) return true;
            }
            return false;
        },

        isMinuteDisabled(m) {
            if (!this.min && !this.max) return false;
            if (this.hour === null) return false;

            let h24 = parseInt(this.hour, 10);
            if (this.is12h) {
                h24 = this._to24h(h24, this.period);
            }
            const mInt = parseInt(m, 10);

            if (this.min) {
                const [minH, minM] = this.min.split(':').map(Number);
                if (h24 === minH && mInt < minM) return true;
            }
            if (this.max) {
                const [maxH, maxM] = this.max.split(':').map(Number);
                if (h24 === maxH && mInt > maxM) return true;
            }
            return false;
        },

        // --- Value generators ---

        getHours() {
            const hours = [];
            if (this.is12h) {
                for (let i = 1; i <= 12; i++) {
                    hours.push(String(i).padStart(2, '0'));
                }
            } else {
                for (let i = 0; i < 24; i++) {
                    hours.push(String(i).padStart(2, '0'));
                }
            }
            return hours;
        },

        getMinutes() {
            const minutes = [];
            for (let i = 0; i < 60; i += this.interval) {
                minutes.push(String(i).padStart(2, '0'));
            }
            return minutes;
        },

        getSeconds() {
            const seconds = [];
            for (let i = 0; i < 60; i++) {
                seconds.push(String(i).padStart(2, '0'));
            }
            return seconds;
        },

        // --- Navigation ---

        moveHour(direction) {
            const hours = this.getHours();
            if (this.hour === null) {
                this.selectHour(hours[0]);
                return;
            }
            const idx = hours.indexOf(this.hour);
            let next = idx + direction;
            if (next < 0) next = hours.length - 1;
            if (next >= hours.length) next = 0;
            let attempts = hours.length;
            while (this.isHourDisabled(hours[next]) && attempts > 0) {
                next += direction;
                if (next < 0) next = hours.length - 1;
                if (next >= hours.length) next = 0;
                attempts--;
            }
            if (attempts > 0) {
                this.selectHour(hours[next]);
            }
        },

        moveMinute(direction, autoClose = true) {
            const minutes = this.getMinutes();
            if (this.minute === null) {
                this.selectMinute(minutes[0], autoClose);
                return;
            }
            const idx = minutes.indexOf(this.minute);
            let next = idx + direction;
            if (next < 0) next = minutes.length - 1;
            if (next >= minutes.length) next = 0;
            let attempts = minutes.length;
            while (this.isMinuteDisabled(minutes[next]) && attempts > 0) {
                next += direction;
                if (next < 0) next = minutes.length - 1;
                if (next >= minutes.length) next = 0;
                attempts--;
            }
            if (attempts > 0) {
                this.selectMinute(minutes[next], autoClose);
            }
        },

        moveSecond(direction, autoClose = true) {
            const seconds = this.getSeconds();
            if (this.second === null) {
                this.selectSecond(seconds[0], autoClose);
                return;
            }
            const idx = seconds.indexOf(this.second);
            let next = idx + direction;
            if (next < 0) next = seconds.length - 1;
            if (next >= seconds.length) next = 0;
            this.selectSecond(seconds[next], autoClose);
        },

        // --- Helpers ---

        _toMinutes(timeStr) {
            if (!timeStr) return 0;
            const [h, m] = timeStr.split(':').map(Number);
            return h * 60 + (m || 0);
        },

        _to24h(h, period) {
            if (period === 'AM') {
                return h === 12 ? 0 : h;
            }
            return h === 12 ? 12 : h + 12;
        },
    };
}

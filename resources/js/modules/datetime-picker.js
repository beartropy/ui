// DateTime Picker Module
export function beartropyDatetimepicker(cfg) {
    return {
        value: cfg.value ?? '',
        open: false,
        range: !!cfg.range,
        min: cfg.min || '',
        max: cfg.max || '',
        showTime: !!cfg.showTime,
        disabled: !!cfg.disabled,
        startHour: '00',
        startMinute: '00',
        endHour: '00',
        endMinute: '00',
        formatDisplay: cfg.formatDisplay || '{d}/{m}/{Y}',
        panel: 'date-start',
        startTimeSet: false,
        endTimeSet: false,
        hovered: null,
        month: (new Date()).getMonth(),
        year: (new Date()).getFullYear(),
        days: [],
        start: null,
        end: null,
        displayLabel: '',
        i18n: cfg.i18n ?? {},

        init() {
            this.setFromValue();
            let refDate = this.start ? new Date(this.start) : new Date();
            if (this.start && /^\d{4}-\d{2}-\d{2}$/.test(this.start)) {
                let [y, m, d] = this.start.split('-');
                refDate = new Date(Number(y), Number(m) - 1, Number(d));
            }
            this.month = refDate.getMonth();
            this.year = refDate.getFullYear();
            this.updateCalendar();
            this.updateDisplay();
            this.setInitialPanel();
            this.$watch('open', (isOpen) => {
                if (isOpen) {
                    this.setInitialPanel();
                }
            });
            this.$watch('value', () => {
                this.setFromValue();
                this.updateDisplay();
                let refDateWatch = this.start ? new Date(this.start) : new Date();
                if (this.start && /^\d{4}-\d{2}-\d{2}$/.test(this.start)) {
                    let [y, m, d] = this.start.split('-');
                    refDateWatch = new Date(Number(y), Number(m) - 1, Number(d));
                }
                this.month = refDateWatch.getMonth();
                this.year = refDateWatch.getFullYear();
                this.updateCalendar();
                this.setInitialPanel();
            });
        },

        setFromValue() {
            if (!this.range) {
                let [date, time] = (this.value || '').split(' ');
                this.start = this.normalizeDate(date);
                this.startTimeSet = this.showTime && !!time;
                if (this.showTime && time) {
                    let [h, m] = time.split(':');
                    this.startHour = h?.padStart(2, '0') || '00';
                    this.startMinute = m?.padStart(2, '0') || '00';
                } else if (this.showTime) {
                    this.startHour = '00';
                    this.startMinute = '00';
                }
                this.end = null;
                this.endTimeSet = false;
            } else if (this.value && typeof this.value === 'object' && this.value.start && this.value.end) {
                let [date1, time1] = (this.value.start || '').split(' ');
                let [date2, time2] = (this.value.end || '').split(' ');
                this.start = this.normalizeDate(date1);
                this.end = this.normalizeDate(date2);
                this.startTimeSet = this.showTime && !!time1;
                this.endTimeSet = this.showTime && !!time2;
                if (this.showTime) {
                    let [h1, m1] = time1 ? time1.split(':') : [];
                    let [h2, m2] = time2 ? time2.split(':') : [];
                    this.startHour = h1?.padStart(2, '0') || '00';
                    this.startMinute = m1?.padStart(2, '0') || '00';
                    this.endHour = h2?.padStart(2, '0') || '00';
                    this.endMinute = m2?.padStart(2, '0') || '00';
                } else {
                    this.startHour = '00';
                    this.startMinute = '00';
                    this.endHour = '00';
                    this.endMinute = '00';
                }
            } else {
                this.startTimeSet = false;
                this.endTimeSet = false;
            }
        },

        updateDisplay() {
            if (!this.range) {
                this.displayLabel = this.formatForDisplay(
                    this.start,
                    this.formatDisplay,
                    this.showTime ? this.startHour : '',
                    this.showTime ? this.startMinute : ''
                );
            } else if (this.start && this.end) {
                this.displayLabel =
                    this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : '', this.showTime ? this.startMinute : '') +
                    ' - ' +
                    this.formatForDisplay(this.end, this.formatDisplay, this.showTime ? this.endHour : '', this.showTime ? this.endMinute : '');
            } else if (this.start) {
                this.displayLabel =
                    this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : '', this.showTime ? this.startMinute : '') +
                    ' - ...';
            } else {
                this.displayLabel = '';
            }
        },

        updateCalendar() {
            let first = new Date(this.year, this.month, 1);
            let last = new Date(this.year, this.month + 1, 0);
            let startDay = (first.getDay() + 6) % 7;
            let days = [];
            for (let i = 0; i < startDay; i++) {
                days.push({ label: '', date: '', inMonth: false });
            }
            for (let d = 1; d <= last.getDate(); d++) {
                let date = `${this.year}-${(this.month + 1).toString().padStart(2, '0')}-${d.toString().padStart(2, '0')}`;
                days.push({ label: d, date, inMonth: true });
            }
            while (days.length % 7) {
                days.push({ label: '', date: '', inMonth: false });
            }
            this.days = days;
        },

        isDisabled(day) {
            if (!day.date) return true;
            if (this.min && day.date < this.min) return true;
            if (this.max && day.date > this.max) return true;
            return !day.inMonth;
        },

        isToday(day) {
            if (!day.date) return false;
            const now = new Date();
            const today = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, '0')}-${now.getDate().toString().padStart(2, '0')}`;
            return day.date === today;
        },

        selectDay(day) {
            if (this.isDisabled(day)) return;
            this.hovered = null;
            if (this.range) {
                const selectingEnd = this.panel === 'date-end';
                if (selectingEnd) {
                    if (day.date < this.start) {
                        this.start = day.date;
                        this.startHour = '00';
                        this.startMinute = '00';
                        this.startTimeSet = false;
                        this.end = '';
                        this.endHour = '00';
                        this.endMinute = '00';
                        this.endTimeSet = false;
                        this.panel = this.showTime ? 'time-start' : 'date-end';
                    } else {
                        this.end = day.date;
                        this.endHour = '00';
                        this.endMinute = '00';
                        this.endTimeSet = false;
                        this.panel = this.showTime ? 'time-end' : this.panel;
                        if (!this.showTime) {
                            this.value = { start: this.start, end: this.end };
                            this.open = false;
                        }
                    }
                } else if (!this.start || (this.start && this.end)) {
                    this.start = day.date;
                    this.startHour = '00';
                    this.startMinute = '00';
                    this.end = '';
                    this.endHour = '00';
                    this.endMinute = '00';
                    this.startTimeSet = false;
                    this.endTimeSet = false;
                    this.panel = this.showTime ? 'time-start' : 'date-end';
                } else if (day.date < this.start) {
                    this.start = day.date;
                    this.startHour = '00';
                    this.startMinute = '00';
                    this.end = '';
                    this.endHour = '00';
                    this.endMinute = '00';
                    this.startTimeSet = false;
                    this.endTimeSet = false;
                    this.panel = this.showTime ? 'time-start' : 'date-end';
                } else {
                    this.end = day.date;
                    this.endHour = '00';
                    this.endMinute = '00';
                    this.endTimeSet = false;
                    this.panel = this.showTime ? 'time-end' : this.panel;
                    if (!this.showTime) {
                        this.value = { start: this.start, end: this.end };
                        this.open = false;
                    }
                }
            } else {
                this.start = day.date;
                this.startHour = '00';
                this.startMinute = '00';
                this.startTimeSet = false;
                if (!this.showTime) {
                    this.value = this.start;
                    this.open = false;
                } else {
                    this.panel = 'time-start';
                }
            }
            this.updateDisplay();
        },

        setTime(type, h, m, autoAdvance = false) {
            if (type === 'start') {
                this.startHour = h;
                this.startMinute = m;
                this.startTimeSet = true;
            } else {
                this.endHour = h;
                this.endMinute = m;
                this.endTimeSet = true;
            }

            if (autoAdvance) {
                if (this.range && this.start && this.end) {
                    this.value = this.showTime
                        ? {
                            start: `${this.start} ${this.startHour}:${this.startMinute}`,
                            end: `${this.end} ${this.endHour}:${this.endMinute}`
                        }
                        : { start: this.start, end: this.end };
                    this.open = false;
                    this.panel = 'date-start';
                }
                if (this.range && type === 'start' && this.start) {
                    this.panel = this.end ? 'time-end' : 'date-end';
                }
                if (!this.range && this.start) {
                    this.value = this.showTime
                        ? `${this.start} ${this.startHour}:${this.startMinute}`
                        : this.start;
                    this.open = false;
                    this.panel = 'date-start';
                }
            }
            this.updateDisplay();
        },

        isSelected(day) {
            return day.date && (day.date === this.start || day.date === this.end);
        },

        isInRange(day) {
            if (!this.range || !day.date || !this.start) return false;
            if (this.end) {
                return day.date > this.start && day.date < this.end;
            }
            if (this.hovered && this.start && this.hovered !== this.start) {
                let [minRange, maxRange] = [this.start, this.hovered].sort();
                return day.date > minRange && day.date < maxRange;
            }
            return false;
        },

        prevMonth() {
            if (--this.month < 0) { this.month = 11; this.year--; }
            this.updateCalendar();
        },

        nextMonth() {
            if (++this.month > 11) { this.month = 0; this.year++; }
            this.updateCalendar();
        },

        normalizeDate(str) {
            if (!str) return '';
            if (/^\d{10}$/.test(str)) {
                const d = new Date(Number(str) * 1000);
                return d.toISOString().slice(0, 10);
            }
            if (/^\d{13}$/.test(str)) {
                const d = new Date(Number(str));
                return d.toISOString().slice(0, 10);
            }
            let m = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})/);
            if (m) return `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}`;
            m = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})\s+\d{2}:\d{2}:\d{2}$/);
            if (m) return `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}`;
            m = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$/);
            if (m) return `${m[3]}-${m[2].padStart(2, '0')}-${m[1].padStart(2, '0')}`;
            m = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{2})$/);
            if (m) return `20${m[3]}-${m[2].padStart(2, '0')}-${m[1].padStart(2, '0')}`;
            m = str.match(/^(\d{4})[/-](\d{1,2})[/-](\d{1,2})/);
            if (m) return `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}`;
            return str;
        },

        formatForDisplay(dateStr, format = '{d}/{m}/{Y}', hour = '', minute = '') {
            if (!dateStr) return '';
            let [y, m, d] = dateStr.split('-');
            y = y ?? '';
            m = m ? m.padStart(2, '0') : '';
            d = d ? d.padStart(2, '0') : '';
            let out = format;
            out = out.replace(/{Y}/g, y);
            out = out.replace(/{m}/g, m);
            out = out.replace(/{d}/g, d);
            if (out.includes('{M}')) {
                let monthsShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                out = out.replace(/{M}/g, monthsShort[parseInt(m, 10) - 1] || m);
            }
            if (out.includes('{MMMM}')) {
                let monthsLong = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                out = out.replace(/{MMMM}/g, monthsLong[parseInt(m, 10) - 1] || m);
            }
            hour = (hour || '').padStart(2, '0');
            minute = (minute || '').padStart(2, '0');
            out = out.replace(/{H}/g, hour);
            out = out.replace(/{i}/g, minute);
            return out;
        },

        onDropdownClose() {
            if (this.range) {
                if (this.start && this.end) {
                    this.value = this.showTime
                        ? {
                            start: `${this.start} ${this.startHour || '00'}:${this.startMinute || '00'}`,
                            end: `${this.end} ${this.endHour || '00'}:${this.endMinute || '00'}`
                        }
                        : { start: this.start, end: this.end };
                }
            } else if (this.start) {
                this.value = this.showTime
                    ? `${this.start} ${this.startHour || '00'}:${this.startMinute || '00'}`
                    : this.start;
            }
            this.open = false;
            this.panel = 'date-start';
            this.updateDisplay();
        },

        setInitialPanel() {
            if (!this.showTime) {
                this.panel = 'date-start';
                return;
            }
            if (this.range) {
                if (!this.start) {
                    this.panel = 'date-start';
                } else if (!this.startTimeSet) {
                    this.panel = 'time-start';
                } else if (!this.end) {
                    this.panel = 'date-end';
                } else if (!this.endTimeSet) {
                    this.panel = 'time-end';
                } else {
                    this.panel = 'date-start';
                }
                return;
            }
            if (!this.start) {
                this.panel = 'date-start';
            } else if (!this.startTimeSet) {
                this.panel = 'time-start';
            } else {
                this.panel = 'date-start';
            }
        },

        showCalendarPane() {
            if (!this.showTime) return true;
            return this.panel === 'date-start' || this.panel === 'date-end';
        },

        isPickingStartTime() {
            return this.showTime && this.panel === 'time-start';
        },

        isPickingEndTime() {
            return this.showTime && this.panel === 'time-end';
        },

        currentTimeType() {
            return this.panel === 'time-end' ? 'end' : 'start';
        },

        // --- Wheel getters ---

        getHourForType(type) {
            return type === 'end' ? this.endHour : this.startHour;
        },

        getMinuteForType(type) {
            return type === 'end' ? this.endMinute : this.startMinute;
        },

        getAdjacentHour(type, offset) {
            const h = parseInt(this.getHourForType(type), 10);
            let next = h + offset;
            if (next < 0) next = 23;
            if (next > 23) next = 0;
            return String(next).padStart(2, '0');
        },

        getAdjacentMinute(type, offset) {
            const m = parseInt(this.getMinuteForType(type), 10);
            let next = m + offset;
            if (next < 0) next = 59;
            if (next > 59) next = 0;
            return String(next).padStart(2, '0');
        },

        wheelHour(type, event) {
            if (this.disabled) return;
            this.moveHour(type, event.deltaY > 0 ? 1 : -1);
        },

        wheelMinute(type, event) {
            if (this.disabled) return;
            this.moveMinute(type, event.deltaY > 0 ? 1 : -1);
        },

        moveHour(type, direction) {
            const current = parseInt(this.getHourForType(type), 10);
            let next = current + direction;
            if (next < 0) next = 23;
            if (next > 23) next = 0;
            const h = String(next).padStart(2, '0');
            if (type === 'end') {
                this.endHour = h;
            } else {
                this.startHour = h;
            }
            this.setTime(type, this.getHourForType(type), this.getMinuteForType(type));
        },

        moveMinute(type, direction) {
            const current = parseInt(this.getMinuteForType(type), 10);
            let next = current + direction;
            if (next < 0) next = 59;
            if (next > 59) next = 0;
            const m = String(next).padStart(2, '0');
            if (type === 'end') {
                this.endMinute = m;
            } else {
                this.startMinute = m;
            }
            this.setTime(type, this.getHourForType(type), this.getMinuteForType(type));
        },

        setTimeNow(type) {
            if (this.disabled) return;
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            if (type === 'end') {
                this.endHour = h;
                this.endMinute = m;
            } else {
                this.startHour = h;
                this.startMinute = m;
            }
            this.setTime(type, h, m, true);
        },

        goToToday() {
            const now = new Date();
            this.month = now.getMonth();
            this.year = now.getFullYear();
            this.updateCalendar();
        },

        clearSelection() {
            this.value = '';
            this.start = '';
            this.end = '';
            this.startHour = '00';
            this.startMinute = '00';
            this.endHour = '00';
            this.endMinute = '00';
            this.displayLabel = '';
            this.panel = 'date-start';
            this.startTimeSet = false;
            this.endTimeSet = false;
            this.hovered = null;
        },
    };
}

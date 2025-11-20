// DateTime Picker Module
export const beartropyI18n = {
    es: {
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsLong: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
        weekdays: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
        from: 'Desde',
        to: 'Hasta',
        placeholder: 'Seleccionar fechaâ€¦'
    },
    en: {
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        monthsLong: ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'],
        weekdays: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
        from: 'From',
        to: 'To',
        placeholder: 'Select dateâ€¦'
    },
};
export function datetimepicker(entangledValue, rangeMode = false, min = '', max = '', formatDisplay = '{d}/{m}/{Y}', showTime = false) {
    return {
        value: entangledValue,
        open: false,
        range: !!rangeMode,
        min: min || '',
        max: max || '',
        showTime: !!showTime,
        startHour: '00',
        startMinute: '00',
        endHour: '00',
        endMinute: '00',
        formatDisplay: formatDisplay || '{d}/{m}/{Y}',
        hovered: null,
        month: (new Date()).getMonth(),
        year: (new Date()).getFullYear(),
        days: [],
        start: null,
        end: null,
        displayLabel: '',
        init() {
            this.setFromValue();
            // âš¡ï¸ Esto es lo importante:
            let refDate = this.start ? new Date(this.start) : new Date();
            // Para fechas YYYY-MM-DD, new Date() a veces asume UTC y puede fallar un mes.
            // Â¡RecomendaciÃ³n pro! ParseÃ¡ a mano:
            if (this.start && /^\d{4}-\d{2}-\d{2}$/.test(this.start)) {
                let [y, m, d] = this.start.split('-');
                refDate = new Date(Number(y), Number(m) - 1, Number(d));
            }
            this.month = refDate.getMonth();
            this.year = refDate.getFullYear();
            this.updateCalendar();
            this.updateDisplay();
            this.$watch('value', () => {
                this.setFromValue();
                this.updateDisplay();
                // TambiÃ©n actualizar mes/aÃ±o cuando cambia el valor externo
                let refDate = this.start ? new Date(this.start) : new Date();
                if (this.start && /^\d{4}-\d{2}-\d{2}$/.test(this.start)) {
                    let [y, m, d] = this.start.split('-');
                    refDate = new Date(Number(y), Number(m) - 1, Number(d));
                }
                this.month = refDate.getMonth();
                this.year = refDate.getFullYear();
                this.updateCalendar();
            });
        },
        setFromValue() {
            if (!this.range) {
                // Soporta fecha y hora: YYYY-MM-DD HH:MM
                let [date, time] = (this.value || '').split(' ');
                this.start = this.normalizeDate(date);
                if (this.showTime && time) {
                    let [h, m] = time.split(':');
                    this.startHour = h?.padStart(2, '0') || '00';
                    this.startMinute = m?.padStart(2, '0') || '00';
                }
                this.end = null;
            } else if (this.value && typeof this.value === 'object' && this.value.start && this.value.end) {
                let [date1, time1] = (this.value.start || '').split(' ');
                let [date2, time2] = (this.value.end || '').split(' ');
                this.start = this.normalizeDate(date1);
                this.end = this.normalizeDate(date2);
                if (this.showTime) {
                    let [h1, m1] = time1 ? time1.split(':') : [];
                    let [h2, m2] = time2 ? time2.split(':') : [];
                    this.startHour = h1?.padStart(2, '0') || '00';
                    this.startMinute = m1?.padStart(2, '0') || '00';
                    this.endHour = h2?.padStart(2, '0') || '00';
                    this.endMinute = m2?.padStart(2, '0') || '00';
                }
            }
        },
        updateDisplay() {
            if (!this.range) {
                this.displayLabel = this.formatForDisplay(
                    this.start, this.formatDisplay, this.showTime ? this.startHour : '', this.showTime ? this.startMinute : ''
                );
            } else if (this.start && this.end) {
                this.displayLabel =
                    this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : '', this.showTime ? this.startMinute : '') +
                    ' â€” ' +
                    this.formatForDisplay(this.end, this.formatDisplay, this.showTime ? this.endHour : '', this.showTime ? this.endMinute : '');
            } else if (this.start) {
                this.displayLabel =
                    this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : '', this.showTime ? this.startMinute : '') +
                    ' â€” â€¦';
            } else {
                this.displayLabel = '';
            }
        },


        formatDate(str) {
            if (!str) return '';
            let [y, m, d] = str.split('-');
            return `${d}/${m}/${y}`;
        },
        updateCalendar() {
            let first = new Date(this.year, this.month, 1);
            let last = new Date(this.year, this.month + 1, 0);
            let startDay = (first.getDay() + 6) % 7; // lunes = 0
            let days = [];
            for (let i = 0; i < startDay; i++) days.push({ label: '', date: '', inMonth: false });
            for (let d = 1; d <= last.getDate(); d++) {
                let date = `${this.year}-${(this.month + 1).toString().padStart(2, '0')}-${d.toString().padStart(2, '0')}`;
                days.push({ label: d, date, inMonth: true });
            }
            while (days.length % 7) days.push({ label: '', date: '', inMonth: false });
            this.days = days;
        },
        isDisabled(day) {
            if (!day.date) return true;
            if (this.min && day.date < this.min) return true;
            if (this.max && day.date > this.max) return true;
            return !day.inMonth;
        },
        selectDay(day) {
            if (this.isDisabled(day)) return;
            if (this.range) {
                if (!this.start || (this.start && this.end)) {
                    this.start = day.date;
                    this.startHour = '00'; this.startMinute = '00';
                    this.end = ''; this.endHour = '00'; this.endMinute = '00';
                } else if (day.date < this.start) {
                    this.start = day.date;
                    this.startHour = '00'; this.startMinute = '00';
                    this.end = ''; this.endHour = '00'; this.endMinute = '00';
                } else {
                    this.end = day.date;
                    this.endHour = '00'; this.endMinute = '00';
                    if (!this.showTime) {
                        this.value = { start: this.start, end: this.end };
                        this.open = false;
                    }
                }
            } else {
                this.start = day.date;
                this.startHour = '00'; this.startMinute = '00';
                if (!this.showTime) {
                    this.value = this.start;
                    this.open = false;
                }
            }
            this.updateDisplay();
        },
        setTime(type, h, m) {
            if (type === 'start') {
                this.startHour = h;
                this.startMinute = m;
            } else {
                this.endHour = h;
                this.endMinute = m;
            }
            if (this.range && this.start && this.end) {
                this.value = this.showTime
                    ? {
                        start: `${this.start} ${this.startHour}:${this.startMinute}`,
                        end: `${this.end} ${this.endHour}:${this.endMinute}`
                    }
                    : { start: this.start, end: this.end };
                this.open = false;
            }
            if (!this.range && this.start) {
                this.value = this.showTime
                    ? `${this.start} ${this.startHour}:${this.startMinute}`
                    : this.start;
                this.open = false;
            }
            this.updateDisplay();
        },
        isSelected(day) {
            return day.date && (day.date === this.start || day.date === this.end);
        },
        isInRange(day) {
            if (!this.range || !day.date || !this.start) return false;
            // Rango definitivo
            if (this.end) {
                return day.date > this.start && day.date < this.end;
            }
            // Hover temporal (al elegir rango)
            if (this.hovered && this.start && this.hovered !== this.start) {
                let [min, max] = [this.start, this.hovered].sort();
                return day.date > min && day.date < max;
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
            // Timestamps (10 dÃ­gitos, segundos)
            if (/^\d{10}$/.test(str)) {
                const d = new Date(Number(str) * 1000);
                return d.toISOString().slice(0, 10);
            }
            // Timestamps milisegundos (13 dÃ­gitos)
            if (/^\d{13}$/.test(str)) {
                const d = new Date(Number(str));
                return d.toISOString().slice(0, 10);
            }
            // ISO completo: 2025-08-04T23:10:00.000Z
            let m = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})/);
            if (m) return `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}`;
            // Eloquent datetime: 2025-08-04 23:10:00
            m = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})\s+\d{2}:\d{2}:\d{2}$/);
            if (m) return `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}`;
            // DD-MM-YYYY o DD/MM/YYYY
            m = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$/);
            if (m) return `${m[3]}-${m[2].padStart(2, '0')}-${m[1].padStart(2, '0')}`;
            // DD-MM-YY o DD/MM/YY
            m = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{2})$/);
            if (m) return `20${m[3]}-${m[2].padStart(2, '0')}-${m[1].padStart(2, '0')}`;
            // Solo fecha, cualquier formato con separador, ignora hora
            m = str.match(/^(\d{4})[/-](\d{1,2})[/-](\d{1,2})/);
            if (m) return `${m[1]}-${m[2].padStart(2, '0')}-${m[3].padStart(2, '0')}`;
            // Si nada matchea, devolver original
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
            // Nombre mes corto/largo
            if (out.includes('{M}')) {
                let meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                out = out.replace(/{M}/g, meses[parseInt(m, 10) - 1] || m);
            }
            if (out.includes('{MMMM}')) {
                let mesesL = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                out = out.replace(/{MMMM}/g, mesesL[parseInt(m, 10) - 1] || m);
            }
            // âš¡ï¸ Soporte HORA Y MINUTO
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
            this.updateDisplay();
        }


    };
};

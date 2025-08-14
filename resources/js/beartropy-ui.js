window.$beartropy = window.$beartropy || {};

/** Modals **/
window.$beartropy.openModal = function(id) {
    id = id.toLowerCase();
    window.dispatchEvent(new CustomEvent(`open-modal-${id}`));
};

window.$beartropy.closeModal = function(id) {
    id = id.toLowerCase();
    window.dispatchEvent(new CustomEvent(`close-modal-${id}`));
};

/** Toasts **/
window.$beartropy.toast = function(type, title, message = '', duration = 4000, position = 'top-right') {
    const toast = {
        id: (window.crypto && window.crypto.randomUUID)
            ? window.crypto.randomUUID()
            : 'toast-' + Math.random().toString(36).slice(2) + Date.now(),
        type,
        title,
        message,
        duration,
        position,
    };

    // 🔥 1. Intentar usar el store de Alpine (inmediato)
    if (window.Alpine && Alpine.store('toasts')) {
        Alpine.store('toasts').add(toast);
    }
    // ⚡ 2. Si no existe, usar Livewire (menos probable, pero más universal)
    else if (window.Livewire && window.Livewire.dispatch) {
        window.Livewire.dispatch('beartropy-add-toast', toast);
    }
    // 3. Fallback: warning
    else {
        console.warn('[Beartropy] Toast: no Alpine store ni Livewire.dispatch disponible');
    }
};

['success', 'error', 'warning', 'info'].forEach(type => {
    $beartropy.toast[type] = (title, message, duration, position) =>
        $beartropy.toast(type, title, message, duration, position);
});


/** Table **/
window.$beartropy.beartropyTable = function({ data, columns, perPage, sortable, searchable, paginated  }) {
    return {
        original: data,
        columns: Array.isArray(columns) ? columns : Object.keys(columns),
        colLabels: columns,
        perPage,
        sortable,
        searchable,
        paginated: paginated === undefined ? true : paginated,

        search: '',
        sortBy: '',
        sortDesc: false,
        page: 1,

        get filtered() {
            let rows = this.original;
            if (this.search && this.searchable) {
                const s = this.search.toLowerCase();
                rows = rows.filter(r =>
                    this.columns.some(c =>
                        (r[c] ?? '').toString().toLowerCase().includes(s)
                    )
                );
            }
            return rows;
        },
        get sorted() {
            if (!this.sortable || !this.sortBy) return this.filtered;
            return [...this.filtered].sort((a, b) => {
                let vA = a[this.sortBy] ?? '';
                let vB = b[this.sortBy] ?? '';
                if (!isNaN(vA) && !isNaN(vB)) {
                    return this.sortDesc ? vB - vA : vA - vB;
                }
                return this.sortDesc
                    ? vB.toString().localeCompare(vA.toString())
                    : vA.toString().localeCompare(vB.toString());
            });
        },
        get paginatedRows() {
            if (!this.paginated) {
                return this.sorted;
            }
            return this.sorted.slice(this.start, this.start + this.perPage);
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.sorted.length / this.perPage));
        },
        get start() {
            return (this.page - 1) * this.perPage;
        },
        colLabel(col) {
            return this.colLabels[col] ?? col;
        },
        toggleSort(col) {
            if (!this.sortable) return;
            if (this.sortBy === col) {
                this.sortDesc = !this.sortDesc;
            } else {
                this.sortBy = col;
                this.sortDesc = false;
            }
        },
        gotoPage(p) {
            if (p >= 1 && p <= this.totalPages) this.page = p;
        },
        nextPage() {
            if (this.page < this.totalPages) this.page++;
        },
        prevPage() {
            if (this.page > 1) this.page--;
        },
        // Método Livewire-style
        pagesToShow() {
            const total = this.totalPages;
            const current = this.page;
            const delta = 1;

            // Si pocas páginas, muestro todas
            if (total <= 7) {
                return Array.from({length: total}, (_, i) => i + 1);
            }

            let pages = [];

            // Siempre la primera
            pages.push(1);

            // Dots a la izquierda
            if (current - delta > 2) {
                pages.push('...');
            }

            // Rango central
            let start = Math.max(2, current - delta);
            let end = Math.min(total - 1, current + delta);

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            // Dots a la derecha
            if (current + delta < total - 1) {
                pages.push('...');
            }

            // Siempre la última
            pages.push(total);

            return pages;
        },
        init() {
            this.$watch('search', () => this.page = 1);
            this.$watch('sorted', () => this.page = 1);
        }
    }
}


/** DateTime Picker **/
window.beartropyI18n = {
    es: {
        months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthsLong: ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
        weekdays: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
        from: 'Desde',
        to: 'Hasta',
        placeholder: 'Seleccionar fecha…'
    },
    en: {
        months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
        monthsLong: ['january','february','march','april','may','june','july','august','september','october','november','december'],
        weekdays: ['Mo','Tu','We','Th','Fr','Sa','Su'],
        from: 'From',
        to: 'To',
        placeholder: 'Select date…'
    },
};
window.$beartropy.datetimepicker = function(entangledValue, rangeMode = false, min = '', max = '', formatDisplay = '{d}/{m}/{Y}', showTime = false) {
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
            // ⚡️ Esto es lo importante:
            let refDate = this.start ? new Date(this.start) : new Date();
            // Para fechas YYYY-MM-DD, new Date() a veces asume UTC y puede fallar un mes.
            // ¡Recomendación pro! Parseá a mano:
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
                // También actualizar mes/año cuando cambia el valor externo
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
                    ' — ' +
                    this.formatForDisplay(this.end, this.formatDisplay, this.showTime ? this.endHour : '', this.showTime ? this.endMinute : '');
            } else if (this.start) {
                this.displayLabel =
                    this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : '', this.showTime ? this.startMinute : '') +
                    ' — …';
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
                        end:   `${this.end} ${this.endHour}:${this.endMinute}`
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
            // Timestamps (10 dígitos, segundos)
            if (/^\d{10}$/.test(str)) {
                const d = new Date(Number(str) * 1000);
                return d.toISOString().slice(0,10);
            }
            // Timestamps milisegundos (13 dígitos)
            if (/^\d{13}$/.test(str)) {
                const d = new Date(Number(str));
                return d.toISOString().slice(0,10);
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
                let meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                out = out.replace(/{M}/g, meses[parseInt(m,10)-1] || m);
            }
            if (out.includes('{MMMM}')) {
                let mesesL = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                out = out.replace(/{MMMM}/g, mesesL[parseInt(m,10)-1] || m);
            }
            // ⚡️ Soporte HORA Y MINUTO
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

/** Tag Input **/
window.$beartropy.tagInput = function ({ initialTags = [], unique = true, maxTags = null, disabled = false, separator = ',' }) {
    // Soporta string (",; ") o array ([';', ',', ' '])
    let seps = Array.isArray(separator) ? separator : separator.split('');
    // Armar regex tipo /[;, ]+/g
    let sepRegex = new RegExp(`[${seps.map(s => s.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&')).join('')}]`, 'g');
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
            // Split si hay separador dentro
            let parts = val.split(sepRegex).map(t => t.trim()).filter(Boolean);
            parts.forEach(tag => this._tryAddTag(tag));
            this.input = '';
        },
        removeTag(i) { if (!this.disabled) this.tags.splice(i, 1); },
        removeOnBackspace(e) {
            if (!this.input && this.tags.length && !this.disabled) this.tags.pop();
        },
        addTagOnTab(e) {
            if (this.input) { this.addTag(); e.preventDefault(); }
        },
        handlePaste(e) {
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            if (paste && sepRegex.test(paste)) {
                let newTags = paste.split(sepRegex).map(t => t.trim()).filter(Boolean);
                newTags.forEach(tag => this._tryAddTag(tag));
                e.preventDefault();
                this.input = '';
            }
        },
        addTagFromPaste(tag) { this._tryAddTag(tag); },
        _tryAddTag(tag) {
            if (!tag) return;
            if (this.unique && this.tags.includes(tag)) return;
            if (this.maxTags && this.tags.length >= this.maxTags) return;
            this.tags.push(tag);
        },
    };
}

/** Confirm **/

window.$beartropy.confirmHost = function ({
    id,
    defaultPlacement = 'top',
    defaultPanelClass = 'mt-32',
}) {
  function toArray(v){ return Array.isArray(v) ? v : (v != null ? [v] : []); }
  function normalizeButtons(arr){
    return (Array.isArray(arr) ? arr : []).map(b => ({
      label: b?.label ?? 'OK',
      variant: b?.variant ?? 'soft',
      color: b?.color ?? 'gray',
      mode: b?.mode ?? (b?.wire ? 'wire' : (b?.emit ? 'emit' : 'close')),
      wire: b?.wire ?? null,
      params: toArray(b?.params),
      emit: b?.emit ?? null,
      payload: b?.payload ?? {},
      dismissAfter: b?.dismissAfter === true, // 👈 estricto
      close: b?.close === true,
      spinner: b?.spinner === true,
      role: b?.role ?? null,
    }));
  }

  // ====== efectos ======
  function dialogClosed(effect){
    switch (effect) {
      case 'slide-up':    return 'opacity-0 translate-y-2';
      case 'slide-down':  return 'opacity-0 -translate-y-2';
      case 'slide-left':  return 'opacity-0 translate-x-2';
      case 'slide-right': return 'opacity-0 -translate-x-2';
      case 'fade':        return 'opacity-0';
      case 'zoom':
      default:            return 'opacity-0 scale-95';
    }
  }
  function dialogOpen(effect){
    switch (effect) {
      case 'slide-up':
      case 'slide-down':
      case 'slide-left':
      case 'slide-right': return 'opacity-100 translate-x-0 translate-y-0';
      case 'fade':        return 'opacity-100';
      case 'zoom':
      default:            return 'opacity-100 scale-100';
    }
  }

  // Mapea (variant,color) → clases para TU <x-button raw>
  function classesFor(variant, color){
    const v = variant || 'soft';
    const c = color || 'gray';
    const map = {
      primary: {
        red:  'bg-red-600 text-white hover:bg-red-700 focus:ring-red-400 dark:focus:ring-red-600',
        blue: 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-400 dark:focus:ring-blue-600',
        gray: 'bg-gray-800 text-white hover:bg-gray-900 focus:ring-gray-500 dark:focus:ring-gray-500',
      },
      ghost: {
        gray: 'bg-transparent text-gray-700 hover:bg-gray-100/40 focus:ring-gray-300 dark:text-gray-200',
        red:  'bg-transparent text-red-600 hover:bg-red-50 focus:ring-red-300 dark:text-red-300',
      },
      outline: {
        gray: 'bg-transparent border border-gray-400/60 text-gray-800 dark:text-gray-200 hover:bg-gray-100/40 focus:ring-gray-300',
      },
      soft: { gray: 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600' },
      danger: { red: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-400 dark:focus:ring-red-600' },
    };
    return (map[v] && map[v][c]) ? map[v][c] : map.soft.gray;
  }

  return {
    // ===== state =====
    id,
    cfg: {},
    buttons: [],
    btnLoading: [],
    open: false,              // visibilidad del wrapper
    anim: 'idle',             // 'idle' | 'enter' | 'open' | 'leave'

    // flags/efectos (configurables por payload)
    closeOnBackdrop: true,
    closeOnEscape:   true,
    effect:   'zoom',
    duration: 200,           // ms
    easing:   'ease-out',
    overlayOpacity: 0.6,     // 0..1
    overlayBlur: false,

    placement: defaultPlacement,   // 'top' | 'center'
    panelClass: defaultPanelClass, // ej. 'mt-32'

    // ===== utils =====
    _norm(raw){ if(!raw) return {}; if(Array.isArray(raw)) return raw[0] ?? {}; return raw; },
    sizeClasses(){
      switch (this.cfg.size) {
        case 'sm': return 'max-w-sm';
        case 'lg': return 'max-w-2xl';
        case 'xl': return 'max-w-4xl';
        case '2xl': return 'max-w-6xl';
        default: return 'max-w-lg';
      }
    },
    containerClass(){
        return this.placement === 'top'
        ? 'flex justify-center items-start'
        : 'grid place-items-center';
    },
    overlayStyle(){
    const d = Number(this.duration) || 200;
    const e = this.easing || 'ease-out';
    // Cuando está abierto, usa overlayOpacity; cuando entra/sale, 0
    const o = (this.anim === 'open') ? this.overlayOpacity : 0;
    return `opacity:${o}; transition: opacity ${d}ms ${e};`;
    },
    dialogStyle(){
      const d = Number(this.duration) || 200;
      const e = this.easing || 'ease-out';
      return `transition: transform ${d}ms ${e}, opacity ${d}ms ${e};`;
    },
    dialogMotionClass(){
      // Durante 'enter' y 'leave' usamos la pose "cerrada"
      return (this.anim === 'open') ? dialogOpen(this.effect) : dialogClosed(this.effect);
    },
    btnClass(btn){ return classesFor(btn.variant, btn.color); },

    // ===== core =====
    handle(ev){
      const d = this._norm(ev.detail);
      const target = d.target || this.id;
      if (target !== this.id) return;

      // flags dinámicos
      this.closeOnBackdrop = (typeof d.closeOnBackdrop === 'boolean') ? d.closeOnBackdrop : true;
      this.closeOnEscape   = (typeof d.closeOnEscape   === 'boolean') ? d.closeOnEscape   : true;

      // efectos
      this.effect         = d.effect   || 'zoom';
      this.duration       = (typeof d.duration === 'number') ? d.duration : 200;
      this.easing         = d.easing   || 'ease-out';
      this.overlayOpacity = (typeof d.overlayOpacity === 'number') ? d.overlayOpacity : 0.6;
      this.overlayBlur    = d.overlayBlur === true;

      // contenido
      this.cfg = d;
      const btns = normalizeButtons(d.buttons);
      this.buttons = btns.length ? btns : normalizeButtons([{ label:'OK', mode:'close', variant:'soft' }]);
      this.btnLoading = this.buttons.map(() => false);

      this.placement = (d.placement ?? this.placement);
      this.panelClass = (d.panelClass ?? this.panelClass);
      console.log('CONFIRM payload -> placement:', d.placement, 'panelClass:', d.panelClass, 'default:', this.placement, this.panelClass);

      // ⏯️ pipeline de animación
      this._openWithAnimation();
    },

    _openWithAnimation(){
      if (this.open && this.anim === 'open') return;
      this.open = true;       // muestra el wrapper (overlay+dialog)
      this.anim = 'enter';    // posición "cerrada" visible

      // salto de frame para que el browser pinte 'enter' y luego transicione a 'open'
      this.$nextTick(() => requestAnimationFrame(() => {
        this.anim = 'open';   // -> transiciona a la pose "abierta"
        // focus al primer control si existe
        this.$nextTick(() => { try { this.$refs.first && this.$refs.first.focus(); } catch(_){} });
      }));

      document.documentElement.classList.add('overflow-hidden');
    },

    close(){
      if (!this.open) return;
      this.anim = 'leave'; // vuelve a pose "cerrada" y deja que la transición corra
      const ms = Number(this.duration) || 200;
      setTimeout(() => {
        this.open = false;    // ahora sí ocultamos el wrapper
        this.anim = 'idle';
        document.documentElement.classList.remove('overflow-hidden');
      }, ms);
    },

    onBackdrop(e){
      if (!this.closeOnBackdrop) return;
      if (e.target === e.currentTarget) this.close();
    },

    onKeydown(e){
      if (!this.closeOnEscape) return;
      if (e.key === 'Escape') { e.preventDefault(); this.close(); }
    },

    async run(btn, i){
      const mode = btn.mode || (btn.wire ? 'wire' : (btn.emit ? 'emit' : 'close'));
      const dismiss = btn.dismissAfter === true;
      const compId = this.cfg.componentId;

      if (mode === 'wire' && compId && btn.wire) {
        try {
          this.btnLoading[i] = true;
          await Livewire.find(compId).call(btn.wire, ...(btn.params || []));
        } finally {
          this.btnLoading[i] = false;
          if (dismiss) this.close();
        }
        return;
      }

      if (mode === 'emit' && btn.emit) {
        if (compId) Livewire.dispatch(btn.emit, btn.payload || {}, { to: compId });
        else         Livewire.dispatch(btn.emit, btn.payload || {});
        if (dismiss) this.close();
        return;
      }

      // close
      this.close();
    },
  };
};

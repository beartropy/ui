// Beartropy UI - Bundled with esbuild

(() => {
  // resources/js/modules/dialog.js
  function dialog(payload) {
    if (typeof payload === "string") {
      payload = {
        type: arguments[0],
        title: arguments[1] ?? "",
        description: arguments[2] ?? ""
      };
    }
    if (!payload.id) {
      payload.id = window.crypto?.randomUUID?.() || "dialog-" + Math.random().toString(36).slice(2) + Date.now();
    }
    try {
      window.dispatchEvent(new CustomEvent("bt-dialog", { detail: payload }));
      return;
    } catch (e) {
      console.warn("[Beartropy] Dialog DOM dispatch fallback used:", e);
    }
    if (window.Livewire && window.Livewire.dispatch) {
      window.Livewire.dispatch("bt-dialog", payload);
      return;
    }
    console.warn("[Beartropy] Dialog: no Alpine or Livewire available");
  }
  ["success", "error", "warning", "info"].forEach((type) => {
    dialog[type] = (title, description = "", options = {}) => dialog({
      type,
      title,
      description,
      ...options
    });
  });
  dialog.confirm = function(config) {
    dialog({
      type: "confirm",
      icon: "question-mark-circle",
      allowOutsideClick: false,
      allowEscape: false,
      ...config
    });
  };
  dialog.delete = function(title, description = "", config = {}) {
    dialog({
      type: "danger",
      icon: "x-circle",
      title,
      description,
      allowOutsideClick: false,
      allowEscape: false,
      accept: {
        label: config.acceptLabel ?? "Delete",
        method: config.method ?? null,
        params: config.params ?? []
      },
      reject: {
        label: config.rejectLabel ?? "Cancel",
        method: config.rejectMethod ?? null,
        params: config.rejectParams ?? []
      },
      size: config.size ?? null,
      componentId: config.componentId ?? null
    });
  };

  // resources/js/modules/modal.js
  function openModal(id) {
    id = id.toLowerCase();
    window.dispatchEvent(new CustomEvent(`open-modal-${id}`));
  }
  function closeModal(id) {
    id = id.toLowerCase();
    window.dispatchEvent(new CustomEvent(`close-modal-${id}`));
  }

  // resources/js/modules/toast.js
  function toast(type, title, message = "", duration = 4e3, position = "top-right", action2 = null, actionUrl = null) {
    const toastObj = {
      id: window.crypto && window.crypto.randomUUID ? window.crypto.randomUUID() : "toast-" + Math.random().toString(36).slice(2) + Date.now(),
      type,
      title,
      message,
      duration,
      position,
      action: action2,
      actionUrl
    };
    if (window.Alpine && Alpine.store("toasts")) {
      Alpine.store("toasts").add(toastObj);
    } else if (window.Livewire && window.Livewire.dispatch) {
      window.Livewire.dispatch("beartropy-add-toast", toastObj);
    } else {
      console.warn("[Beartropy] Toast: no Alpine store or Livewire.dispatch available");
    }
  }
  ["success", "error", "warning", "info"].forEach((type) => {
    toast[type] = (title, message, duration, position, action2, actionUrl) => toast(type, title, message, duration, position, action2, actionUrl);
  });

  // resources/js/modules/table.js
  function beartropyTable({ data, columns, perPage, sortable, searchable, paginated }) {
    return {
      original: data,
      columns: Array.isArray(columns) ? columns : Object.keys(columns),
      colLabels: columns,
      perPage,
      sortable,
      searchable,
      paginated: paginated === void 0 ? true : paginated,
      search: "",
      sortBy: "",
      sortDesc: false,
      page: 1,
      get filtered() {
        let rows = this.original;
        if (this.search && this.searchable) {
          const s = this.search.toLowerCase();
          rows = rows.filter(
            (r) => this.columns.some(
              (c) => (r[c] ?? "").toString().toLowerCase().includes(s)
            )
          );
        }
        return rows;
      },
      get sorted() {
        if (!this.sortable || !this.sortBy) return this.filtered;
        return [...this.filtered].sort((a, b) => {
          let vA = a[this.sortBy] ?? "";
          let vB = b[this.sortBy] ?? "";
          if (!isNaN(vA) && !isNaN(vB)) {
            return this.sortDesc ? vB - vA : vA - vB;
          }
          return this.sortDesc ? vB.toString().localeCompare(vA.toString()) : vA.toString().localeCompare(vB.toString());
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
        this.page = 1;
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
      pagesToShow() {
        const total = this.totalPages;
        const current = this.page;
        const delta = 1;
        if (total <= 7) {
          return Array.from({ length: total }, (_, i) => i + 1);
        }
        let pages = [];
        pages.push(1);
        if (current - delta > 2) {
          pages.push("...");
        }
        let start = Math.max(2, current - delta);
        let end = Math.min(total - 1, current + delta);
        for (let i = start; i <= end; i++) {
          pages.push(i);
        }
        if (current + delta < total - 1) {
          pages.push("...");
        }
        pages.push(total);
        return pages;
      },
      init() {
        this.$watch("search", () => this.page = 1);
      }
    };
  }

  // resources/js/modules/datetime-picker.js
  function beartropyDatetimepicker(cfg) {
    return {
      value: cfg.value ?? "",
      open: false,
      range: !!cfg.range,
      min: cfg.min || "",
      max: cfg.max || "",
      showTime: !!cfg.showTime,
      disabled: !!cfg.disabled,
      startHour: "00",
      startMinute: "00",
      endHour: "00",
      endMinute: "00",
      formatDisplay: cfg.formatDisplay || "{d}/{m}/{Y}",
      panel: "date-start",
      startTimeSet: false,
      endTimeSet: false,
      hovered: null,
      month: (/* @__PURE__ */ new Date()).getMonth(),
      year: (/* @__PURE__ */ new Date()).getFullYear(),
      days: [],
      start: null,
      end: null,
      displayLabel: "",
      i18n: cfg.i18n ?? {},
      init() {
        this.setFromValue();
        let refDate = this.start ? new Date(this.start) : /* @__PURE__ */ new Date();
        if (this.start && /^\d{4}-\d{2}-\d{2}$/.test(this.start)) {
          let [y, m, d] = this.start.split("-");
          refDate = new Date(Number(y), Number(m) - 1, Number(d));
        }
        this.month = refDate.getMonth();
        this.year = refDate.getFullYear();
        this.updateCalendar();
        this.updateDisplay();
        this.setInitialPanel();
        this.$watch("open", (isOpen) => {
          if (isOpen) {
            this.setInitialPanel();
          }
        });
        this.$watch("value", () => {
          this.setFromValue();
          this.updateDisplay();
          let refDateWatch = this.start ? new Date(this.start) : /* @__PURE__ */ new Date();
          if (this.start && /^\d{4}-\d{2}-\d{2}$/.test(this.start)) {
            let [y, m, d] = this.start.split("-");
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
          let [date, time] = (this.value || "").split(" ");
          this.start = this.normalizeDate(date);
          this.startTimeSet = this.showTime && !!time;
          if (this.showTime && time) {
            let [h, m] = time.split(":");
            this.startHour = h?.padStart(2, "0") || "00";
            this.startMinute = m?.padStart(2, "0") || "00";
          } else if (this.showTime) {
            this.startHour = "00";
            this.startMinute = "00";
          }
          this.end = null;
          this.endTimeSet = false;
        } else if (this.value && typeof this.value === "object" && this.value.start && this.value.end) {
          let [date1, time1] = (this.value.start || "").split(" ");
          let [date2, time2] = (this.value.end || "").split(" ");
          this.start = this.normalizeDate(date1);
          this.end = this.normalizeDate(date2);
          this.startTimeSet = this.showTime && !!time1;
          this.endTimeSet = this.showTime && !!time2;
          if (this.showTime) {
            let [h1, m1] = time1 ? time1.split(":") : [];
            let [h2, m2] = time2 ? time2.split(":") : [];
            this.startHour = h1?.padStart(2, "0") || "00";
            this.startMinute = m1?.padStart(2, "0") || "00";
            this.endHour = h2?.padStart(2, "0") || "00";
            this.endMinute = m2?.padStart(2, "0") || "00";
          } else {
            this.startHour = "00";
            this.startMinute = "00";
            this.endHour = "00";
            this.endMinute = "00";
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
            this.showTime ? this.startHour : "",
            this.showTime ? this.startMinute : ""
          );
        } else if (this.start && this.end) {
          this.displayLabel = this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : "", this.showTime ? this.startMinute : "") + " - " + this.formatForDisplay(this.end, this.formatDisplay, this.showTime ? this.endHour : "", this.showTime ? this.endMinute : "");
        } else if (this.start) {
          this.displayLabel = this.formatForDisplay(this.start, this.formatDisplay, this.showTime ? this.startHour : "", this.showTime ? this.startMinute : "") + " - ...";
        } else {
          this.displayLabel = "";
        }
      },
      updateCalendar() {
        let first = new Date(this.year, this.month, 1);
        let last = new Date(this.year, this.month + 1, 0);
        let startDay = (first.getDay() + 6) % 7;
        let days = [];
        for (let i = 0; i < startDay; i++) {
          days.push({ label: "", date: "", inMonth: false });
        }
        for (let d = 1; d <= last.getDate(); d++) {
          let date = `${this.year}-${(this.month + 1).toString().padStart(2, "0")}-${d.toString().padStart(2, "0")}`;
          days.push({ label: d, date, inMonth: true });
        }
        while (days.length % 7) {
          days.push({ label: "", date: "", inMonth: false });
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
        const now = /* @__PURE__ */ new Date();
        const today = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, "0")}-${now.getDate().toString().padStart(2, "0")}`;
        return day.date === today;
      },
      selectDay(day) {
        if (this.isDisabled(day)) return;
        this.hovered = null;
        if (this.range) {
          const selectingEnd = this.panel === "date-end";
          if (selectingEnd) {
            if (day.date < this.start) {
              this.start = day.date;
              this.startHour = "00";
              this.startMinute = "00";
              this.startTimeSet = false;
              this.end = "";
              this.endHour = "00";
              this.endMinute = "00";
              this.endTimeSet = false;
              this.panel = this.showTime ? "time-start" : "date-end";
            } else {
              this.end = day.date;
              this.endHour = "00";
              this.endMinute = "00";
              this.endTimeSet = false;
              this.panel = this.showTime ? "time-end" : this.panel;
              if (!this.showTime) {
                this.value = { start: this.start, end: this.end };
                this.open = false;
              }
            }
          } else if (!this.start || this.start && this.end) {
            this.start = day.date;
            this.startHour = "00";
            this.startMinute = "00";
            this.end = "";
            this.endHour = "00";
            this.endMinute = "00";
            this.startTimeSet = false;
            this.endTimeSet = false;
            this.panel = this.showTime ? "time-start" : "date-end";
          } else if (day.date < this.start) {
            this.start = day.date;
            this.startHour = "00";
            this.startMinute = "00";
            this.end = "";
            this.endHour = "00";
            this.endMinute = "00";
            this.startTimeSet = false;
            this.endTimeSet = false;
            this.panel = this.showTime ? "time-start" : "date-end";
          } else {
            this.end = day.date;
            this.endHour = "00";
            this.endMinute = "00";
            this.endTimeSet = false;
            this.panel = this.showTime ? "time-end" : this.panel;
            if (!this.showTime) {
              this.value = { start: this.start, end: this.end };
              this.open = false;
            }
          }
        } else {
          this.start = day.date;
          this.startHour = "00";
          this.startMinute = "00";
          this.startTimeSet = false;
          if (!this.showTime) {
            this.value = this.start;
            this.open = false;
          } else {
            this.panel = "time-start";
          }
        }
        this.updateDisplay();
      },
      setTime(type, h, m, autoAdvance = false) {
        if (type === "start") {
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
            this.value = this.showTime ? {
              start: `${this.start} ${this.startHour}:${this.startMinute}`,
              end: `${this.end} ${this.endHour}:${this.endMinute}`
            } : { start: this.start, end: this.end };
            this.open = false;
            this.panel = "date-start";
          }
          if (this.range && type === "start" && this.start) {
            this.panel = this.end ? "time-end" : "date-end";
          }
          if (!this.range && this.start) {
            this.value = this.showTime ? `${this.start} ${this.startHour}:${this.startMinute}` : this.start;
            this.open = false;
            this.panel = "date-start";
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
        if (--this.month < 0) {
          this.month = 11;
          this.year--;
        }
        this.updateCalendar();
      },
      nextMonth() {
        if (++this.month > 11) {
          this.month = 0;
          this.year++;
        }
        this.updateCalendar();
      },
      normalizeDate(str) {
        if (!str) return "";
        if (/^\d{10}$/.test(str)) {
          const d = new Date(Number(str) * 1e3);
          return d.toISOString().slice(0, 10);
        }
        if (/^\d{13}$/.test(str)) {
          const d = new Date(Number(str));
          return d.toISOString().slice(0, 10);
        }
        let m = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})/);
        if (m) return `${m[1]}-${m[2].padStart(2, "0")}-${m[3].padStart(2, "0")}`;
        m = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})\s+\d{2}:\d{2}:\d{2}$/);
        if (m) return `${m[1]}-${m[2].padStart(2, "0")}-${m[3].padStart(2, "0")}`;
        m = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$/);
        if (m) return `${m[3]}-${m[2].padStart(2, "0")}-${m[1].padStart(2, "0")}`;
        m = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{2})$/);
        if (m) return `20${m[3]}-${m[2].padStart(2, "0")}-${m[1].padStart(2, "0")}`;
        m = str.match(/^(\d{4})[/-](\d{1,2})[/-](\d{1,2})/);
        if (m) return `${m[1]}-${m[2].padStart(2, "0")}-${m[3].padStart(2, "0")}`;
        return str;
      },
      formatForDisplay(dateStr, format = "{d}/{m}/{Y}", hour = "", minute = "") {
        if (!dateStr) return "";
        let [y, m, d] = dateStr.split("-");
        y = y ?? "";
        m = m ? m.padStart(2, "0") : "";
        d = d ? d.padStart(2, "0") : "";
        let out = format;
        out = out.replace(/{Y}/g, y);
        out = out.replace(/{m}/g, m);
        out = out.replace(/{d}/g, d);
        if (out.includes("{M}")) {
          let monthsShort = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
          out = out.replace(/{M}/g, monthsShort[parseInt(m, 10) - 1] || m);
        }
        if (out.includes("{MMMM}")) {
          let monthsLong = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
          out = out.replace(/{MMMM}/g, monthsLong[parseInt(m, 10) - 1] || m);
        }
        hour = (hour || "").padStart(2, "0");
        minute = (minute || "").padStart(2, "0");
        out = out.replace(/{H}/g, hour);
        out = out.replace(/{i}/g, minute);
        return out;
      },
      onDropdownClose() {
        if (this.range) {
          if (this.start && this.end) {
            this.value = this.showTime ? {
              start: `${this.start} ${this.startHour || "00"}:${this.startMinute || "00"}`,
              end: `${this.end} ${this.endHour || "00"}:${this.endMinute || "00"}`
            } : { start: this.start, end: this.end };
          }
        } else if (this.start) {
          this.value = this.showTime ? `${this.start} ${this.startHour || "00"}:${this.startMinute || "00"}` : this.start;
        }
        this.open = false;
        this.panel = "date-start";
        this.updateDisplay();
      },
      setInitialPanel() {
        if (!this.showTime) {
          this.panel = "date-start";
          return;
        }
        if (this.range) {
          if (!this.start) {
            this.panel = "date-start";
          } else if (!this.startTimeSet) {
            this.panel = "time-start";
          } else if (!this.end) {
            this.panel = "date-end";
          } else if (!this.endTimeSet) {
            this.panel = "time-end";
          } else {
            this.panel = "date-start";
          }
          return;
        }
        if (!this.start) {
          this.panel = "date-start";
        } else if (!this.startTimeSet) {
          this.panel = "time-start";
        } else {
          this.panel = "date-start";
        }
      },
      showCalendarPane() {
        if (!this.showTime) return true;
        return this.panel === "date-start" || this.panel === "date-end";
      },
      isPickingStartTime() {
        return this.showTime && this.panel === "time-start";
      },
      isPickingEndTime() {
        return this.showTime && this.panel === "time-end";
      },
      currentTimeType() {
        return this.panel === "time-end" ? "end" : "start";
      },
      // --- Wheel getters ---
      getHourForType(type) {
        return type === "end" ? this.endHour : this.startHour;
      },
      getMinuteForType(type) {
        return type === "end" ? this.endMinute : this.startMinute;
      },
      getAdjacentHour(type, offset) {
        const h = parseInt(this.getHourForType(type), 10);
        let next = h + offset;
        if (next < 0) next = 23;
        if (next > 23) next = 0;
        return String(next).padStart(2, "0");
      },
      getAdjacentMinute(type, offset) {
        const m = parseInt(this.getMinuteForType(type), 10);
        let next = m + offset;
        if (next < 0) next = 59;
        if (next > 59) next = 0;
        return String(next).padStart(2, "0");
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
        const h = String(next).padStart(2, "0");
        if (type === "end") {
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
        const m = String(next).padStart(2, "0");
        if (type === "end") {
          this.endMinute = m;
        } else {
          this.startMinute = m;
        }
        this.setTime(type, this.getHourForType(type), this.getMinuteForType(type));
      },
      setTimeNow(type) {
        if (this.disabled) return;
        const now = /* @__PURE__ */ new Date();
        const h = String(now.getHours()).padStart(2, "0");
        const m = String(now.getMinutes()).padStart(2, "0");
        if (type === "end") {
          this.endHour = h;
          this.endMinute = m;
        } else {
          this.startHour = h;
          this.startMinute = m;
        }
        this.setTime(type, h, m, true);
      },
      goToToday() {
        const now = /* @__PURE__ */ new Date();
        this.month = now.getMonth();
        this.year = now.getFullYear();
        this.updateCalendar();
      },
      clearSelection() {
        this.value = "";
        this.start = "";
        this.end = "";
        this.startHour = "00";
        this.startMinute = "00";
        this.endHour = "00";
        this.endMinute = "00";
        this.displayLabel = "";
        this.panel = "date-start";
        this.startTimeSet = false;
        this.endTimeSet = false;
        this.hovered = null;
      }
    };
  }

  // resources/js/modules/time-picker.js
  function beartropyTimepicker(cfg) {
    return {
      value: cfg.value ?? null,
      open: false,
      hour: null,
      minute: null,
      second: null,
      period: "AM",
      displayLabel: "",
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
        this.$watch("value", () => {
          this.setFromValue();
          this.updateDisplay();
        });
      },
      setFromValue() {
        if (!this.value) {
          this.hour = null;
          this.minute = null;
          this.second = null;
          this.period = "AM";
          return;
        }
        const parts = this.value.split(":");
        let h = parseInt(parts[0] || "0", 10);
        const m = parseInt(parts[1] || "0", 10);
        const s = parseInt(parts[2] || "0", 10);
        if (this.is12h) {
          this.period = h >= 12 ? "PM" : "AM";
          h = h % 12;
          if (h === 0) h = 12;
        }
        this.hour = String(h).padStart(2, "0");
        this.minute = String(m).padStart(2, "0");
        this.second = String(s).padStart(2, "0");
      },
      updateTime() {
        if (this.hour === null || this.minute === null) return;
        let h = parseInt(this.hour, 10);
        const m = parseInt(this.minute, 10);
        const s = parseInt(this.second || "0", 10);
        if (this.is12h) {
          h = this._to24h(h, this.period);
        }
        const hh = String(h).padStart(2, "0");
        const mm = String(m).padStart(2, "0");
        if (this.showSeconds) {
          const ss = String(s).padStart(2, "0");
          this.value = `${hh}:${mm}:${ss}`;
        } else {
          this.value = `${hh}:${mm}`;
        }
        this.updateDisplay();
      },
      updateDisplay() {
        if (!this.value) {
          this.displayLabel = "";
          return;
        }
        if (this.hour === null) {
          this.displayLabel = "";
          return;
        }
        const m = this.minute || "00";
        if (this.is12h) {
          const label = `${this.hour}:${m}`;
          this.displayLabel = this.showSeconds ? `${label}:${this.second || "00"} ${this.period}` : `${label} ${this.period}`;
        } else {
          const parts = this.value.split(":");
          const hh = parts[0] || "00";
          const mm = parts[1] || "00";
          this.displayLabel = this.showSeconds ? `${hh}:${mm}:${parts[2] || "00"}` : `${hh}:${mm}`;
        }
      },
      selectHour(h) {
        if (this.disabled) return;
        this.hour = h;
        if (this.minute === null) this.minute = "00";
        if (this.showSeconds && this.second === null) this.second = "00";
        this.updateTime();
      },
      selectMinute(m) {
        if (this.disabled) return;
        this.minute = m;
        if (this.hour === null) this.hour = "00";
        if (this.showSeconds && this.second === null) this.second = "00";
        this.updateTime();
      },
      selectSecond(s) {
        if (this.disabled) return;
        this.second = s;
        if (this.hour === null) this.hour = "00";
        if (this.minute === null) this.minute = "00";
        this.updateTime();
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
        this.period = "AM";
        this.displayLabel = "";
        this.open = false;
      },
      setNow() {
        if (this.disabled) return;
        const now = /* @__PURE__ */ new Date();
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
          this.period = h >= 12 ? "PM" : "AM";
          h = h % 12;
          if (h === 0) h = 12;
        }
        this.hour = String(h).padStart(2, "0");
        this.minute = String(m).padStart(2, "0");
        this.second = String(s).padStart(2, "0");
        this.updateTime();
      },
      // --- Adjacent value getters (for wheel display) ---
      getAdjacentHour(offset) {
        const hours = this.getHours();
        if (this.hour === null) return "";
        const idx = hours.indexOf(this.hour);
        if (idx === -1) return "";
        let next = idx + offset;
        if (next < 0) next = hours.length - 1;
        if (next >= hours.length) next = 0;
        return hours[next];
      },
      getAdjacentMinute(offset) {
        const minutes = this.getMinutes();
        if (this.minute === null) return "";
        const idx = minutes.indexOf(this.minute);
        if (idx === -1) return "";
        let next = idx + offset;
        if (next < 0) next = minutes.length - 1;
        if (next >= minutes.length) next = 0;
        return minutes[next];
      },
      getAdjacentSecond(offset) {
        const seconds = this.getSeconds();
        if (this.second === null) return "";
        const idx = seconds.indexOf(this.second);
        if (idx === -1) return "";
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
        this.moveMinute(event.deltaY > 0 ? 1 : -1);
      },
      wheelSecond(event) {
        if (this.disabled) return;
        this.moveSecond(event.deltaY > 0 ? 1 : -1);
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
          const minH = parseInt(this.min.split(":")[0], 10);
          if (h24 < minH) return true;
        }
        if (this.max) {
          const maxH = parseInt(this.max.split(":")[0], 10);
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
          const [minH, minM] = this.min.split(":").map(Number);
          if (h24 === minH && mInt < minM) return true;
        }
        if (this.max) {
          const [maxH, maxM] = this.max.split(":").map(Number);
          if (h24 === maxH && mInt > maxM) return true;
        }
        return false;
      },
      // --- Value generators ---
      getHours() {
        const hours = [];
        if (this.is12h) {
          for (let i = 1; i <= 12; i++) {
            hours.push(String(i).padStart(2, "0"));
          }
        } else {
          for (let i = 0; i < 24; i++) {
            hours.push(String(i).padStart(2, "0"));
          }
        }
        return hours;
      },
      getMinutes() {
        const minutes = [];
        for (let i = 0; i < 60; i += this.interval) {
          minutes.push(String(i).padStart(2, "0"));
        }
        return minutes;
      },
      getSeconds() {
        const seconds = [];
        for (let i = 0; i < 60; i++) {
          seconds.push(String(i).padStart(2, "0"));
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
      moveMinute(direction) {
        const minutes = this.getMinutes();
        if (this.minute === null) {
          this.selectMinute(minutes[0]);
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
          this.selectMinute(minutes[next]);
        }
      },
      moveSecond(direction) {
        const seconds = this.getSeconds();
        if (this.second === null) {
          this.selectSecond(seconds[0]);
          return;
        }
        const idx = seconds.indexOf(this.second);
        let next = idx + direction;
        if (next < 0) next = seconds.length - 1;
        if (next >= seconds.length) next = 0;
        this.selectSecond(seconds[next]);
      },
      // --- Helpers ---
      _toMinutes(timeStr) {
        if (!timeStr) return 0;
        const [h, m] = timeStr.split(":").map(Number);
        return h * 60 + (m || 0);
      },
      _to24h(h, period) {
        if (period === "AM") {
          return h === 12 ? 0 : h;
        }
        return h === 12 ? 12 : h + 12;
      }
    };
  }

  // resources/js/modules/tag-input.js
  function beartropyTagInput({ initialTags = [], unique = true, maxTags = null, disabled = false, separator = "," }) {
    let seps = Array.isArray(separator) ? separator : separator.split("");
    let sepRegex = new RegExp(`[${seps.map((s) => s.replace(/[-[\]/{}()*+?.\\^$|]/g, "\\$&")).join("")}]+`);
    return {
      tags: initialTags ?? [],
      input: "",
      unique,
      maxTags,
      disabled,
      separator,
      focusInput() {
        if (!this.disabled) this.$refs.input.focus();
      },
      addTag() {
        let val = this.input.trim();
        if (!val) return this.input = "";
        let parts = val.split(sepRegex).map((t) => t.trim()).filter(Boolean);
        parts.forEach((tag) => this._tryAddTag(tag));
        this.input = "";
      },
      removeTag(i) {
        if (!this.disabled) this.tags.splice(i, 1);
      },
      clearAll() {
        if (!this.disabled) {
          this.tags = [];
          this.input = "";
        }
      },
      removeOnBackspace(e) {
        if (!this.input && this.tags.length && !this.disabled) this.tags.pop();
      },
      addTagOnTab(e) {
        if (this.input) {
          this.addTag();
          e.preventDefault();
        }
      },
      handlePaste(e) {
        let paste = (e.clipboardData || window.clipboardData).getData("text");
        if (paste && sepRegex.test(paste)) {
          sepRegex.lastIndex = 0;
          let newTags = paste.split(sepRegex).map((t) => t.trim()).filter(Boolean);
          newTags.forEach((tag) => this._tryAddTag(tag));
          e.preventDefault();
          this.input = "";
        }
      },
      _tryAddTag(tag) {
        if (!tag) return;
        if (this.unique && this.tags.includes(tag)) return;
        if (this.maxTags && this.tags.length >= this.maxTags) return;
        this.tags.push(tag);
      }
    };
  }

  // resources/js/modules/confirm.js
  function confirmHost({
    id,
    defaultPlacement = "top",
    defaultPanelClass = "mt-32"
  }) {
    function toArray(v) {
      return Array.isArray(v) ? v : v != null ? [v] : [];
    }
    function fallbackToken(variant, color) {
      const v = variant || "soft", c = color || "gray";
      if (v === "primary" && c === "blue") return "btc-primary-blue";
      if (v === "primary" && c === "gray") return "btc-primary-gray";
      if (v === "primary" && c === "green") return "btc-primary-green";
      if (v === "primary" && c === "amber") return "btc-primary-amber";
      if (v === "danger") return "btc-danger-red";
      if (v === "ghost") return "btc-ghost";
      if (v === "outline") return "btc-outline";
      return "btc-soft";
    }
    function normalizeButtons(arr) {
      return (Array.isArray(arr) ? arr : []).map((b) => ({
        label: b?.label ?? "OK",
        // semantic (optional)
        variant: b?.variant ?? "soft",
        color: b?.color ?? "gray",
        // class token from server (or fallback)
        token: b?.token ?? fallbackToken(b?.variant, b?.color),
        // acciones
        mode: b?.mode ?? (b?.wire ? "wire" : b?.emit ? "emit" : "close"),
        wire: b?.wire ?? null,
        params: toArray(b?.params),
        emit: b?.emit ?? null,
        payload: b?.payload ?? {},
        dismissAfter: b?.dismissAfter === true,
        close: b?.close === true,
        spinner: b?.spinner === true,
        role: b?.role ?? null
      }));
    }
    function dialogClosed(effect) {
      switch (effect) {
        case "slide-up":
          return "opacity-0 translate-y-2";
        case "slide-down":
          return "opacity-0 -translate-y-2";
        case "slide-left":
          return "opacity-0 translate-x-2";
        case "slide-right":
          return "opacity-0 -translate-x-2";
        case "fade":
          return "opacity-0";
        case "zoom":
        default:
          return "opacity-0 scale-95";
      }
    }
    function dialogOpen(effect) {
      switch (effect) {
        case "slide-up":
        case "slide-down":
        case "slide-left":
        case "slide-right":
          return "opacity-100 translate-x-0 translate-y-0";
        case "fade":
          return "opacity-100";
        case "zoom":
        default:
          return "opacity-100 scale-100";
      }
    }
    return {
      // ===== state =====
      id,
      cfg: {},
      buttons: [],
      btnLoading: [],
      open: false,
      // wrapper visibility
      anim: "idle",
      // 'idle' | 'enter' | 'open' | 'leave'
      // flags/effects (configurable per payload)
      closeOnBackdrop: true,
      closeOnEscape: true,
      effect: "zoom",
      duration: 200,
      // ms
      easing: "ease-out",
      overlayOpacity: 0.6,
      // 0..1
      overlayBlur: false,
      placement: defaultPlacement,
      // 'top' | 'center'
      panelClass: defaultPanelClass,
      // ej. 'mt-32'
      // ===== utils =====
      _norm(raw) {
        if (!raw) return {};
        if (Array.isArray(raw)) return raw[0] ?? {};
        return raw;
      },
      sizeClasses() {
        switch (this.cfg.size) {
          case "sm":
            return "max-w-sm";
          case "lg":
            return "max-w-2xl";
          case "xl":
            return "max-w-4xl";
          case "2xl":
            return "max-w-6xl";
          default:
            return "max-w-lg";
        }
      },
      containerClass() {
        return this.placement === "top" ? "flex justify-center items-start" : "grid place-items-center";
      },
      overlayStyle() {
        const d = Number(this.duration) || 200;
        const e = this.easing || "ease-out";
        const o = this.anim === "open" ? this.overlayOpacity : 0;
        return `opacity:${o}; transition: opacity ${d}ms ${e};`;
      },
      dialogStyle() {
        const d = Number(this.duration) || 200;
        const e = this.easing || "ease-out";
        return `transition: transform ${d}ms ${e}, opacity ${d}ms ${e};`;
      },
      dialogMotionClass() {
        return this.anim === "open" ? dialogOpen(this.effect) : dialogClosed(this.effect);
      },
      btnClass(btn) {
        return classesFor(btn.variant, btn.color);
      },
      // ===== core =====
      handle(ev) {
        const d = this._norm(ev.detail);
        const target2 = d.target ?? this.id;
        if (target2 !== this.id) return;
        this.closeOnBackdrop = typeof d.closeOnBackdrop === "boolean" ? d.closeOnBackdrop : true;
        this.closeOnEscape = typeof d.closeOnEscape === "boolean" ? d.closeOnEscape : true;
        this.effect = d.effect || "zoom";
        this.duration = typeof d.duration === "number" ? d.duration : 200;
        this.easing = d.easing || "ease-out";
        this.overlayOpacity = typeof d.overlayOpacity === "number" ? d.overlayOpacity : 0.6;
        this.overlayBlur = d.overlayBlur === true;
        this.placement = d.placement ?? this.placement;
        this.panelClass = d.panelClass ?? this.panelClass;
        this.autofocus = d.autofocus || this.autofocus;
        this.cfg = d;
        const btns = normalizeButtons(d.buttons);
        this.buttons = btns.length ? btns : normalizeButtons([{ label: "OK", mode: "close" }]);
        this.btnLoading = this.buttons.map(() => false);
        this._openWithAnimation();
      },
      _openWithAnimation() {
        if (this.open && this.anim === "open") return;
        this.open = true;
        this.anim = "enter";
        this.$nextTick(() => requestAnimationFrame(() => {
          this.anim = "open";
          this.$nextTick(() => {
            try {
              this.$refs.first && this.$refs.first.focus();
            } catch (_) {
            }
          });
        }));
        document.documentElement.classList.add("overflow-hidden");
      },
      close() {
        if (!this.open) return;
        this.anim = "leave";
        const ms = Number(this.duration) || 200;
        setTimeout(() => {
          this.open = false;
          this.anim = "idle";
          document.documentElement.classList.remove("overflow-hidden");
        }, ms);
      },
      onBackdrop(e) {
        if (!this.closeOnBackdrop) return;
        if (e.target === e.currentTarget) this.close();
      },
      onKeydown(e) {
        if (!this.closeOnEscape) return;
        if (e.key === "Escape") {
          e.preventDefault();
          this.close();
        }
      },
      async run(btn, i) {
        const mode = btn.mode || (btn.wire ? "wire" : btn.emit ? "emit" : "close");
        const dismiss = btn.dismissAfter === true;
        const compId = this.cfg.componentId;
        if (mode === "wire" && compId && btn.wire) {
          try {
            this.btnLoading[i] = true;
            await Livewire.find(compId).call(btn.wire, ...btn.params || []);
          } finally {
            this.btnLoading[i] = false;
            if (dismiss) this.close();
          }
          return;
        }
        if (mode === "emit" && btn.emit) {
          if (compId) Livewire.dispatch(btn.emit, btn.payload || {}, { to: compId });
          else Livewire.dispatch(btn.emit, btn.payload || {});
          if (dismiss) this.close();
          return;
        }
        this.close();
      }
    };
  }

  // resources/js/modules/bt-dialog.js
  function btDialog({ globalSize = "md", typeStyles = {} } = {}) {
    return {
      isOpen: false,
      type: "info",
      title: "",
      description: "",
      icon: null,
      accept: null,
      reject: null,
      componentId: null,
      panelSizeClass: "",
      globalSize,
      typeStyles,
      acceptBusy: false,
      rejectBusy: false,
      allowOutsideClick: false,
      allowEscape: false,
      get canCloseViaButton() {
        return true;
      },
      get isSingleButton() {
        const hasAcceptAction = this.accept && this.accept.method;
        const hasRejectAction = this.reject && this.reject.method;
        if (hasAcceptAction || hasRejectAction) {
          return false;
        }
        return true;
      },
      buttonColors: {
        info: "bg-blue-700 hover:bg-blue-600 dark:bg-blue-800 dark:hover:bg-blue-700 text-white",
        success: "bg-emerald-700 hover:bg-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white",
        warning: "bg-amber-700 hover:bg-amber-600 dark:bg-amber-800 dark:hover:bg-amber-700 text-white",
        error: "bg-rose-700 hover:bg-rose-600 dark:bg-rose-800 dark:hover:bg-rose-700 text-white",
        danger: "bg-rose-700 hover:bg-rose-600 dark:bg-rose-800 dark:hover:bg-rose-700 text-white"
      },
      defaultIconForType(type) {
        switch (type) {
          case "success":
            return "check-circle";
          case "error":
            return "x-circle";
          case "warning":
            return "exclamation-triangle";
          case "confirm":
            return "question-mark-circle";
          case "danger":
            return "x-circle";
          case "info":
          default:
            return "information-circle";
        }
      },
      openDialog(raw) {
        const payload = Array.isArray(raw) ? raw[0] ?? {} : raw ?? {};
        this.acceptBusy = false;
        this.rejectBusy = false;
        const size = payload.size ?? this.globalSize ?? "md";
        const classes = {
          sm: "max-w-md",
          md: "max-w-lg",
          lg: "max-w-xl",
          xl: "max-w-2xl",
          "2xl": "max-w-3xl"
        };
        this.panelSizeClass = classes[size] ?? classes["md"];
        this.type = payload.type ?? "info";
        this.title = payload.title ?? "";
        this.description = payload.description ?? "";
        this.icon = payload.icon ?? this.defaultIconForType(this.type);
        this.accept = payload.accept ?? null;
        this.reject = payload.reject ?? null;
        this.componentId = payload.componentId ?? null;
        this.allowOutsideClick = payload.allowOutsideClick ?? false;
        this.allowEscape = payload.allowEscape ?? false;
        this.isOpen = true;
        this.$nextTick(() => {
          const primary = this.$el.querySelector('[x-on\\:click="clickAccept()"]');
          if (primary) primary.focus();
        });
      },
      close() {
        this.isOpen = false;
      },
      backdropClick() {
        if (this.allowOutsideClick) {
          this.close();
        }
      },
      clickAccept() {
        if (this.accept && this.accept.method && this.componentId && window.Livewire) {
          if (this.acceptBusy) return;
          this.acceptBusy = true;
          const params = Array.isArray(this.accept.params) ? this.accept.params : this.accept.params !== void 0 ? [this.accept.params] : [];
          const comp = window.Livewire.find(this.componentId);
          const finish = () => {
            this.acceptBusy = false;
            this.close();
          };
          if (comp) {
            try {
              const result = comp.call(this.accept.method, ...params);
              if (result && typeof result.then === "function") {
                result.then(finish).catch(finish);
              } else {
                finish();
              }
            } catch (e) {
              console.error("[Dialog] accept method error", e);
              finish();
            }
          } else {
            finish();
          }
        } else {
          this.close();
        }
      },
      clickReject() {
        if (this.reject && this.reject.method && this.componentId && window.Livewire) {
          if (this.rejectBusy) return;
          this.rejectBusy = true;
          const params = Array.isArray(this.reject.params) ? this.reject.params : this.reject.params !== void 0 ? [this.reject.params] : [];
          const comp = window.Livewire.find(this.componentId);
          const finish = () => {
            this.rejectBusy = false;
            this.close();
          };
          if (comp) {
            try {
              const result = comp.call(this.reject.method, ...params);
              if (result && typeof result.then === "function") {
                result.then(finish).catch(finish);
              } else {
                finish();
              }
            } catch (e) {
              console.error("[Dialog] reject method error", e);
              finish();
            }
          } else {
            finish();
          }
        } else {
          this.close();
        }
      },
      init() {
        window.addEventListener("keydown", (e) => {
          if (e.key === "Escape" && this.isOpen && this.allowEscape) {
            e.preventDefault();
            this.close();
          }
        });
      }
    };
  }

  // resources/js/modules/select.js
  function beartropySelect(cfg) {
    return {
      _cfg: cfg,
      value: cfg.value,
      open: false,
      options: cfg.options,
      search: "",
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
      saveState: "idle",
      _saveT: null,
      hasFieldError: cfg.hasFieldError,
      showSpinner: cfg.showSpinner,
      init() {
        if (this._cfg.hasWireModel) {
          if (this.isMulti) {
            this.$watch("$wire." + this._cfg.name, (v) => {
              this.value = v;
              this.syncInput();
            });
          } else {
            this.$watch("$wire." + this._cfg.name, (v) => {
              this.value = v;
            });
          }
        }
        if (this.isMulti && Array.isArray(this.value)) {
          this.value = this.value.map(String);
        }
        if (!this._cfg.defer && this.remoteUrl) {
          this.fetchOptions(true);
          this.initDone = true;
        }
        this.$watch("search", () => {
          this.page = 1;
          this.highlightedIndex = 0;
          this.fetchOptions(true);
        });
        this.$watch("open", (v) => {
          if (v) {
            this.focusSearch();
          }
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
        if (!this.open || !this.filteredOptions().length) {
          return;
        }
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
          const list = document.getElementById(this._cfg.selectId + "-list");
          if (!list) {
            return;
          }
          const el = list.querySelector('[data-select-index="' + this.highlightedIndex + '"]');
          if (el) {
            el.scrollIntoView({ block: "nearest" });
          }
        });
      },
      triggerAutosave() {
        if (!this.autosave || !this.autosaveMethod || !this.autosaveKey) {
          return;
        }
        clearTimeout(this._saveT);
        this.saveState = "saving";
        this._saveT = setTimeout(() => {
          this.$wire.call(this.autosaveMethod, this.value, this.autosaveKey).then(() => {
            this.saveState = "ok";
            setTimeout(() => {
              if (this.saveState === "ok") {
                this.saveState = "idle";
              }
            }, 2e3);
          }).catch(() => {
            this.saveState = "error";
            setTimeout(() => {
              if (this.saveState === "error") {
                this.saveState = "idle";
              }
            }, 3e3);
          });
        }, this.autosaveDebounce);
      },
      filteredOptions() {
        if (this.remoteUrl) {
          return Object.entries(this.options);
        }
        const entries = Object.entries(this.options);
        if (!this.search) {
          return entries;
        }
        return entries.filter(
          ([id, opt]) => (opt.label ?? opt ?? id).toLowerCase().includes(this.search.toLowerCase())
        );
      },
      isSelected(id) {
        if (this.isMulti) {
          return Array.isArray(this.value) ? this.value.map(String).includes(String(id)) : false;
        }
        return String(this.value) === String(id);
      },
      setValue(id) {
        id = String(id);
        if (this.isMulti) {
          if (!Array.isArray(this.value)) {
            this.value = [];
          }
          const valueStr = this.value.map(String);
          if (valueStr.includes(id)) {
            this.value = valueStr.filter((v) => v !== id);
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
        if (!this.value) {
          return;
        }
        this.value = this.value.filter((v) => v !== id);
        this.syncInput();
      },
      syncInput() {
        if (!this._cfg.hasWireModel) {
          this.$refs.multiInputs.innerHTML = "";
          if (this.isMulti) {
            if (Array.isArray(this.value)) {
              this.value.forEach((val) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = this._cfg.name + "[]";
                input.value = val;
                this.$refs.multiInputs.appendChild(input);
              });
            }
          } else {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = this._cfg.name;
            input.value = this.value ?? "";
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
        return Array.isArray(this.value) && this.value.length > this.maxChips ? this.value.length - this.maxChips : 0;
      },
      clearValue() {
        if (this.isMulti) {
          this.value = [];
        } else {
          this.value = "";
        }
        this.syncInput();
      },
      fetchOptions(reset = false) {
        if (!this.remoteUrl || this.loading) {
          return;
        }
        this.loading = true;
        let params = new URLSearchParams({
          q: this.search,
          page: this.page,
          per_page: this.perPage
        }).toString();
        fetch(`${this.remoteUrl}?${params}`).then((res) => res.json()).then((data) => {
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
        if (!this.hasMore || this.loading || !this.open) {
          return;
        }
        setTimeout(() => {
          const el = document.getElementById(this._cfg.selectId + "-list");
          if (el && el.scrollHeight <= el.clientHeight + 10) {
            this.page++;
            this.fetchOptions();
          }
        }, 50);
      },
      focusSearch() {
        this.$nextTick(() => {
          requestAnimationFrame(() => {
            let el = document.getElementById(this._cfg.selectId + "-search");
            if (!el && this.$refs.searchHost) {
              el = this.$refs.searchHost.querySelector("[data-beartropy-input]");
            }
            if (!el) {
              el = this.$root.querySelector("[data-beartropy-input]");
            }
            if (el) {
              el.focus({ preventScroll: true });
              try {
                el.select?.();
              } catch (_) {
              }
              this._bindSearchKeyboard(el);
            }
          });
        });
      },
      _bindSearchKeyboard(el) {
        if (el._beartropyKeyBound) {
          return;
        }
        el._beartropyKeyBound = true;
        el.addEventListener("keydown", (e) => {
          if (e.key === "ArrowDown") {
            e.preventDefault();
            this.move(1);
          } else if (e.key === "ArrowUp") {
            e.preventDefault();
            this.move(-1);
          } else if (e.key === "Enter") {
            e.preventDefault();
            if (this.highlightedIndex >= 0) {
              this.selectHighlighted();
            }
          } else if (e.key === "Escape") {
            e.preventDefault();
            this.close();
          }
        });
      }
    };
  }

  // resources/js/modules/file-dropzone.js
  function beartropyFileDropzone(cfg) {
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
            id: "existing-" + i + "-" + Date.now()
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
            this.errors.push(cfg.i18n.file_type_not_accepted.replace(":name", file.name));
            continue;
          }
          if (cfg.maxFileSize && file.size > cfg.maxFileSize) {
            this.errors.push(
              cfg.i18n.file_too_large.replace(":name", file.name).replace(":max", this.formatSize(cfg.maxFileSize))
            );
            continue;
          }
          valid.push(file);
        }
        let toAdd = valid;
        if (cfg.maxFiles) {
          const currentCount = cfg.multiple ? this.files.length : 0;
          if (currentCount + toAdd.length > cfg.maxFiles) {
            this.errors.push(cfg.i18n.max_files_exceeded.replace(":max", cfg.maxFiles));
            toAdd = toAdd.slice(0, Math.max(0, cfg.maxFiles - currentCount));
          }
        }
        if (!toAdd.length) {
          this.$refs.input.value = "";
          return;
        }
        const entries = toAdd.map((file) => ({
          file,
          id: "file-" + Math.random().toString(36).slice(2) + Date.now(),
          status: "pending",
          progress: 0,
          preview: file.type.startsWith("image/") ? URL.createObjectURL(file) : null
        }));
        if (cfg.multiple) {
          this.files = this.files.concat(entries);
        } else {
          this.files.forEach((f) => {
            if (f.preview) URL.revokeObjectURL(f.preview);
          });
          this.files = entries.slice(0, 1);
        }
        this._syncInput();
      },
      removeFile(id) {
        const idx = this.files.findIndex((f) => f.id === id);
        if (idx === -1) return;
        const file = this.files[idx];
        if (file.preview) URL.revokeObjectURL(file.preview);
        this.files.splice(idx, 1);
        this._syncInput();
      },
      removeExisting(id) {
        this.existingFiles = this.existingFiles.filter((f) => f.id !== id);
        this.$dispatch("existing-file-removed", { id });
      },
      clearFiles() {
        this.files.forEach((f) => {
          if (f.preview) URL.revokeObjectURL(f.preview);
        });
        this.files = [];
        this._syncInput();
      },
      formatSize(bytes) {
        if (bytes < 1024) return bytes + " B";
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
        if (bytes < 1073741824) return (bytes / 1048576).toFixed(1) + " MB";
        return (bytes / 1073741824).toFixed(1) + " GB";
      },
      getFileIcon(mimeType) {
        if (!mimeType) return "document";
        if (mimeType.startsWith("image/")) return "photo";
        if (mimeType.startsWith("video/")) return "film";
        if (mimeType.startsWith("audio/")) return "musical-note";
        if (mimeType === "application/pdf") return "document-text";
        return "document";
      },
      _syncInput() {
        try {
          const dt = new DataTransfer();
          this.files.forEach((f) => dt.items.add(f.file));
          this.$refs.input.files = dt.files;
        } catch (e) {
        }
      },
      _matchesAccept(file, accept) {
        const types = accept.split(",").map((t) => t.trim().toLowerCase());
        const fileName = file.name.toLowerCase();
        const mimeType = file.type.toLowerCase();
        return types.some((type) => {
          if (type.startsWith(".")) {
            return fileName.endsWith(type);
          }
          if (type.endsWith("/*")) {
            return mimeType.startsWith(type.replace("/*", "/"));
          }
          return mimeType === type;
        });
      }
    };
  }

  // resources/js/modules/chat-input.js
  function beartropyChatInput(cfg) {
    return {
      val: cfg.value ?? "",
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
        this.$watch("val", () => {
          this.$nextTick(() => this.resize());
        });
      },
      resize() {
        const textarea = this.$refs.textarea;
        const currentWidth = textarea.offsetWidth;
        textarea.style.width = currentWidth + "px";
        textarea.style.height = "auto";
        const scrollH = textarea.scrollHeight;
        const newHeight = Math.max(scrollH, this.baseHeight || 0);
        textarea.style.height = newHeight + "px";
        textarea.style.width = "";
        textarea.style.overflowY = newHeight >= 240 ? "auto" : "hidden";
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
      }
    };
  }

  // resources/js/modules/lookup.js
  function beartropyLookup(cfg) {
    return {
      // State
      open: false,
      highlighted: -1,
      options: [],
      filtered: [],
      // Config from Blade
      inputId: cfg.inputId,
      isLivewire: cfg.isLivewire ?? false,
      labelKey: cfg.labelKey ?? "name",
      valueKey: cfg.valueKey ?? "id",
      wireModelName: cfg.wireModelName ?? null,
      init() {
        this._syncOptionsFromAttr();
        const obs = new MutationObserver((muts) => {
          for (const m of muts) {
            if (m.type === "attributes" && m.attributeName === "data-options") {
              this._syncOptionsFromAttr();
            }
          }
        });
        obs.observe(this.$el, { attributes: true });
        this._obs = obs;
        if (this.isLivewire && this.wireModelName) {
          this.$watch("$wire." + this.wireModelName, () => {
            this._syncFromLivewire();
          });
        }
      },
      // Data helpers
      _syncOptionsFromAttr() {
        const raw = this.$el.getAttribute("data-options") || "[]";
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
        if (!this.isLivewire || !this.wireModelName || !this.$wire) {
          return;
        }
        const current = this.$wire.get(this.wireModelName) ?? "";
        if (!current) {
          this.setVisibleValue("");
          return;
        }
        const match = this.options.find(
          (o) => String(this.getValue(o)) === String(current)
        );
        const label = match ? this.getLabel(match) : current;
        this.setVisibleValue(label);
      },
      normalize(s) {
        if (!s) {
          return "";
        }
        return s.toString().normalize("NFD").replace(/\p{Diacritic}/gu, "").trim().toLowerCase();
      },
      getLabel(o) {
        return o?.[this.labelKey] ?? "";
      },
      getValue(o) {
        return o?.[this.valueKey] ?? "";
      },
      exactMatch(txt) {
        const t = this.normalize(txt);
        return this.options.find((o) => this.normalize(this.getLabel(o)) === t) || null;
      },
      // Events / main logic
      onInput(e) {
        const raw = e?.target?.value ?? "";
        const t = this.normalize(raw);
        this.filtered = !t ? this.options : this.options.filter((o) => this.normalize(this.getLabel(o)).includes(t));
        this.open = true;
        this.highlighted = this.filtered.length ? 0 : -1;
        this._setHiddenFromRawOrMatch(raw);
      },
      move(delta) {
        if (!this.open || !this.filtered.length) {
          return;
        }
        const n = this.filtered.length;
        this.highlighted = (this.highlighted + delta + n) % n;
      },
      choose(idx) {
        const opt = this.filtered[idx];
        if (!opt) {
          return;
        }
        this.setVisibleValue(this.getLabel(opt));
        if (this.isLivewire && this.$refs.livewireValue) {
          this.$refs.livewireValue.value = this.getValue(opt);
          this.$refs.livewireValue.dispatchEvent(new Event("input", { bubbles: true }));
        }
        this.open = false;
      },
      confirm() {
        if (this.highlighted >= 0) {
          this.choose(this.highlighted);
        } else {
          const raw = document.getElementById(this.inputId)?.value ?? "";
          this._setHiddenFromRawOrMatch(raw);
          this.open = false;
        }
      },
      close() {
        this.open = false;
      },
      setVisibleValue(v) {
        const el = document.getElementById(this.inputId);
        if (!el) {
          return;
        }
        el.value = v ?? "";
        el.dispatchEvent(new Event("input", { bubbles: true }));
      },
      clearBoth() {
        this.setVisibleValue("");
        if (this.isLivewire && this.$refs.livewireValue) {
          this.$refs.livewireValue.value = "";
          this.$refs.livewireValue.dispatchEvent(new Event("input", { bubbles: true }));
        }
        this.filtered = this.options;
        this.highlighted = -1;
        this.open = false;
      },
      _setHiddenFromRawOrMatch(raw) {
        if (!this.isLivewire || !this.$refs.livewireValue) {
          return;
        }
        const match = this.exactMatch(raw);
        this.$refs.livewireValue.value = match ? this.getValue(match) : raw;
        this.$refs.livewireValue.dispatchEvent(new Event("input", { bubbles: true }));
      },
      _reconcileFromVisible() {
        const el = document.getElementById(this.inputId);
        if (!el) {
          return;
        }
        this._setHiddenFromRawOrMatch(el.value || "");
      }
    };
  }

  // resources/js/modules/toggle-theme.js
  function initTheme() {
    function computeDark() {
      const saved = localStorage.getItem("theme");
      if (saved === "dark") return true;
      if (saved === "light") return false;
      return window.matchMedia("(prefers-color-scheme: dark)").matches;
    }
    function applyTheme(dark) {
      document.documentElement.classList.toggle("dark", dark);
      document.documentElement.style.colorScheme = dark ? "dark" : "light";
    }
    applyTheme(computeDark());
    window.__setTheme = function(mode) {
      const dark = mode === "dark";
      localStorage.setItem("theme", dark ? "dark" : "light");
      applyTheme(dark);
      window.dispatchEvent(new CustomEvent("theme-change", { detail: { theme: dark ? "dark" : "light" } }));
    };
    window.addEventListener("livewire:navigated", () => {
      applyTheme(computeDark());
    });
  }
  function btToggleTheme() {
    return {
      dark: localStorage.theme === "dark" || !("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches,
      rotating: false,
      init() {
        window.addEventListener("theme-change", (e) => {
          if (e.detail && e.detail.theme) {
            this.dark = e.detail.theme === "dark";
          }
        });
      },
      toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle("dark", this.dark);
        document.documentElement.style.colorScheme = this.dark ? "dark" : "light";
        localStorage.theme = this.dark ? "dark" : "light";
        window.dispatchEvent(new CustomEvent("theme-change", { detail: { theme: this.dark ? "dark" : "light" } }));
        this.$nextTick(() => {
          this.rotating = true;
          setTimeout(() => {
            this.rotating = false;
          }, 500);
        });
      }
    };
  }

  // resources/js/modules/command-palette.js
  function btCommandPalette({ initial }) {
    return {
      open: false,
      query: "",
      all: initial || [],
      selectedIndex: 0,
      get filtered() {
        const q = (this.query || "").toLowerCase().trim();
        if (!q) return (this.all || []).slice(0, 5);
        const terms = q.split(/\s+/);
        const results = (this.all || []).filter((i) => {
          const text = [
            i.title ?? "",
            i.description ?? "",
            Array.isArray(i.tags) ? i.tags.join(" ") : "",
            i.action ?? ""
          ].join(" ").toLowerCase();
          return terms.every((t) => text.includes(t));
        });
        if (results.length && this.selectedIndex >= results.length) this.selectedIndex = 0;
        return results;
      },
      scrollIntoView() {
        this.$nextTick(() => {
          const el = document.querySelector(`[data-cp-index="${this.selectedIndex}"]`);
          if (el) el.scrollIntoView({ block: "nearest", behavior: "smooth" });
        });
      },
      handleKey(e) {
        if (!this.filtered.length) return;
        if (["ArrowDown", "Tab"].includes(e.key) && !e.shiftKey) {
          e.preventDefault();
          this.selectedIndex = (this.selectedIndex + 1) % this.filtered.length;
          this.scrollIntoView();
        } else if (["ArrowUp"].includes(e.key) || e.key === "Tab" && e.shiftKey) {
          e.preventDefault();
          this.selectedIndex = (this.selectedIndex - 1 + this.filtered.length) % this.filtered.length;
          this.scrollIntoView();
        } else if (e.key === "Enter") {
          e.preventDefault();
          const item2 = this.filtered[this.selectedIndex];
          if (item2) this.execute(item2);
        }
      },
      execute(item) {
        const action = (item.action || "").trim();
        const routes = this.routes || {};
        const target = (item.target || item?.options?.target || "_self").toLowerCase();
        const openUrl = (url) => {
          if (!url) return;
          if (target === "_blank") {
            window.open(url, "_blank", "noopener,noreferrer");
          } else {
            window.location.href = url;
          }
        };
        if (action.startsWith("route:")) {
          const name = action.replace("route:", "").trim();
          let url = null;
          if (typeof window.route === "function") {
            const params = item.params || item.route_params || {};
            url = route(name, params);
          } else if (routes[name]) {
            url = typeof routes[name] === "function" ? routes[name](item.params || item.route_params || {}) : routes[name];
          } else {
            console.warn(`Could not resolve route "${name}".`);
          }
          openUrl(url);
        } else if (action.startsWith("url:")) {
          openUrl(action.replace("url:", "").trim());
        } else if (/^(https?:\/\/|\/)/i.test(action)) {
          openUrl(action);
        } else if (action.startsWith("dispatch:")) {
          this.$dispatch(action.replace("dispatch:", "").trim());
        } else if (action.startsWith("js:")) {
          try {
            eval(action.replace("js:", ""));
          } catch (e) {
            console.error(e);
          }
        }
        this.open = false;
      }
    };
  }

  // resources/js/index.js
  window.$beartropy = window.$beartropy || {};
  window.$beartropy.dialog = dialog;
  window.$beartropy.openModal = openModal;
  window.$beartropy.closeModal = closeModal;
  window.$beartropy.toast = toast;
  initTheme();
  document.addEventListener("alpine:init", () => {
    Alpine.data("beartropyTable", beartropyTable);
    Alpine.data("beartropyDatetimepicker", beartropyDatetimepicker);
    Alpine.data("beartropyTimepicker", beartropyTimepicker);
    Alpine.data("beartropyTagInput", beartropyTagInput);
    Alpine.data("confirmHost", confirmHost);
    Alpine.data("btDialog", btDialog);
    Alpine.data("beartropySelect", beartropySelect);
    Alpine.data("beartropyFileDropzone", beartropyFileDropzone);
    Alpine.data("beartropyChatInput", beartropyChatInput);
    Alpine.data("beartropyLookup", beartropyLookup);
    Alpine.data("btToggleTheme", btToggleTheme);
    Alpine.data("btCommandPalette", btCommandPalette);
    window.$beartropy.beartropyTable = beartropyTable;
    window.$beartropy.beartropyDatetimepicker = beartropyDatetimepicker;
    window.$beartropy.beartropyTimepicker = beartropyTimepicker;
    window.$beartropy.beartropyTagInput = beartropyTagInput;
    window.$beartropy.confirmHost = confirmHost;
    window.$beartropy.btDialog = btDialog;
    window.$beartropy.beartropySelect = beartropySelect;
    window.$beartropy.beartropyFileDropzone = beartropyFileDropzone;
    window.$beartropy.beartropyChatInput = beartropyChatInput;
    window.$beartropy.beartropyLookup = beartropyLookup;
    window.$beartropy.btToggleTheme = btToggleTheme;
    window.$beartropy.btCommandPalette = btCommandPalette;
  });
})();

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
  function toast(type, title, message = "", duration = 4e3, position = "top-right") {
    const toastObj = {
      id: window.crypto && window.crypto.randomUUID ? window.crypto.randomUUID() : "toast-" + Math.random().toString(36).slice(2) + Date.now(),
      type,
      title,
      message,
      duration,
      position
    };
    if (window.Alpine && Alpine.store("toasts")) {
      Alpine.store("toasts").add(toastObj);
    } else if (window.Livewire && window.Livewire.dispatch) {
      window.Livewire.dispatch("beartropy-add-toast", toastObj);
    } else {
      console.warn("[Beartropy] Toast: no Alpine store ni Livewire.dispatch disponible");
    }
  }
  ["success", "error", "warning", "info"].forEach((type) => {
    toast[type] = (title, message, duration, position) => toast(type, title, message, duration, position);
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
        this.$watch("sorted", () => this.page = 1);
      }
    };
  }

  // resources/js/modules/datetime-picker.js
  var beartropyI18n = {
    es: {
      months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      monthsLong: ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"],
      weekdays: ["Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
      from: "Desde",
      to: "Hasta",
      placeholder: "Seleccionar fecha."
    },
    en: {
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      monthsLong: ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"],
      weekdays: ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
      from: "From",
      to: "To",
      placeholder: "Select date."
    }
  };
  function datetimepicker(entangledValue, rangeMode = false, min = "", max = "", formatDisplay = "{d}/{m}/{Y}", showTime = false) {
    return {
      value: entangledValue,
      open: false,
      range: !!rangeMode,
      min: min || "",
      max: max || "",
      showTime: !!showTime,
      startHour: "00",
      startMinute: "00",
      endHour: "00",
      endMinute: "00",
      formatDisplay: formatDisplay || "{d}/{m}/{Y}",
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
      formatDate(str) {
        if (!str) return "";
        let [y, m, d] = str.split("-");
        return `${d}/${m}/${y}`;
      },
      updateCalendar() {
        let first = new Date(this.year, this.month, 1);
        let last = new Date(this.year, this.month + 1, 0);
        let startDay = (first.getDay() + 6) % 7;
        let days = [];
        for (let i = 0; i < startDay; i++) days.push({ label: "", date: "", inMonth: false });
        for (let d = 1; d <= last.getDate(); d++) {
          let date = `${this.year}-${(this.month + 1).toString().padStart(2, "0")}-${d.toString().padStart(2, "0")}`;
          days.push({ label: d, date, inMonth: true });
        }
        while (days.length % 7) days.push({ label: "", date: "", inMonth: false });
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
  function timepicker(entangledValue, format = "H:i") {
    return {
      value: entangledValue,
      open: false,
      hour: "00",
      minute: "00",
      format,
      displayLabel: "",
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
          this.hour = "00";
          this.minute = "00";
          return;
        }
        let [h, m] = this.value.split(":");
        this.hour = h?.padStart(2, "0") || "00";
        this.minute = m?.padStart(2, "0") || "00";
      },
      updateTime() {
        this.value = `${this.hour}:${this.minute}`;
        this.updateDisplay();
      },
      updateDisplay() {
        if (!this.value) {
          this.displayLabel = "";
          return;
        }
        this.displayLabel = `${this.hour}:${this.minute}`;
      },
      clear() {
        this.value = null;
        this.hour = "00";
        this.minute = "00";
        this.displayLabel = "";
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
              container.scrollTop = selected.offsetTop - container.offsetTop - container.clientHeight / 2 + selected.clientHeight / 2;
            }
          };
          scroll("hoursColumn", this.hour);
          scroll("minutesColumn", this.minute);
        });
      }
    };
  }

  // resources/js/modules/tag-input.js
  function tagInput({ initialTags = [], unique = true, maxTags = null, disabled = false, separator = "," }) {
    let seps = Array.isArray(separator) ? separator : separator.split("");
    let sepRegex = new RegExp(`[${seps.map((s) => s.replace(/[-[\]/{}()*+?.\\^$|]/g, "\\$&")).join("")}]`, "g");
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
          let newTags = paste.split(sepRegex).map((t) => t.trim()).filter(Boolean);
          newTags.forEach((tag) => this._tryAddTag(tag));
          e.preventDefault();
          this.input = "";
        }
      },
      addTagFromPaste(tag) {
        this._tryAddTag(tag);
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
        const target = d.target ?? this.id;
        if (target !== this.id) return;
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
        this.saveState = "saving";
        this.$wire.call(this.autosaveMethod, this.value, this.autosaveKey).then(() => {
          this.saveState = "ok";
        }).catch(() => {
          this.saveState = "error";
        });
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

  // resources/js/index.js
  window.$beartropy = window.$beartropy || {};
  window.$beartropy.dialog = dialog;
  window.$beartropy.openModal = openModal;
  window.$beartropy.closeModal = closeModal;
  window.$beartropy.toast = toast;
  window.beartropyI18n = beartropyI18n;
  document.addEventListener("alpine:init", () => {
    Alpine.data("beartropyTable", beartropyTable);
    Alpine.data("datetimepicker", datetimepicker);
    Alpine.data("timepicker", timepicker);
    Alpine.data("tagInput", tagInput);
    Alpine.data("confirmHost", confirmHost);
    Alpine.data("btDialog", btDialog);
    Alpine.data("beartropySelect", beartropySelect);
    Alpine.data("beartropyFileDropzone", beartropyFileDropzone);
    window.$beartropy.beartropyTable = beartropyTable;
    window.$beartropy.datetimepicker = datetimepicker;
    window.$beartropy.timepicker = timepicker;
    window.$beartropy.tagInput = tagInput;
    window.$beartropy.confirmHost = confirmHost;
    window.$beartropy.btDialog = btDialog;
    window.$beartropy.beartropySelect = beartropySelect;
    window.$beartropy.beartropyFileDropzone = beartropyFileDropzone;
  });
})();

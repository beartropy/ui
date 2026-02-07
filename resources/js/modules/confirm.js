// Confirm Module
export function confirmHost({
    id,
    defaultPlacement = 'top',
    defaultPanelClass = 'mt-32',
}) {
    function toArray(v) { return Array.isArray(v) ? v : (v != null ? [v] : []); }
    function fallbackToken(variant, color) {
        const v = variant || 'soft', c = color || 'gray';
        if (v === 'primary' && c === 'blue') return 'btc-primary-blue';
        if (v === 'primary' && c === 'gray') return 'btc-primary-gray';
        if (v === 'primary' && c === 'green') return 'btc-primary-green';
        if (v === 'primary' && c === 'amber') return 'btc-primary-amber';
        if (v === 'danger') return 'btc-danger-red';
        if (v === 'ghost') return 'btc-ghost';
        if (v === 'outline') return 'btc-outline';
        return 'btc-soft';
    }

    function normalizeButtons(arr) {
        return (Array.isArray(arr) ? arr : []).map(b => ({
            label: b?.label ?? 'OK',
            // semantic (optional)
            variant: b?.variant ?? 'soft',
            color: b?.color ?? 'gray',
            // class token from server (or fallback)
            token: b?.token ?? fallbackToken(b?.variant, b?.color),

            // acciones
            mode: b?.mode ?? (b?.wire ? 'wire' : (b?.emit ? 'emit' : 'close')),
            wire: b?.wire ?? null,
            params: toArray(b?.params),
            emit: b?.emit ?? null,
            payload: b?.payload ?? {},

            dismissAfter: b?.dismissAfter === true,
            close: b?.close === true,
            spinner: b?.spinner === true,
            role: b?.role ?? null,
        }));
    }

    // ====== effects ======
    function dialogClosed(effect) {
        switch (effect) {
            case 'slide-up': return 'opacity-0 translate-y-2';
            case 'slide-down': return 'opacity-0 -translate-y-2';
            case 'slide-left': return 'opacity-0 translate-x-2';
            case 'slide-right': return 'opacity-0 -translate-x-2';
            case 'fade': return 'opacity-0';
            case 'zoom':
            default: return 'opacity-0 scale-95';
        }
    }
    function dialogOpen(effect) {
        switch (effect) {
            case 'slide-up':
            case 'slide-down':
            case 'slide-left':
            case 'slide-right': return 'opacity-100 translate-x-0 translate-y-0';
            case 'fade': return 'opacity-100';
            case 'zoom':
            default: return 'opacity-100 scale-100';
        }
    }


    return {
        // ===== state =====
        id,
        cfg: {},
        buttons: [],
        btnLoading: [],
        open: false,              // wrapper visibility
        anim: 'idle',             // 'idle' | 'enter' | 'open' | 'leave'

        // flags/effects (configurable per payload)
        closeOnBackdrop: true,
        closeOnEscape: true,
        effect: 'zoom',
        duration: 200,           // ms
        easing: 'ease-out',
        overlayOpacity: 0.6,     // 0..1
        overlayBlur: false,

        placement: defaultPlacement,   // 'top' | 'center'
        panelClass: defaultPanelClass, // ej. 'mt-32'

        // ===== utils =====
        _norm(raw) { if (!raw) return {}; if (Array.isArray(raw)) return raw[0] ?? {}; return raw; },
        sizeClasses() {
            switch (this.cfg.size) {
                case 'sm': return 'max-w-sm';
                case 'lg': return 'max-w-2xl';
                case 'xl': return 'max-w-4xl';
                case '2xl': return 'max-w-6xl';
                default: return 'max-w-lg';
            }
        },
        containerClass() {
            return this.placement === 'top'
                ? 'flex justify-center items-start'
                : 'grid place-items-center';
        },
        overlayStyle() {
            const d = Number(this.duration) || 200;
            const e = this.easing || 'ease-out';
            // When open, use overlayOpacity; when entering/leaving, 0
            const o = (this.anim === 'open') ? this.overlayOpacity : 0;
            return `opacity:${o}; transition: opacity ${d}ms ${e};`;
        },
        dialogStyle() {
            const d = Number(this.duration) || 200;
            const e = this.easing || 'ease-out';
            return `transition: transform ${d}ms ${e}, opacity ${d}ms ${e};`;
        },
        dialogMotionClass() {
            // During 'enter' and 'leave' we use the "closed" pose
            return (this.anim === 'open') ? dialogOpen(this.effect) : dialogClosed(this.effect);
        },
        btnClass(btn) { return classesFor(btn.variant, btn.color); },

        // ===== core =====
        handle(ev) {
            const d = this._norm(ev.detail);
            const target = d.target ?? this.id;
            if (target !== this.id) return;

            // -- dynamic flags --
            this.closeOnBackdrop = (typeof d.closeOnBackdrop === 'boolean') ? d.closeOnBackdrop : true;
            this.closeOnEscape = (typeof d.closeOnEscape === 'boolean') ? d.closeOnEscape : true;

            // -- effects --
            this.effect = d.effect || 'zoom';
            this.duration = (typeof d.duration === 'number') ? d.duration : 200;
            this.easing = d.easing || 'ease-out';
            this.overlayOpacity = (typeof d.overlayOpacity === 'number') ? d.overlayOpacity : 0.6;
            this.overlayBlur = d.overlayBlur === true;

            // -- placement / offset (defaults already set in factory) --
            this.placement = (d.placement ?? this.placement);
            this.panelClass = (d.panelClass ?? this.panelClass);

            // -- autofocus (optional) --
            this.autofocus = d.autofocus || this.autofocus; // 'dialog' | 'cancel' | 'confirm' | 'none'

            // -- content / buttons (with token from PHP) --
            this.cfg = d;
            const btns = normalizeButtons(d.buttons);
            this.buttons = btns.length ? btns : normalizeButtons([{ label: 'OK', mode: 'close' }]);
            this.btnLoading = this.buttons.map(() => false);

            // â¯ï¸ pipeline de animaciÃ³n
            this._openWithAnimation();
        },

        _openWithAnimation() {
            if (this.open && this.anim === 'open') return;
            this.open = true;       // show the wrapper (overlay+dialog)
            this.anim = 'enter';    // visible "closed" positiÃ³n "cerrada" visible

            // salto de frame para que el browser pinte 'enter' y luego transicione a 'open'
            this.$nextTick(() => requestAnimationFrame(() => {
                this.anim = 'open';   // -> transiciona a la pose "abierta"
                // focus al primer control si existe
                this.$nextTick(() => { try { this.$refs.first && this.$refs.first.focus(); } catch (_) { } });
            }));

            document.documentElement.classList.add('overflow-hidden');
        },

        close() {
            if (!this.open) return;
            this.anim = 'leave'; // vuelve a pose "cerrada" y deja que la transiciÃ³n corra
            const ms = Number(this.duration) || 200;
            setTimeout(() => {
                this.open = false;    // ahora sÃ­ ocultamos el wrapper
                this.anim = 'idle';
                document.documentElement.classList.remove('overflow-hidden');
            }, ms);
        },

        onBackdrop(e) {
            if (!this.closeOnBackdrop) return;
            if (e.target === e.currentTarget) this.close();
        },

        onKeydown(e) {
            if (!this.closeOnEscape) return;
            if (e.key === 'Escape') { e.preventDefault(); this.close(); }
        },

        async run(btn, i) {
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
                else Livewire.dispatch(btn.emit, btn.payload || {});
                if (dismiss) this.close();
                return;
            }

            // close
            this.close();
        },
    };
};

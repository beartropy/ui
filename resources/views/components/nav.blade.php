@props([
    'items' => [],
    'sidebarBind' => '',
    'highlightMode' => 'standard',
    'highlightParentClass' => '',
    'highlightChildClass' => '',
    'itemClass' => '',
    'childItemClass' => '',
    'categoryClass' => '',
    'iconClass' => '',
    'childBorderClass' => '',
    'customBadges' => [],

    'hideCategories' => false,          // Oculta los títulos de las categorías
    'singleOpenExpanded' => false,

    // Collapse button (opcional)
    'collapseButtonAsItem' => true,
    'collapseButtonLabelCollapse' => 'Collapse',
    'collapseButtonLabelExpand'  => 'Expand',
    'collapseButtonIconCollapse' => 'arrows-pointing-in',
    'collapseButtonIconExpand'   => 'arrows-pointing-out',
    'rememberCollapse' => null,                 // null = auto (usa collapseButtonAsItem)
    'rememberCollapseKey' => 'beartropy:sidebar:collapsed',

    // Header del menú flotante
    'hoverMenuShowHeader'   => true,
    'hoverMenuHeaderClass'  => 'sticky top-0 z-10 px-3 py-2 border-b border-gray-200/80 dark:border-gray-700/70 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm',
    'hoverMenuHeaderTextClass' => 'font-bold text-sm text-gray-700 dark:text-gray-400',

])

@php
    // Clases hover desde preset según modo
    $itemHover  = $highlightMode === 'text' ? $hoverTextClass      : '';
    $childHover = $highlightMode === 'text' ? $hoverTextChildClass : '';

    // Soporte para badges por slot y/o Alpine
    $customBadges = ${'custom-badges'} ?? [];

    // Botón de colapso reusa las clases del item principal + hover
    $collapseBtnClass = trim(($itemClass ?? '') . ' ' . $itemHover);

    // Manejo de binding (acepta "!sidebarOpen")
    $bindExpr   = trim($sidebarBind ?? '');
    $bindVar    = ltrim($bindExpr, '! ');
    $isNegated  = str_starts_with($bindExpr, '!');

@endphp

<nav
    x-data="{
        open: {},
        sidebarIsCollapsed: false,
        singleOpenExpanded: {{ $singleOpenExpanded ? 'true' : 'false' }},
        // ---- config de recordatorio ----
        remember: {{ ($rememberCollapse === null ? ($collapseButtonAsItem ? 'true' : 'false') : ($rememberCollapse ? 'true' : 'false')) }},
        rememberKey: @js($rememberCollapseKey),

        // ---- hover submenu ----
        hoverId: null,
        hoverTimer: null,
        submenuPos: { top: 0, left: 0, minWidth: 220 },
        openHover(id, el) {
            if (!this.sidebarIsCollapsed) return;
            clearTimeout(this.hoverTimer);
            const r = el.getBoundingClientRect();
            this.submenuPos.top  = Math.max(8, Math.min(r.top,  window.innerHeight - 8));
            this.submenuPos.left = Math.min(r.right + 8,        window.innerWidth  - 8);
            this.hoverId = id;
        },
        closeHoverSoon() { clearTimeout(this.hoverTimer); this.hoverTimer = setTimeout(() => this.hoverId = null, 120); },
        keepHoverOpen()  { clearTimeout(this.hoverTimer); },

        // ---- ACTIVE por Alpine ----
        activePath: window.location.pathname + window.location.search,
_norm(u) {
  try {
    const url = new URL(u, window.location.origin);
    let path = url.pathname.replace(/\/+$/, '');
    if (path === '') path = '/';
    const query = url.search; // conserva query si querés distinguir filtros
    return path + query;
  } catch (e) {
    let s = (u || '').replace(/\/+$/, '');
    if (s === '') s = '/';
    return s;
  }
},

isActiveHref(href, mode = 'exact') {
  if (!href || href === '#' || href.startsWith('javascript')) return false;
  const here  = this._norm(this.activePath);
  const there = this._norm(href);
  if (!there) return false; // evita que '' haga match con todo
  return mode === 'startsWith' ? here.startsWith(there) : here === there;
},
hrefFromEl(el) {
  // 1) data-href renderizado por Blade
  if (el?.dataset?.href) return el.dataset.href;

  // 2) Ziggy por routeName/params
  const name = el?.dataset?.routeName;
  if (name && typeof window.route === 'function') {
    let params = {};
    try { params = JSON.parse(el.dataset.routeParams || '{}'); } catch (e) {}
    try { return route(name, params); } catch (e) {}
  }

  // 3) atributo href tal cual
  const attr = el?.getAttribute?.('href');
  if (attr) return attr;

  // 4) href resuelto por el navegador (absoluto)
  if (el?.href) return el.href;

  return '#';
},
isActiveEl(el, startsWith = false) {
  const href = this.hrefFromEl(el);
  return this.isActiveHref(href, startsWith ? 'startsWith' : 'exact');
},
_path(u) {
  // ignorar anchors y javascript
  if (!u || u === '#' || String(u).startsWith('javascript')) return '';
  try {
    let p = new URL(u, window.location.origin).pathname.replace(/\/+$/, '');
    if (p === '') p = '/';
    return p;
  } catch (e) {
    let s = String(u || '').replace(/\/+$/, '');
    if (s === '') s = '/';
    return s;
  }
},

_pathStartsWith(herePath, prefix) {
  if (prefix === '/') return herePath === '/';
  return (herePath === prefix) || herePath.startsWith(prefix + '/');
},
parentMatches(el) {
  const here = this._path(this.activePath);

  // Si es padre con hijos: SOLO chequeamos los prefijos de hijos
  if (el?.dataset?.hasChildren === '1') {
    if (el?.dataset?.childPrefixes) {
      try {
        const arr = JSON.parse(el.dataset.childPrefixes) || [];
        for (const raw of arr) {
          const p = this._path(raw);
          if (p && this._pathStartsWith(here, p)) return true;
        }
      } catch (e) {}
    }
    // Opcional: si este padre tiene realmente un link propio (data-href verdadero),
    // también lo dejamos matchear. Pero evitamos '#' o 'javascript'.
    const dh = el?.dataset?.href;
    if (dh && dh !== '#' && !String(dh).startsWith('javascript')) {
      const p = this._path(dh);
      if (p && this._pathStartsWith(here, p)) return true;
    }
    return false;
  }

  // Si NO tiene hijos: activo exacto por su propio href (solo path)
  const herePath = here;
  const p = this._path(this.hrefFromEl(el));
  return herePath === p;
},

isActiveParent(el) {
  if (el?.dataset?.hasChildren === '1') return this.parentMatches(el);
  // sin hijos: activo exacto (path + query no; usamos solo path)
  const here = this._path(this.activePath);
  const p = this._path(this.hrefFromEl(el));
  return here === p;
},


getActiveParentIds() {
  const set = new Set();
  document.querySelectorAll('a[data-nav-id]').forEach((el) => {
    if (el.dataset.hasChildren === '1' && this.parentMatches(el)) {
      set.add(el.dataset.navId);
    }
  });
  return Array.from(set);
},


updateActiveState() {
  this.activePath = window.location.pathname + window.location.search;

  const activeParents = this.getActiveParentIds();

  if (activeParents.length === 0) {
    // no tocar 'open' aún; esperamos al próximo frame (dom ya montado)
    requestAnimationFrame(() => {
      const retry = this.getActiveParentIds();
      if (retry.length > 0) this._reconcileOpen(retry);
    });
    return;
  }

  this._reconcileOpen(activeParents);
},


_reconcileOpen(activeParents) {
  const shouldOpen = new Set(activeParents);

  if (this.singleOpenExpanded) {
    // Cerrar solo lo que NO debe estar abierto
    Object.keys(this.open).forEach(id => {
      if (this.open[id] && !shouldOpen.has(id)) this.open[id] = false;
    });
    // Abrir solo lo necesario
    shouldOpen.forEach(id => { if (!this.open[id]) this.open[id] = true; });
  } else {
    // No colapsivo: solo garantizar abiertas las activas actuales
    shouldOpen.forEach(id => { if (!this.open[id]) this.open[id] = true; });
  }
  this.$nextTick(() => {});
},


        openActiveBranches() {
            // (compat) mantener llamada desde init; ahora delega en updateActiveState
            this.updateActiveState();
        },

_installLocationListeners() {
  let t = null;
  const notify = () => {
    clearTimeout(t);
    t = setTimeout(() => this.updateActiveState(), 30); // pequeño debounce
  };

  window.addEventListener('popstate', notify);

  const _push = history.pushState;
  const _replace = history.replaceState;
  history.pushState = function(...args){
    const r = _push.apply(this, args);
    window.dispatchEvent(new Event('locationchange'));
    return r;
  };
  history.replaceState = function(...args){
    const r = _replace.apply(this, args);
    window.dispatchEvent(new Event('locationchange'));
    return r;
  };
  window.addEventListener('locationchange', notify);

  document.addEventListener('livewire:navigated', notify);
},



        // ---- helpers persistencia ----
        loadRemembered() {
            if (!this.remember) return null;
            const v = localStorage.getItem(this.rememberKey);
            if (v === null) return null;
            return v === '1';
        },
        saveRemembered(v) {
            if (!this.remember) return;
            localStorage.setItem(this.rememberKey, v ? '1' : '0');
        },

        init() {
            // 1) Estado inicial (autoabrir ramas activas en expandido)
            @foreach($items as $category)
                @foreach($category['items'] as $item)
                    @php $id = $navId($item); @endphp
                    @if($isItemActive($item) && !empty($item['children']))
                        this.open['{{ $id }}'] = true;
                    @endif
                @endforeach
            @endforeach

            // 2) Bind externo (con/sin negación) + recordatorio
            @if(!empty($bindVar))
                if (typeof {{ $bindVar }} !== 'undefined') {
                    // a) Si hay valor recordado, úsalo para inicializar el bind externo y el estado local
                    const remembered = this.loadRemembered();
                    if (remembered !== null) {
                        // Si sidebarIsCollapsed = remembered,
                        // entonces bindVar debe ser (negado o no) según $isNegated
                        {{ $bindVar }} = {{ $isNegated ? '!' : '' }}remembered;
                        this.sidebarIsCollapsed = remembered;
                    } else {
                        // Sin recordado: derivamos del bind
                        this.sidebarIsCollapsed = {{ $isNegated ? '!' : '' }}{{ $bindVar }} ? true : false;
                    }

                    // b) Watch: cuando cambia el bind, sincronizamos local + localStorage
                    $watch('{{ $bindVar }}', value => {
                        const collapsed = {{ $isNegated ? '!' : '' }}value ? true : false;
                        this.sidebarIsCollapsed = collapsed;
                        this.saveRemembered(collapsed);
                    });
                } else {
                    // No existe el bind en runtime: caemos a modo interno con recordatorio
                    const remembered = this.loadRemembered();
                    this.sidebarIsCollapsed = remembered !== null ? remembered : false;
                    $watch('sidebarIsCollapsed', v => this.saveRemembered(v));
                }
            @else
                // 3) Sin bind externo: persistimos el estado interno
                const remembered = this.loadRemembered();
                this.sidebarIsCollapsed = remembered !== null ? remembered : false;
                $watch('sidebarIsCollapsed', v => this.saveRemembered(v));
            @endif

            // ---- ACTIVAR listeners y abrir ramas segun URL actual ----
            this._installLocationListeners();
            this.openActiveBranches();
        },

        toggle(id) {
            // En colapsado no hay inline (se maneja por hover), no hacemos nada
            if (this.sidebarIsCollapsed) return;

            if (this.singleOpenExpanded) {
                const willOpen = !this.open[id];
                // cerrar todos
                Object.keys(this.open).forEach(k => this.open[k] = false);
                // abrir solo el actual si corresponde
                this.open[id] = willOpen;
            } else {
                this.open[id] = !this.open[id];
            }
        },
        isOpen(id) { return !!this.open[id]; }
    }"
    class="flex flex-col h-full overflow-hidden overflow-x-hidden beartropy-thin-scrollbar"
    @click.stop
    @mousedown.stop
>

    {{-- ÁREA SCROLLABLE: categorías + items --}}
    <div class="flex-1 overflow-y-auto pl-1.5 py-4 space-y-6 overflow-x-hidden beartropy-thin-scrollbar">
        @foreach($items as $category)
            <div class="overflow-x-hidden">
                @if(!$hideCategories)
                    <div class="{{ $categoryClass }} transition-all duration-500"
                        x-show="!sidebarIsCollapsed"
                        :class="{ 'hidden': sidebarIsCollapsed }">
                        {{ $category['category'] }}
                    </div>
                @endif
                <div class="space-y-1">
                    @foreach($category['items'] as $item)
                        @if(!empty($item['divider']))
                            <div class="my-2 border-t border-gray-200 dark:border-gray-600"></div>
                            @continue
                        @endif

                        @php
                            $isActive          = $isItemActive($item);
                            $hasChildren       = !empty($item['children']);
                            $iconHtml          = $renderIcon($item['icon'] ?? '', $iconClass);
                            $badge             = $item['badge'] ?? null;
                            $disabled          = $item['disabled'] ?? false;
                            $external          = $item['external'] ?? false;
                            $itemId            = $navId($item);
                            $itemClassOverride = $item['class'] ?? null;
                            $itemLabelClass    = $item['label_class'] ?? '';
                            $badgeVar          = $customBadges[$item['id'] ?? ''] ?? null;
                            $slotBadgeVar      = $__data['badge-' . ($item['id'] ?? '')] ?? null;

                            if ($itemClassOverride) {
                                $finalItemClass = trim($itemClassOverride);
                            } else {
                                // NUNCA marcamos active desde PHP (lo maneja Alpine)
                                $finalItemClass = trim($itemClass . ' ' . $itemHover);
                            }

                            // Nueva lógica para obtener el href
                            $href = '#';
                            if (!$hasChildren) {
                                if (!empty($item['routeName'])) {
                                    try {
                                        $href = route($item['routeName'], $item['routeParams'] ?? []);
                                    } catch (\Throwable $e) {
                                        $href = '#';
                                    }
                                } elseif (!empty($item['route'])) {
                                    $href = $item['route'];
                                }
                            }
                        @endphp

                        <div>
                            @php
                                $routeName   = $item['routeName']   ?? null;
                                $routeParams = $item['routeParams'] ?? [];

                                $parentPrefixes = [];
                                if ($hasChildren) {
                                    foreach (($item['children'] ?? []) as $c) {
                                        $chref = '#';
                                        if (!empty($c['routeName'])) {
                                            try { $chref = route($c['routeName'], $c['routeParams'] ?? []); } catch (\Throwable $e) { $chref = '#'; }
                                        } elseif (!empty($c['route'])) {
                                            $chref = $c['route'];
                                        }
                                        try {
                                            $p = parse_url($chref, PHP_URL_PATH) ?? '/';
                                            if ($p === '') $p = '/';
                                            $parentPrefixes[] = $p;
                                        } catch (\Throwable $e) {}
                                    }

                                    // también considerar el path del propio padre si tiene routeName/route
                                    $parentSelfHref = null;
                                    if (!empty($item['routeName'])) {
                                        try { $parentSelfHref = route($item['routeName'], $item['routeParams'] ?? []); } catch (\Throwable $e) {}
                                    } elseif (!empty($item['route'])) {
                                        $parentSelfHref = $item['route'];
                                    }
                                    if ($parentSelfHref) {
                                        try {
                                            $pp = parse_url($parentSelfHref, PHP_URL_PATH) ?? '/';
                                            if ($pp === '') $pp = '/';
                                            $parentPrefixes[] = $pp;
                                        } catch (\Throwable $e) {}
                                    }

                                    $parentPrefixes = array_values(array_unique($parentPrefixes));
                                }
                            @endphp

                            <a
                                data-nav-id="{{ $itemId }}"
                                data-has-children="{{ $hasChildren ? '1' : '0' }}"
                                data-href="{{ !$hasChildren ? $href : '' }}"
                                @if($routeName)
                                    data-route-name="{{ $routeName }}"
                                    data-route-params='@json($routeParams)'
                                @endif
                                @if($hasChildren)
                                data-child-prefixes='@json($parentPrefixes)'
                                @endif
                                x-effect="if (!sidebarIsCollapsed && isActiveParent($el)) { open['{{ $itemId }}'] = true }"
                                href="{{ $hasChildren ? '#' : $href }}" {{ $withnavigate && !$hasChildren ? "wire:navigate" : "" }}

                                @if($hasChildren)
                                    @click.prevent="if (!sidebarIsCollapsed) toggle('{{ $itemId }}')"
                                @endif
                                @mouseenter="if (sidebarIsCollapsed && {{ $hasChildren ? 'true' : 'false' }}) openHover('{{ $itemId }}', $el)"
                                @mouseleave="if (sidebarIsCollapsed && {{ $hasChildren ? 'true' : 'false' }}) closeHoverSoon()"

                                class="{{ $finalItemClass }}{{ $disabled ? ' opacity-60 pointer-events-none' : '' }}"
                                :class="[
                                    isActiveParent($el) ? ' {{ $highlightParentClass }} ' : '',
                                    (sidebarIsCollapsed ? 'justify-center gap-0 px-2' : 'justify-start gap-2 px-2.5')
                                ]"
                                title="{{ $item['tooltip'] ?? '' }}"
                                @if(!empty($item['tooltip'])) x-tooltip="'{{ $item['tooltip'] }}'" @endif
                                @if($external) target="_blank" rel="noopener" @endif
                                style="cursor: pointer;"
                            >


                                {!! $iconHtml !!}

                                <span class="truncate {{ $itemLabelClass }} transition-all duration-500"
                                      x-show="!sidebarIsCollapsed"
                                      :class="{ 'hidden': sidebarIsCollapsed }">
                                    {{ $item['label'] }}
                                </span>

                                {{-- BADGE SLOT / ALPINE / FALLBACK --}}
                                @if (isset($slotBadgeVar))
                                    <span x-show="!sidebarIsCollapsed" :class="{ 'hidden': sidebarIsCollapsed }">
                                        {!! $slotBadgeVar !!}
                                    </span>
                                @elseif($badgeVar)
                                    <span
                                        class="ml-2 text-xs bg-green-600 text-white px-2 py-0.5 rounded-full transition-all duration-500"
                                        x-show="!sidebarIsCollapsed"
                                        :class="{ 'hidden': sidebarIsCollapsed }"
                                        x-text="{{ $badgeVar }}"
                                    >{{ $badge }}</span>
                                @elseif($badge)
                                    <span
                                        class="ml-2 text-xs bg-green-600 text-white px-2 py-0.5 rounded-full transition-all duration-500"
                                        x-show="!sidebarIsCollapsed"
                                        :class="{ 'hidden': sidebarIsCollapsed }"
                                    >{{ $badge }}</span>
                                @endif

                                @if($hasChildren)
                                    <i class="fas fa-caret-down ml-auto text-xs opacity-60 transition-transform duration-200 ease-out"
                                       x-show="!sidebarIsCollapsed"
                                       :class="{ 'rotate-180': isOpen('{{ $itemId }}'), 'hidden': sidebarIsCollapsed }"></i>
                                @endif
                            </a>

                            {{-- SUBMENU INLINE (solo expandido, comportamiento original) --}}
                            @if($hasChildren)
                                <div
                                    class="space-y-1 {{ $childBorderClass }}"
                                    :class="{
                                        'ml-4 pl-1 mt-1': !sidebarIsCollapsed,
                                        'ml-1 pl-1 mt-1 border-l-0': sidebarIsCollapsed
                                    }"
                                    x-show="!sidebarIsCollapsed && isOpen('{{ $itemId }}')"
                                    x-transition
                                    x-collapse
                                    x-cloak
                                >
                                    @foreach($item['children'] as $child)
                                        @if(!empty($child['divider']))
                                            <div class="my-2 border-t border-gray-200 dark:border-gray-600"></div>
                                            @continue
                                        @endif

                                        @php
                                            $childIsActive      = $isItemActive($child);
                                            $childIcon          = $renderIcon($child['icon'] ?? '', $iconClass);
                                            $childBadge         = $child['badge'] ?? null;
                                            $childDisabled      = $child['disabled'] ?? false;
                                            $childExternal      = $child['external'] ?? false;
                                            $childClassOverride = $child['class'] ?? null;
                                            $childLabelClass    = $child['label_class'] ?? '';
                                            $childBadgeVar      = $customBadges[$child['id'] ?? ''] ?? null;
                                            $childSlotBadgeVar  = $__data['badge-' . ($child['id'] ?? '')] ?? null;

                        if ($childClassOverride) {
                            $finalChildClass = trim($childClassOverride);
                        } else {
                            // NUNCA marcamos active desde PHP (lo maneja Alpine)
                            $finalChildClass = trim($childItemClass . ' ' . $childHover);
                        }

                        $childHref = '#';
                        if (!empty($child['routeName'])) {
                            try {
                                $childHref = route($child['routeName'], $child['routeParams'] ?? []);
                            } catch (\Throwable $e) {
                                $childHref = '#';
                            }
                        } elseif (!empty($child['route'])) {
                            $childHref = $child['route'];
                        }
                                        @endphp

                                        @php
                                            $cName   = $child['routeName']   ?? null;
                                            $cParams = $child['routeParams'] ?? [];
                                        @endphp

                                            <a
                                            data-parent-id="{{ $itemId }}"
                                            data-href="{{ $childHref }}"
                                            @if($cName)
                                                data-route-name="{{ $cName }}"
                                                data-route-params='@json($cParams)'
                                            @endif

                                            href="{{ $childHref }}" {{ $withnavigate ? "wire:navigate" : "" }}
                                            class="{{ $finalChildClass }}{{ $childDisabled ? ' opacity-60 pointer-events-none' : '' }}"
                                            :class="[
                                                // highlight: hijos con startsWith para que /users/123 active /users
                                                isActiveEl($el, true) ? ' {{ $highlightChildClass }} ' : '',
                                                // layout
                                                (sidebarIsCollapsed ? 'justify-center' : 'justify-start')
                                            ]"
                                            title="{{ $child['tooltip'] ?? '' }}"
                                            @if(!empty($child['tooltip'])) x-tooltip="'{{ $child['tooltip'] }}'" @endif
                                            @if($childExternal) target="_blank" rel="noopener" @endif
                                            >

                                            {!! $childIcon !!}
                                            <span class="truncate {{ $childLabelClass }} transition-all duration-500"
                                                  x-show="!sidebarIsCollapsed"
                                                  :class="{ 'hidden': sidebarIsCollapsed }">
                                                {{ $child['label'] }}
                                            </span>

                                            {{-- CHILD BADGE SLOT / ALPINE / FALLBACK --}}
                                            @if (isset($childSlotBadgeVar))
                                                <span x-show="!sidebarIsCollapsed" :class="{ 'hidden': sidebarIsCollapsed }">
                                                    {!! $childSlotBadgeVar !!}
                                                </span>
                                            @elseif($childBadgeVar)
                                                <span
                                                    class="ml-2 text-xs bg-green-600 text-white px-2 py-0.5 rounded-full transition-all duration-500"
                                                    x-show="!sidebarIsCollapsed"
                                                    :class="{ 'hidden': sidebarIsCollapsed }"
                                                    x-text="{{ $childBadgeVar }}"
                                                >{{ $childBadge }}</span>
                                            @elseif($childBadge)
                                                <span
                                                    class="ml-2 text-xs bg-green-600 text-white px-2 py-0.5 rounded-full transition-all duration-500"
                                                    x-show="!sidebarIsCollapsed"
                                                    :class="{ 'hidden': sidebarIsCollapsed }"
                                                >{{ $childBadge }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- SUBMENU FLOTANTE (solo colapsado, hover) --}}
                            @if($hasChildren)
                                <template x-teleport="body">
                                    <template x-if="sidebarIsCollapsed && hoverId === '{{ $itemId }}'">
                                        <div
                                            @mouseenter="keepHoverOpen()"
                                            @mouseleave="closeHoverSoon()"
                                            x-transition
                                            class="z-[1000] fixed shadow-xl rounded-lg border border-gray-200 dark:border-gray-700
                                                bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm
                                                max-h-[70vh] overflow-auto"
                                            :style="`top:${submenuPos.top}px; left:${submenuPos.left}px; min-width:${submenuPos.minWidth}px`"
                                            role="menu"
                                            aria-label="Submenú {{ $item['label'] ?? '' }}"
                                        >
                                        {{-- HEADER DEL SUBMENÚ FLOTANTE --}}
                                        @if($hoverMenuShowHeader)
                                            <div class="{{ $hoverMenuHeaderClass }}">
                                                <div class="flex items-center gap-2">
                                                    <span class="{{ $hoverMenuHeaderTextClass }}">
                                                        {{ $item['label'] ?? '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- LISTA DE HIJOS --}}
                                        <div class="py-1">
                                            @foreach($item['children'] as $child)
                                                @if(!empty($child['divider']))
                                                    <div class="my-2 border-t border-gray-200 dark:border-gray-600"></div>
                                                    @continue
                                                @endif

                                                @php
                                                    $childIsActive   = $isItemActive($child);
                                                    $childIcon       = $renderIcon($child['icon'] ?? '', $iconClass);
                                                    $childClassBase  = $childItemClass . ' ' . $childHover;
                                                    $finalChildClass = trim($childClassBase);

                                                    $childBadge        = $child['badge'] ?? null;
                                                    $childBadgeVar     = $customBadges[$child['id'] ?? ''] ?? null;
                                                    $childSlotBadgeVar = $__data['badge-' . ($child['id'] ?? '')] ?? null;
                                                    $childHref = '#';
                                                    if (!empty($child['routeName'])) {
                                                        try {
                                                            $childHref = route($child['routeName'], $child['routeParams'] ?? []);
                                                        } catch (\Throwable $e) {
                                                            $childHref = '#';
                                                        }
                                                    } elseif (!empty($child['route'])) {
                                                        $childHref = $child['route'];
                                                    }
                                                @endphp

                                                @php
                                                    $cName   = $child['routeName']   ?? null;
                                                    $cParams = $child['routeParams'] ?? [];
                                                @endphp

                                                <a
                                                    data-parent-id="{{ $itemId }}"
                                                    data-href="{{ $childHref }}"
                                                    @if($cName)
                                                        data-route-name="{{ $cName }}"
                                                        data-route-params='@json($cParams)'
                                                    @endif

                                                    href="{{ $childHref }}" {{ $withnavigate ? "wire:navigate" : "" }}
                                                    class="{{ $finalChildClass }} flex items-center gap-2 px-3 py-2 whitespace-nowrap"
                                                    :class="isActiveEl($el, true) ? ' {{ $highlightChildClass }} ' : ''"
                                                    title="{{ $child['tooltip'] ?? '' }}"
                                                    role="menuitem"
                                                    @if(!empty($child['tooltip'])) x-tooltip="'{{ $child['tooltip'] }}'" @endif
                                                    @if(!empty($child['external'])) target="_blank" rel="noopener" @endif
                                                >

                                                    {!! $childIcon !!}
                                                    <span class="truncate {{ $child['label_class'] ?? '' }}">{{ $child['label'] }}</span>

                                                    @if (isset($childSlotBadgeVar))
                                                        <span class="ml-2">{!! $childSlotBadgeVar !!}</span>
                                                    @elseif($childBadgeVar)
                                                        <span class="ml-2 text-xs bg-green-600 text-white px-2 py-0.5 rounded-full" x-text="{{ $childBadgeVar }}"></span>
                                                    @elseif($childBadge)
                                                        <span class="ml-2 text-xs bg-green-600 text-white px-2 py-0.5 rounded-full">{{ $childBadge }}</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </template>
                                </template>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- FOOTER FIJO: botón de colapso --}}
    @if ($collapseButtonAsItem)
        <div class="pl-1.5 py-2">
            <button
                type="button"
                class="{{ $collapseBtnClass }} w-full"
                @click.stop="
                    @if(!empty($bindVar))
                        if (typeof {{ $bindVar }} !== 'undefined') {
                            {{ $bindVar }} = !{{ $bindVar }};
                        } else {
                            sidebarIsCollapsed = !sidebarIsCollapsed;
                        }
                    @else
                        sidebarIsCollapsed = !sidebarIsCollapsed;
                    @endif
                "
                :class="sidebarIsCollapsed ? 'justify-center gap-0 px-2' : 'justify-start gap-2 px-2.5'"
                :title="sidebarIsCollapsed ? '{{ $collapseButtonLabelExpand }}' : '{{ $collapseButtonLabelCollapse }}'">

                <span x-show="!sidebarIsCollapsed">
                    {!! $renderIcon($collapseButtonIconCollapse, $iconClass) !!}
                </span>
                <span x-show="sidebarIsCollapsed">
                    {!! $renderIcon($collapseButtonIconExpand, $iconClass) !!}
                </span>

                <span class="truncate transition-all duration-200"
                      x-show="!sidebarIsCollapsed"
                      :class="{ 'hidden': sidebarIsCollapsed }">
                    <span x-text="sidebarIsCollapsed ? '{{ $collapseButtonLabelExpand }}' : '{{ $collapseButtonLabelCollapse }}'"></span>
                </span>
            </button>
        </div>
    @endif
</nav>

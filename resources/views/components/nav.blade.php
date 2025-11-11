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
                                if ($isActive) {
                                    $cleanClass     = preg_replace('/text-gray-\d{3}|dark:text-gray-\d{3}/', '', $itemClass);
                                    $finalItemClass = trim($cleanClass . ' ' . $highlightParentClass);
                                } else {
                                    $finalItemClass = trim($itemClass . ' ' . $itemHover);
                                }
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
                            <a
                                href="{{ $hasChildren ? '#' : $href }}" {{ $withnavigate && !$hasChildren ? "wire:navigate" : "" }}

                                {{-- CLICK: solo togglea si NO está colapsado; si está colapsado, no hace nada --}}
                                @if($hasChildren)
                                    @click.prevent="if (!sidebarIsCollapsed) toggle('{{ $itemId }}')"
                                @endif

                                {{-- HOVER: cuando está colapsado, abre dropdown flotante --}}
                                @mouseenter="if (sidebarIsCollapsed && {{ $hasChildren ? 'true' : 'false' }}) openHover('{{ $itemId }}', $el)"
                                @mouseleave="if (sidebarIsCollapsed && {{ $hasChildren ? 'true' : 'false' }}) closeHoverSoon()"

                                class="{{ $finalItemClass }}{{ $disabled ? ' opacity-60 pointer-events-none' : '' }}"
                                title="{{ $item['tooltip'] ?? '' }}"
                                @if(!empty($item['tooltip'])) x-tooltip="'{{ $item['tooltip'] }}'" @endif
                                @if($external) target="_blank" rel="noopener" @endif
                                style="cursor: pointer;"
                                :class="sidebarIsCollapsed ? 'justify-center gap-0 px-2' : 'justify-start gap-2 px-2.5'"
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
                                    style="display: none;"
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
                                                if ($childIsActive) {
                                                    $cleanChildClass = preg_replace('/text-gray-\d{3}|dark:text-gray-\d{3}/', '', $childItemClass);
                                                    $finalChildClass = trim($cleanChildClass . ' ' . $highlightChildClass);
                                                } else {
                                                    $finalChildClass = trim($childItemClass . ' ' . $childHover);
                                                }
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

                                        <a
                                            href="{{ $childHref }}" {{ $withnavigate ? "wire:navigate" : "" }}
                                            class="{{ $finalChildClass }}{{ $childDisabled ? ' opacity-60 pointer-events-none' : '' }}"
                                            title="{{ $child['tooltip'] ?? '' }}"
                                            :class="{ 'justify-center': sidebarIsCollapsed, 'justify-start': !sidebarIsCollapsed }"
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
                                                    $finalChildClass = $childIsActive
                                                        ? trim(preg_replace('/text-gray-\d{3}|dark:text-gray-\d{3}/', '', $childItemClass) . ' ' . $highlightChildClass)
                                                        : trim($childClassBase);

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

                                                <a
                                                    href="{{ $childHref }}" {{ $withnavigate ? "wire:navigate" : "" }}
                                                    class="{{ $finalChildClass }} flex items-center gap-2 px-3 py-2 whitespace-nowrap"
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

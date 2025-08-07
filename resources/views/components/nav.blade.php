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
    'customBadges' => [], // badge variables de Alpine.js
])

@php
    $itemHover = $highlightMode === 'text' ? $hoverTextClass : '';
    $childHover = $highlightMode === 'text' ? $hoverTextChildClass : '';
    $customBadges = ${'custom-badges'} ?? [];
@endphp

<nav
    x-data="{
        open: {},
        sidebarIsCollapsed: false,
        init() {
            @foreach($items as $category)
                @foreach($category['items'] as $item)
                    @php $id = $navId($item); @endphp
                    @if($isItemActive($item) && !empty($item['children']))
                        this.open['{{ $id }}'] = true;
                    @endif
                @endforeach
            @endforeach

            if (typeof {{$sidebarBind}} !== 'undefined') {
                this.sidebarIsCollapsed = {{$sidebarBind}};
                $watch('{{$sidebarBind}}', value => {
                    this.sidebarIsCollapsed = value;
                });
            }
        },
        toggle(id) { this.open[id] = !this.open[id]; },
        isOpen(id) { return !!this.open[id]; }
    }"
    class="flex-1 px-2 py-4 overflow-y-auto space-y-6"
>
    @foreach($items as $category)
        <div>
            <div class="{{ $categoryClass }} transition-all duration-500" x-show="!sidebarIsCollapsed" :class="{ 'hidden': sidebarIsCollapsed }">
                {{ $category['category'] }}
            </div>
            <div class="space-y-1">
                @foreach($category['items'] as $item)
                    @if(!empty($item['divider']))
                        <div class="my-2 border-t border-gray-200 dark:border-gray-600"></div>
                        @continue
                    @endif
                    @php
                        $isActive = $isItemActive($item);
                        $hasChildren = !empty($item['children']);
                        $iconHtml = $renderIcon($item['icon'] ?? '', $iconClass);
                        $badge = $item['badge'] ?? null;
                        $disabled = $item['disabled'] ?? false;
                        $external = $item['external'] ?? false;
                        $itemId = $navId($item);
                        $itemClassOverride = $item['class'] ?? null;
                        $itemLabelClass = $item['label_class'] ?? '';
                        $badgeVar = $customBadges[$item['id'] ?? ''] ?? null;
                        $slotBadgeVar = $__data['badge-' . ($item['id'] ?? '')] ?? null;

                        if ($itemClassOverride) {
                            $finalItemClass = trim($itemClassOverride);
                        } else {
                            if ($isActive) {
                                $cleanClass = preg_replace('/text-gray-\d{3}|dark:text-gray-\d{3}/', '', $itemClass);
                                $finalItemClass = trim($cleanClass . ' ' . $highlightParentClass);
                            } else {
                                $finalItemClass = trim($itemClass . ' ' . $itemHover);
                            }
                        }
                    @endphp
                    <div>
                        <a
                            href="{{ $hasChildren ? '#' : ($item['route'] ?? '#') }}"
                            @if($hasChildren) @click.prevent="toggle('{{ $itemId }}')" @endif
                            class="{{ $finalItemClass }}{{ $disabled ? ' opacity-60 pointer-events-none' : '' }}"
                            title="{{ $item['tooltip'] ?? '' }}"
                            @if(!empty($item['tooltip'])) x-tooltip="'{{ $item['tooltip'] }}'" @endif
                            @if($external) target="_blank" rel="noopener" @endif
                            style="cursor: pointer;"
                        >
                            {!! $iconHtml !!}
                            <span class="truncate {{ $itemLabelClass }} transition-all duration-500" x-show="!sidebarIsCollapsed" :class="{ 'hidden': sidebarIsCollapsed }">
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
                                <i class="fas fa-caret-down ml-auto text-xs opacity-60 transition-all duration-500" x-show="!sidebarIsCollapsed"
                                    :class="{ 'rotate-180': isOpen('{{ $itemId }}'),  'hidden': sidebarIsCollapsed }"
                                    style="transition: transform 0.2s"
                                ></i>
                            @endif
                        </a>
                        @if($hasChildren)
                            <div
                                class="space-y-1 {{ $childBorderClass }}"
                                :class="{
                                    'ml-7 pl-3 mt-1': !sidebarIsCollapsed,
                                    'ml-1 pl-1 mt-1 border-l-0': sidebarIsCollapsed
                                }"
                                x-show="isOpen('{{ $itemId }}')"
                                x-transition
                                style="display: none;"
                            >
                                @foreach($item['children'] as $child)
                                    @if(!empty($child['divider']))
                                        <div class="my-2 border-t border-gray-200 dark:border-gray-600"></div>
                                        @continue
                                    @endif
                                    @php
                                        $childIsActive = $isItemActive($child);
                                        $childIcon = $renderIcon($child['icon'] ?? '', $iconClass);
                                        $childBadge = $child['badge'] ?? null;
                                        $childDisabled = $child['disabled'] ?? false;
                                        $childExternal = $child['external'] ?? false;
                                        $childClassOverride = $child['class'] ?? null;
                                        $childLabelClass = $child['label_class'] ?? '';
                                        $childBadgeVar = $customBadges[$child['id'] ?? ''] ?? null;
                                        $childSlotBadgeVar = $__data['badge-' . ($child['id'] ?? '')] ?? null;

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
                                    @endphp
                                    <a
                                        href="{{ $child['route'] ?? '#' }}"
                                        class="{{ $finalChildClass }}{{ $childDisabled ? ' opacity-60 pointer-events-none' : '' }}"
                                        title="{{ $child['tooltip'] ?? '' }}"
                                        :class="{ 'justify-center': sidebarIsCollapsed, 'justify-start': !sidebarIsCollapsed }"
                                        @if(!empty($child['tooltip'])) x-tooltip="'{{ $child['tooltip'] }}'" @endif
                                        @if($childExternal) target="_blank" rel="noopener" @endif
                                    >
                                        {!! $childIcon !!}
                                        <span class="truncate {{ $childLabelClass }} transition-all duration-500" x-show="!sidebarIsCollapsed" :class="{ 'hidden': sidebarIsCollapsed }">
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
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</nav>

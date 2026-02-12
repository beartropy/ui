@php
    [$colorPreset, $sizePreset] = $getComponentPresets('table');
@endphp

<div
    x-data="$beartropy.beartropyTable({
        data: @js($items),
        columns: @js($columns),
        perPage: {{ $perPage }},
        sortable: {{ $sortable ? 'true' : 'false' }},
        searchable: {{ $searchable ? 'true' : 'false' }},
        paginated: {{ $paginated ? 'true' : 'false' }},
    })"
    class="w-full"
>
    <!-- Search and counter -->
    <div class="{{ $colorPreset['searchbox'] }}">
        <template x-if="searchable">
            <x-beartropy-ui::input
                sm
                type="text"
                x-model="search"
                placeholder="{{ __('beartropy-ui::ui.search') }}"
            />
        </template>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto beartropy-thin-scrollbar {{ $colorPreset['table'] ?? '' }}">
        <table class="w-full border-collapse text-sm">
            <thead class="{{ $colorPreset['thead'] ?? '' }}">
                <tr>
                    <template x-for="col in columns" :key="col">
                        <th
                            role="columnheader"
                            class="cursor-pointer px-3 py-2 text-left select-none {{ $colorPreset['th'] ?? '' }}"
                            :class="sortable ? 'hover:text-beartropy-600 transition' : ''"
                            :aria-sort="sortable && sortBy === col ? (sortDesc ? 'descending' : 'ascending') : (sortable ? 'none' : undefined)"
                            @click="toggleSort(col)"
                        >
                            <span x-text="colLabel(col)"></span>
                            <template x-if="sortable && sortBy === col">
                                <span x-text="sortDesc ? '▼' : '▲'" class="ml-1 text-xs" aria-hidden="true"></span>
                            </template>
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, i) in paginatedRows" :key="i">
                    <tr class="{{ $colorPreset['row'] ?? '' }} @if($striped) even:bg-gray-50 dark:even:bg-gray-800/50 @endif">
                        <template x-for="col in columns" :key="col">
                            <td class="px-3 py-2 {{ $colorPreset['td'] ?? '' }}" @if($allowHtml) x-html="row[col]" @else x-text="row[col]" @endif></td>
                        </template>
                    </tr>
                </template>
                <template x-if="!paginatedRows.length">
                    <tr>
                        <td class="py-6 text-center text-gray-400" :colspan="columns.length" role="status">{{ __('beartropy-ui::ui.no_results') }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Paginator -->
    <div class="flex items-center justify-between mt-2" x-show="paginated">
        <div class="text-sm {{ $colorPreset['pagination_info'] }}">
            {{ __('beartropy-ui::ui.table_showing') }} <span x-text="filtered.length ? start + 1 : 0"></span>
            {{ __('beartropy-ui::ui.table_to') }} <span x-text="start + paginatedRows.length"></span>
            {{ __('beartropy-ui::ui.table_of') }} <span x-text="filtered.length"></span> {{ __('beartropy-ui::ui.table_results') }}
        </div>
        <nav aria-label="{{ __('beartropy-ui::ui.table_results') }}">
            <ul class="{{ $colorPreset['pagination_container'] }}">
                <!-- Previous button -->
                <li>
                    <button
                        type="button"
                        @click="prevPage"
                        :disabled="page === 1"
                        class="{{ $colorPreset['pagination_button'] }} rounded-l-lg"
                        :class="page === 1 ? '{{ $colorPreset['pagination_disabled'] }}' : ''"
                        aria-label="{{ __('beartropy-ui::ui.table_previous') }}"
                    >
                        <svg class="{{ $colorPreset['pagination_icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </li>
                <!-- Page numbers (unique key by value+index) -->
                <template x-for="(p, i) in pagesToShow()" :key="p + '-' + i">
                    <li>
                        <template x-if="p === '...'">
                            <button type="button" class="{{ $colorPreset['pagination_ellipsis'] }}" aria-hidden="true">…</button>
                        </template>
                        <template x-if="p !== '...'">
                            <button
                                type="button"
                                @click="gotoPage(p)"
                                :class="page === p
                                    ? '{{ $colorPreset['pagination_active'] }}'
                                    : '{{ $colorPreset['pagination_button'] }}'"
                                :aria-current="page === p ? 'page' : undefined"
                                class="rounded"
                                x-text="p"
                            ></button>
                        </template>
                    </li>
                </template>
                <!-- Next button -->
                <li>
                    <button
                        type="button"
                        @click="nextPage"
                        :disabled="page === totalPages"
                        class="{{ $colorPreset['pagination_button'] }} rounded-r-lg"
                        :class="page === totalPages ? '{{ $colorPreset['pagination_disabled'] }}' : ''"
                        aria-label="{{ __('beartropy-ui::ui.table_next') }}"
                    >
                        <svg class="{{ $colorPreset['pagination_icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</div>

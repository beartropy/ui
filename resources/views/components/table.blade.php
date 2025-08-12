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
    <!-- Buscador y contador -->
    <div class="{{ $colorPreset['searchbox'] }}">
        <template x-if="searchable">
            <x-beartropy-ui::input
                sm
                type="text"
                x-model="search"
                placeholder="Buscar..."
            />
        </template>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto beartropy-thin-scrollbar {{ $colorPreset['table'] ?? '' }}">
        <table class="w-full border-collapse text-sm">
            <thead class="{{ $colorPreset['thead'] ?? '' }}">
                <tr>
                    <template x-for="col in columns" :key="col">
                        <th
                            class="cursor-pointer px-3 py-2 text-left select-none {{ $colorPreset['th'] ?? '' }}"
                            :class="sortable ? 'hover:text-beartropy-600 transition' : ''"
                            @click="toggleSort(col)"
                        >
                            <span x-text="colLabel(col)"></span>
                            <template x-if="sortable && sortBy === col">
                                <span x-text="sortDesc ? '▼' : '▲'" class="ml-1 text-xs"></span>
                            </template>
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, i) in paginatedRows" :key="i">
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 {{ $colorPreset['row'] ?? '' }}">
                        <template x-for="col in columns" :key="col">
                            <td class="px-3 py-2 {{ $colorPreset['td'] ?? '' }}" x-text="row[col]"></td>
                        </template>
                    </tr>
                </template>
                <template x-if="!paginatedRows.length">
                    <tr>
                        <td class="py-6 text-center text-gray-400" :colspan="columns.length">No results</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Paginador -->
    <div class="flex items-center justify-between mt-2"  x-show="paginated">
        <div class="text-sm {{$colorPreset['pagination_info']}}">
            Showing <span x-text="filtered.length ? start + 1 : 0"></span>
            to <span x-text="start + paginatedRows.length"></span>
            of <span x-text="filtered.length"></span> results
        </div>
        <nav>
            <ul class="{{ $colorPreset['pagination_container'] }}">
                <!-- Botón anterior -->
                <li>
                    <button
                        @click="prevPage"
                        :disabled="page === 1"
                        class="{{ $colorPreset['pagination_button'] }} rounded-l-lg"
                        :class="page === 1 ? '{{ $colorPreset['pagination_disabled'] }}' : ''"
                    >
                        <svg class="{{ $colorPreset['pagination_icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </li>
                <!-- Números de página (key único por valor+índice) -->
                <template x-for="(p, i) in pagesToShow()" :key="p + '-' + i">
                    <li>
                        <template x-if="p === '...'">
                            <button class="{{ $colorPreset['pagination_ellipsis'] }}">…</button>
                        </template>
                        <template x-if="p !== '...'">
                            <button
                                @click="gotoPage(p)"
                                :class="page === p
                                    ? '{{ $colorPreset['pagination_active'] }}'
                                    : '{{ $colorPreset['pagination_button'] }}'"
                                class="rounded"
                                x-text="p"
                            ></button>
                        </template>
                    </li>
                </template>
                <!-- Botón siguiente -->
                <li>
                    <button
                        @click="nextPage"
                        :disabled="page === totalPages"
                        class="{{ $colorPreset['pagination_button'] }} rounded-r-lg"
                        :class="page === totalPages ? '{{ $colorPreset['pagination_disabled'] }}' : ''"
                    >
                        <svg class="{{ $colorPreset['pagination_icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</div>

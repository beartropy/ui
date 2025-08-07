@props([
    'stickyHeader' => false,
    'stickySidebar' => false,
    'footerFixed' => false,
    'sidebarGrow' => false,
    'sidebarOver' => false, // si true: header arriba de todo, no como parte del flex row
    'sidebarWidth' => 16,    // En rem (ej: 16 = 16rem)
    'footerHeight' => 3,     // En rem (ej: 3 = 3rem)
    'headerHeight' => 3.5,    // En rem (ej: 3.5 = 3.5rem)

    'miniSidebar' => true, // Si true, el sidebar es mini (3rem)
    'collapsibleSidebar' => true, // Si true, el sidebar puede colapsar
    'sidebarCollapsed' => false, // Si true, el sidebar está colapsado
    'collapsedSidebarWidth' => 3, // Ancho del sidebar colapsado (ej: 4rem)
    'hideCollapseButton' => false, // Si true, el botón de colapso está en el header
    'hideMobileButton' => false, // Si true, no muestra el botón de colapso en mobile
])

@php
    // Asegura números y arma los estilos css dinámicos
    $sidebarWidthCss = (float)$sidebarWidth . 'rem';
    $footerHeightCss = (float)$footerHeight . 'rem';
    $headerHeightCss = (float)$headerHeight . 'rem';
    $collapsedSidebarWidthCss = (float)$collapsedSidebarWidth . 'rem';

    //Sidebar collapse
    $collapseButtonStyle = "left: ".((float)$sidebarWidth - 1) . 'rem;';
    if ($miniSidebar) {
        $collapsedButtonStyle = "left: ".((float)$collapsedSidebarWidth - 0.5) . 'rem;'; // Ancho del botón de colapso
    } else {
        $collapsedSidebarWidth = 0;
        $collapsedSidebarWidthCss = 0;
        $collapsedButtonStyle = "left: -0.5rem;"; // Ancho del botón de colapso
    }


    // Helpers para estilos condicionales
    $asideStyle = "width: $sidebarWidthCss;";
    $mainOffsetStyle = ($stickySidebar || $footerFixed) ? "margin-left: $sidebarWidthCss;" : "";
    $footerOffsetStyle = ($stickySidebar || $footerFixed) ? "padding-left: $sidebarWidthCss;" : "";
    $headerStyle = "height: $headerHeightCss;";
    $footerStyle = "height: $footerHeightCss;";



@endphp

<div
    x-data="{
        sidebarCollapsed: {{ $sidebarCollapsed ? 'true' : 'false' }},
        miniSidebar: {{ $miniSidebar ? 'true' : 'false' }},
        isMobile: window.matchMedia('(max-width: 767px)').matches,
        toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed },
        updateResponsive() {
            this.isMobile = window.matchMedia('(max-width: 767px)').matches;
            if(this.isMobile) this.miniSidebar = false;
            else this.miniSidebar = {{ $miniSidebar ? 'true' : 'false' }};
        }
    }"
    x-init="updateResponsive(); window.addEventListener('resize', () => updateResponsive())"
>
    @if($sidebarOver)
        {{-- Variante: header arriba de todo --}}
        <div class="flex flex-col min-h-screen">
            <header class="flex-shrink-0 @if($stickyHeader) sticky top-0 z-10 @endif"
                    style="{{ $headerStyle }}">
                {{ $header ?? '' }}
            </header>
            <div class="flex flex-1">
                <aside class="text-white flex flex-col min-h-screen sticky top-0"
                    style="{{ $asideStyle }}">
                    {{ $sidebar ?? '' }}
                </aside>
                <div class="flex-1 flex flex-col min-h-screen">
                    <main class="flex-1">
                        {{ $slot }}
                    </main>
                </div>
            </div>
            <footer class="flex items-center justify-center w-full @if($footerFixed) fixed bottom-0 right-0 z-10 @endif"
                    style="{{ $footerStyle }}">
                {{ $footer ?? '' }}
            </footer>
        </div>
    @else
        {{-- Variante: sidebar y main en row, header dentro de main --}}
        <div class="flex flex-col min-h-screen">
            <div class="flex flex-1">
                <aside
                    class="text-white flex flex-col transition-all duration-500 ease-in-out @if($stickySidebar) fixed top-0 left-0 min-h-full h-full z-30 @elseif($sidebarGrow) flex-col min-h-full sticky top-0 @endif"
                    style="{{ $asideStyle }}"
                    :style="sidebarCollapsed
                                ? ('width: ' + (miniSidebar ? '{{ $collapsedSidebarWidthCss }}' : '0rem') )
                                : 'width: {{ $sidebarWidthCss }}'"

                >
                    @if($collapsibleSidebar && !$hideCollapseButton)
                        {{-- Botón de colapso en el sidebar --}}
                        <button
                            @click="sidebarCollapsed = !sidebarCollapsed"
                            type="button"
                            class="absolute  top-1/4 -translate-y-1/2 z-50 transition-all duration-500
                                bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                                rounded-full shadow p-1 w-7 h-7 flex items-center justify-center
                                 hover:bg-gray-100 dark:hover:bg-gray-800
                                focus:outline-none"
                            :style="sidebarCollapsed ? '{{$collapsedButtonStyle}}' : '{{$collapseButtonStyle}}'"
                            title="Colapsar/Expandir sidebar"
                        >
                            <template x-if="!sidebarCollapsed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </template>
                            <template x-if="sidebarCollapsed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </template>
                        </button>
                    @endif
                        <div x-show="!sidebarCollapsed || miniSidebar"
                                x-transition:enter="transition-opacity duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="h-full flex flex-col"
                            >
                                {{ $sidebar ?? '' }}
                        </div>
                </aside>
                <div class="flex-1 flex flex-col min-h-screen transition-all duration-500"
                    :style="sidebarCollapsed
                        ? ('margin-left: ' + (miniSidebar ? '{{ $stickySidebar ? $collapsedSidebarWidthCss : '0rem' }}' : '0rem'))
                        : 'margin-left: {{ $stickySidebar ? $sidebarWidthCss : '' }}'"
                >
                    <header class="@if($stickyHeader) sticky top-0 z-10 @endif flex-shrink-0"
                            style="{{ $headerStyle }}">

                            @if(!$hideMobileButton)
                                <button
                                    @click="sidebarCollapsed = !sidebarCollapsed"
                                    type="button"
                                    aria-label="Abrir menú"
                                    class="flex flex-col justify-center items-center w-10 h-10 rounded-md focus:outline-none bg-transparent"
                                >
                                    <span class="block w-7 h-0.5 bg-current mb-1"></span>
                                    <span class="block w-7 h-0.5 bg-current mb-1"></span>
                                    <span class="block w-7 h-0.5 bg-current"></span>
                                </button>
                            @endif



                        {{ $header ?? '' }}
                    </header>
                    <main class="flex-1 @if($footerFixed) mb-12 @endif">
                        {{ $slot }}
                    </main>
                    <footer
                        class="flex items-center justify-center w-full transition-all duration-500
                            @if($footerFixed) fixed bottom-0 right-0 z-10 mt-2 @endif"
                        :style="sidebarCollapsed
                            ? ('padding-left: ' + (miniSidebar ? '{{ $stickySidebar ? $collapsedSidebarWidthCss : '0rem' }}' : '0rem') + ';{{ $footerStyle }}')
                            : 'padding-left: {{ $stickySidebar ? $sidebarWidthCss : '' }};{{ $footerStyle }}'"
                    >
                        {{ $footer ?? '' }}
                    </footer>

                </div>
            </div>
        </div>
    @endif
    <script>
        function toggleSideBar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
        }
    </script>
</div>

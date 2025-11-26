@props([
    'logo' => null,
    'bg' => 'bg-light dark:bg-gray-900',
    'border' => 'border-gray-200 dark:border-gray-800',
])

<aside
    class="h-full overflow-y-auto overflow-x-hidden flex-shrink-0 flex flex-col transition-all duration-300 {{ $bg }} z-30 border-r {{ $border }}"
    :class="{
        'w-10 md:w-10': !sidebarOpen && window.innerWidth >= 768,
        'w-60 md:w-60': sidebarOpen && window.innerWidth >= 768,
        'w-full': sidebarOpen && window.innerWidth < 768,
        'hidden': !sidebarOpen && window.innerWidth < 768,
    }"
>
    @if($logo)
        {{ $logo }}
    @endif

    <nav class="flex-1 flex flex-col gap-1 px-2 py-4">
        {{ $slot }}
    </nav>
</aside>

<!-- Hamburger Button for Mobile -->
<button
    @click="sidebarOpen = !sidebarOpen"
    class="absolute top-3 right-3 z-40 text-gray-600 hover:text-blue-600 md:hidden"
    x-show="!sidebarOpen"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

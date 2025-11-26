@props([
    'sidebar' => null,
    'navbar' => null,
    'mainBg' => 'bg-light dark:bg-gray-900',
    'mainText' => 'text-gray-600 dark:text-gray-300',
])

<div x-data="{ sidebarOpen: window.innerWidth > 768 }" class="flex h-full w-full">
    @if($sidebar)
        @persist('sidebar')
            {{ $sidebar }}
        @endpersist
    @endif

    <!-- Contenedor derecho -->
    <div class="flex flex-col flex-grow h-full overflow-hidden relative">
        <!-- Navbar -->
        @if($navbar)
            @persist('navbar')
                {{ $navbar }}
            @endpersist
        @endif

        <!-- Contenido principal -->
        <main class="flex flex-col flex-grow {{ $mainBg }} {{ $mainText }} p-2 mt-10 overflow-hidden">
            {{ $slot }}
        </main>
    </div>
</div>

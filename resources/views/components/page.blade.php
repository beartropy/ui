@props([
    'title' => null,
    'actions' => null,
    'bg' => 'bg-white dark:bg-gray-800',
    'headerBorder' => 'border-gray-300 dark:border-gray-700',
    'titleColor' => 'text-gray-800 dark:text-gray-200',
])

<div class="flex flex-col flex-1 h-full {{ $bg }} rounded-xl p-3 shadow-sm">
    <!-- Header -->
    @if ($title)
        <div class="pb-3 mb-3 border-b {{ $headerBorder }} flex items-center justify-between">
            <h1 class="text-xl font-semibold {{ $titleColor }} p-2">
                {{ $title }}
            </h1>

            @if ($actions)
                <div class="flex items-center space-x-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <!-- Scrollable content -->
    <div class="flex-1 overflow-y-auto beartropy-thin-scrollbar p-1 px-3">
        {{ $slot }}
    </div>
</div>

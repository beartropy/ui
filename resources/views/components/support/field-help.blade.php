<p class="text-sm ml-1 mt-0.5 {{ $minHeight }} {{ $errorMessage ? 'text-red-500 visible' : ($hint ? 'text-gray-400 visible' : 'invisible') }}">
    {{ $errorMessage ?? $hint ?? ' ' }}
</p>

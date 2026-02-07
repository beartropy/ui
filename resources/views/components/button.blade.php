<div class="relative">

    {{-- The actual button --}}
    <x-beartropy-ui::base.button-base
        :type="$type"
        :href="$href"
        :disabled="$disabled"
        :iconStart="$iconStart"
        :iconEnd="$iconEnd"
        :spinner="$spinner"
        :iconSet="$iconSet"
        :iconVariant="$iconVariant"
        {{ $attributes }}
    >
        @if(isset($start))
            <x-slot:start>
                {{ $start }}
            </x-slot:start>
        @endif

        @if(isset($end))
            <x-slot:end>
                {{ $end }}
            </x-slot:end>
        @endif

        {{ trim($slot) !== '' ? $slot : $label }}
    </x-beartropy-ui::base.button-base>
</div>

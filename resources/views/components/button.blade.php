<div class="relative">

    {{-- El botón real --}}
    <x-base.button-base
        :type="$type"
        :href="$href"
        :disabled="$disabled"
        :iconStart="$iconStart"
        :iconEnd="$iconEnd"
        :spinner="$spinner"
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
    </x-base.button-base>
</div>

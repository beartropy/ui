

@php($viewData = $getViewData($__data))

<style>
@keyframes theme-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.theme-rotate { animation: theme-spin 0.5s cubic-bezier(.4,2,.6,1); }
</style>

<div
    x-data="btToggleTheme()"
    class="inline-flex items-center cursor-pointer {{ $viewData->class }}"
>
    @if($viewData->mode === 'button')
        <button
            type="button"
            @click.stop="toggle()"
            :aria-pressed="dark"
            aria-label="{{ $viewData->ariaLabel }}"
            class="{{ $viewData->buttonClasses }} {{ $viewData->borderColorLight }}"
            :class="dark ? '{{ $viewData->borderColorDark }}' : '{{ $viewData->borderColorLight }}'"
        >
            {{-- Label (left) --}}
            @if($viewData->hasLabel && $viewData->labelPosition === 'left')
                <span class="{{ $viewData->labelClasses }}">{{ $viewData->label }}</span>
            @endif

            {{-- Icon light --}}
            @if($viewData->hasIconLightSlot)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    {{ $iconLight }}
                </span>
            @elseif($viewData->iconLight)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    <x-beartropy-ui::icon :name="$viewData->iconLight" :class="$viewData->iconLightClasses" />
                </span>
            @else
                <svg x-show="!dark"
                    :class="`{{ $viewData->iconLightClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2m0 18v2m11-11h-2M3 12H1
                        m16.95 7.07l-1.41-1.41
                        M5.46 5.46L4.05 4.05
                        m14.14 0l-1.41 1.41
                        M5.46 18.54l-1.41 1.41"/>
                </svg>
            @endif

            {{-- Icon dark --}}
            @if($viewData->hasIconDarkSlot)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    {{ $iconDark }}
                </span>
            @elseif($viewData->iconDark)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    <x-beartropy-ui::icon :name="$viewData->iconDark" :class="$viewData->iconDarkClasses" />
                </span>
            @else
                <svg x-show="dark"
                    :class="`{{ $viewData->iconDarkClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1111.21 3 a7 7 0 109.79 9.79z"/>
                </svg>
            @endif

            {{-- Label (right) --}}
            @if($viewData->hasLabel && $viewData->labelPosition === 'right')
                <span class="{{ $viewData->labelClasses }}">{{ $viewData->label }}</span>
            @endif
        </button>

    @elseif($viewData->mode === 'square-button')
        <button
            type="button"
            @click.stop="toggle()"
            :aria-pressed="dark"
            aria-label="{{ $viewData->ariaLabel }}"
            class="{{ $viewData->squareButtonClasses }} {{ $viewData->borderColorLight }}"
            :class="dark ? '{{ $viewData->borderColorDark }}' : '{{ $viewData->borderColorLight }}'"
            style="padding: 0;"
        >
            {{-- Icon light --}}
            @if($viewData->hasIconLightSlot)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    {{ $iconLight }}
                </span>
            @elseif($viewData->iconLight)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    <x-beartropy-ui::icon :name="$viewData->iconLight" :class="$viewData->iconLightClasses" />
                </span>
            @else
                <svg x-show="!dark"
                    :class="`{{ $viewData->iconLightClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2m0 18v2m11-11h-2M3 12H1
                        m16.95 7.07l-1.41-1.41
                        M5.46 5.46L4.05 4.05
                        m14.14 0l-1.41 1.41
                        M5.46 18.54l-1.41 1.41"/>
                </svg>
            @endif

            {{-- Icon dark --}}
            @if($viewData->hasIconDarkSlot)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    {{ $iconDark }}
                </span>
            @elseif($viewData->iconDark)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    <x-beartropy-ui::icon :name="$viewData->iconDark" :class="$viewData->iconDarkClasses" />
                </span>
            @else
                <svg x-show="dark"
                    :class="`{{ $viewData->iconDarkClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1111.21 3 a7 7 0 109.79 9.79z"/>
                </svg>
            @endif
        </button>

    @else {{-- icon mode --}}
        <button
            type="button"
            @click.stop="toggle()"
            :aria-pressed="dark"
            aria-label="{{ $viewData->ariaLabel }}"
            class="inline-flex items-center bg-transparent border-0 p-0 cursor-pointer focus:outline-none"
        >
            {{-- Icon light --}}
            @if($viewData->hasIconLightSlot)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    {{ $iconLight }}
                </span>
            @elseif($viewData->iconLight)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    <x-beartropy-ui::icon :name="$viewData->iconLight" :class="$viewData->iconLightClasses" />
                </span>
            @else
                <svg x-show="!dark"
                    :class="`{{ $viewData->iconLightClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2m0 18v2m11-11h-2M3 12H1
                        m16.95 7.07l-1.41-1.41
                        M5.46 5.46L4.05 4.05
                        m14.14 0l-1.41 1.41
                        M5.46 18.54l-1.41 1.41"/>
                </svg>
            @endif

            {{-- Icon dark --}}
            @if($viewData->hasIconDarkSlot)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    {{ $iconDark }}
                </span>
            @elseif($viewData->iconDark)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    <x-beartropy-ui::icon :name="$viewData->iconDark" :class="$viewData->iconDarkClasses" />
                </span>
            @else
                <svg x-show="dark"
                    :class="`{{ $viewData->iconDarkClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1111.21 3 a7 7 0 109.79 9.79z"/>
                </svg>
            @endif
        </button>
    @endif
</div>

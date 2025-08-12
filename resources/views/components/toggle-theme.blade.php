

@php($viewData = $getViewData($__data))

<style>
.theme-rotatable { transform: rotate(0deg); transition: transform 0.5s cubic-bezier(.4,2,.6,1) !important; }
.theme-rotate { transform: rotate(360deg) !important; }
</style>

<div
    x-data="{
        dark: localStorage.theme === 'dark'
            || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        rotating: false,
        toggle() {
            this.rotating = true;
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.theme = this.dark ? 'dark' : 'light';
            window.dispatchEvent(new CustomEvent('theme-change', { detail: { theme: this.dark ? 'dark' : 'light' } }));
            setTimeout(() => { this.rotating = false }, 50);
        },
    }"
    x-init="
        if (dark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        window.addEventListener('theme-change', function(e) {
            if (e.detail && e.detail.theme) {
                dark = (e.detail.theme === 'dark');
                document.documentElement.classList.toggle('dark', dark);
                localStorage.theme = dark ? 'dark' : 'light';
            }
        });
    "
    class="inline-flex items-center cursor-pointer {{ $viewData->class }}"
>
    @if($viewData->mode === 'button')
        <button
            type="button"
            @click="toggle()"
            :aria-pressed="dark"
            class="{{ $viewData->buttonClasses }} {{ $viewData->borderColorLight }}"
            :class="dark ? '{{ $viewData->borderColorDark }}' : '{{ $viewData->borderColorLight }}'"
        >
            {{-- ICON LIGHT --}}
            @if($viewData->hasIconLightSlot)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    {!! $__data['icon-light'] !!}
                </span>
            @elseif($viewData->iconLight)
            <span x-show="!dark">
                <x-beartropy-ui::icon :name="$viewData->iconLight"
                           :class="$viewData->iconLightClasses"
                           x-show="!dark" />
            </span>
            @else
                <svg x-show="!dark" @click="toggle()" style="cursor:pointer"
                    :class="`{{ $viewData->iconLightClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2m0 18v2m11-11h-2M3 12H1
                        m16.95 7.07l-1.41-1.41
                        M5.46 5.46L4.05 4.05
                        m14.14 0l-1.41 1.41
                        M5.46 18.54l-1.41 1.41"/>
                </svg>
            @endif

            {{-- ICON DARK --}}
            @if($viewData->hasIconDarkSlot)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    {!! $__data['icon-dark'] !!}
                </span>
            @elseif($viewData->iconDark)
            <span x-show="dark">
                <x-beartropy-ui::icon :name="$viewData->iconDark"
                           :class="$viewData->iconDarkClasses"
                           x-show="dark" />
            </span>
            @else
                <svg x-show="dark" @click="toggle()" style="cursor:pointer"
                    :class="`{{ $viewData->iconDarkClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12.79A9 9 0 1111.21 3 a7 7 0 109.79 9.79z"/>
                </svg>
            @endif
        </button>

    @elseif($viewData->mode === 'square-button')
        <button
            type="button"
            @click="toggle()"
            :aria-pressed="dark"
            class="{{ $viewData->squareButtonClasses }} {{ $viewData->borderColorLight }}"
            :class="dark ? '{{ $viewData->borderColorDark }}' : '{{ $viewData->borderColorLight }}'"
            style="padding: 0;"
        >
            {{-- ICON LIGHT --}}
            @if($viewData->hasIconLightSlot)
                <span x-show="!dark" :class="rotating ? 'theme-rotate' : ''">
                    {!! $__data['icon-light'] !!}
                </span>
            @elseif($viewData->iconLight)
                <span x-show="!dark">
                    <x-beartropy-ui::icon :name="$viewData->iconLight"
                               :class="$viewData->iconLightClasses"
                               x-show="!dark" />
                </span>
            @else
                <svg x-show="!dark" @click="toggle()" style="cursor:pointer"
                    :class="`{{ $viewData->iconLightClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2m0 18v2m11-11h-2M3 12H1
                        m16.95 7.07l-1.41-1.41
                        M5.46 5.46L4.05 4.05
                        m14.14 0l-1.41 1.41
                        M5.46 18.54l-1.41 1.41"/>
                </svg>
            @endif

            {{-- ICON DARK --}}
            @if($viewData->hasIconDarkSlot)
                <span x-show="dark" :class="rotating ? 'theme-rotate' : ''">
                    {!! $__data['icon-dark'] !!}
                </span>
            @elseif($viewData->iconDark)
                <span x-show="dark">
                    <x-beartropy-ui::icon :name="$viewData->iconDark"
                               :class="$viewData->iconDarkClasses"
                               x-show="dark" />
                </span>
            @else
                <svg x-show="dark" @click="toggle()" style="cursor:pointer"
                    :class="`{{ $viewData->iconDarkClasses }}` + (rotating ? ' theme-rotate' : '')"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12.79A9 9 0 1111.21 3 a7 7 0 109.79 9.79z"/>
                </svg>
            @endif
        </button>

    @else {{-- icon mode --}}
        @if($viewData->hasIconLightSlot)
            <span x-show="!dark" @click="toggle()" style="cursor:pointer" :class="rotating ? 'theme-rotate' : ''">
                {!! $__data['icon-light'] !!}
            </span>
        @elseif($viewData->iconLight)
            <div x-show="!dark">
                <x-beartropy-ui::icon :name="$viewData->iconLight"
                        :class="$viewData->iconLightClasses"
                        @click="toggle()" style="cursor:pointer" />
            </div>
        @else
            <svg x-show="!dark" @click="toggle()" style="cursor:pointer"
                :class="`{{ $viewData->iconLightClasses }}` + (rotating ? ' theme-rotate' : '')"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="5"/>
                <path d="M12 1v2m0 18v2m11-11h-2M3 12H1
                    m16.95 7.07l-1.41-1.41
                    M5.46 5.46L4.05 4.05
                    m14.14 0l-1.41 1.41
                    M5.46 18.54l-1.41 1.41"/>
            </svg>
        @endif

        @if($viewData->hasIconDarkSlot)
            <span x-show="dark" @click="toggle()" style="cursor:pointer" :class="rotating ? 'theme-rotate' : ''">
                {!! $__data['icon-dark'] !!}
            </span>
        @elseif($viewData->iconDark)
            <span x-show="dark">
                <x-beartropy-ui::icon :name="$viewData->iconDark"
                        :class="$viewData->iconDarkClasses"
                            @click="toggle()" style="cursor:pointer" />
            </span>
        @else
            <svg x-show="dark" @click="toggle()" style="cursor:pointer"
                :class="`{{ $viewData->iconDarkClasses }}` + (rotating ? ' theme-rotate' : '')"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 12.79A9 9 0 1111.21 3 a7 7 0 109.79 9.79z"/>
            </svg>
        @endif
    @endif
</div>

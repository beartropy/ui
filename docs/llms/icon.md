# Icon Component — LLM Reference

## Component

`<x-bt-icon />` — renders icons from Heroicons, Beartropy SVG, FontAwesome, or Lucide.

## Class

`Beartropy\Ui\Components\Icon` extends `BeartropyComponent`.

## Files

- PHP: `src/Components/Icon.php`
- Blade: `resources/views/components/icon.blade.php`
- SVGs: `resources/views/svg/beartropy-*.blade.php`
- Config: `config/beartropyui.php` → `icons.set`, `icons.variant`
- Sizes: `resources/views/presets/sizes.php` → `iconSize` key

## Constructor

```php
public function __construct(
    public string $name,        // Icon name (e.g. 'home', 'heroicon-s-home')
    public ?string $size = null, // Size preset key (xs, sm, md, lg, xl)
    public string $class = '',   // Additional CSS classes
    public bool $solid = false,  // Force solid variant
    public bool $outline = false, // Force outline variant
    public ?string $set = null,  // Icon set override
    public ?string $variant = null, // Variant override (solid, outline)
)
```

## Key Method: getClasses(?string $iconSize): object

Returns `{allClasses, iconComponent, fa, set, variant, name, class}`.

Logic:
1. Resolves `set` from prop or config (`beartropyui.icons.set`, default `heroicons`).
2. Resolves `variant`: `solid` prop > `outline` prop > `variant` prop > config default.
3. Parses heroicon prefixes: `heroicon-o-name` → outline, `heroicon-s-name` → solid.
4. Builds `iconComponent` string per set:
   - heroicons: `heroicon-{s|o}-{name}`
   - lucide: `lucide-{name}`
   - beartropy: `beartropy-ui-svg::beartropy-{name}`
   - fontawesome: sets `fa` string instead of `iconComponent`
   - unknown: `iconComponent = null` → renders `<span class="text-red-600">?</span>`

## Blade Template Logic

```
if iconComponent exists:
  if beartropy → @include (SVG partial with attributes merge)
  else → <x-dynamic-component> (heroicons, lucide)
elseif fontawesome → <i class="...">
else → <span class="text-red-600">?</span>
```

## Size Presets (magic attributes)

| Attr | iconSize |
|------|----------|
| `xs` | `w-3 h-3` |
| `sm` | `w-4 h-4` |
| `md` | `w-5 h-5` (default) |
| `lg` | `w-6 h-6` |
| `xl` | `w-7 h-7` |

## Colors

No color presets. Apply via Tailwind classes: `class="text-red-500"`.

## Beartropy SVG Icons

Available names: `search`, `check`, `x-mark`, `edit`, `trash`, `eye`, `eye-slash`, `calendar`, `clock`, `clipboard`, `upload`, `spinner`, `paper-airplane-right`.

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-icon name="home" />

{{-- Solid variant --}}
<x-bt-icon name="home" :solid="true" />

{{-- With color and size --}}
<x-bt-icon name="heart" class="text-red-500" lg />

{{-- Beartropy set --}}
<x-bt-icon name="search" set="beartropy" />

{{-- FontAwesome --}}
<x-bt-icon name="fa-solid fa-house" set="fontawesome" />

{{-- Heroicon prefix --}}
<x-bt-icon name="heroicon-s-bell" />
```

# Beartropy UI

A beautiful, modern UI components library for Laravel & Livewire.

Beartropy UI is a modular, customizable component system designed for real-world apps. Ships with beautiful presets, accessible markup, Tailwind support, and out-of-the-box integration with Livewire and Alpine.js.

---

## 🚀 Features

* **Plug & Play Components:** Inputs, selects, buttons, toggles, alerts, modals, toasts, tables, tags, and more.
* **Presets System:** Customize every aspect (colors, sizes, icons, slots) via simple Blade/array presets.
* **Blade-first:** All components are Blade files, extendable and publishable.
* **Ready for Livewire:** Built-in support for wire\:model, validation, instant feedback.
* **Tailwind Friendly:** 100% utility classes, dark mode support.
* **JS Optional:** Interactive components (modals, toasts, dropdowns) work with Alpine.js, but degrade gracefully.
* **Easy to Extend:** Add your own components, presets or override any view.

### [See full documentation here](https://beartropy.com/ui)
---

## 📦 Installation

```
composer require beartropy/ui
```

If you use Laravel >=8, the service provider will be auto-discovered.

---

## 🔧 Publishing Presets & Config

Publish config and presets:

```
php artisan vendor:publish --provider="Beartropy\Ui\BeartropyUiServiceProvider"
```

Or publish only a specific tag:

```
php artisan vendor:publish --tag=beartropyui-config
php artisan vendor:publish --tag=beartropyui-presets
```

You can also publish only a specific preset file:
```
php artisan vendor:publish --tag=beartropyui-preset-input
php artisan vendor:publish --tag=beartropyui-preset-select
```
>Replace input or select with the name of the preset you want to publish.

*After publishing presets or views, run*

```
php artisan view:clear
```

*to see changes!*

---

## ⚡️ Usage Example

```blade
<x-input label="Username" placeholder="Your username" />

<x-select :options="$categories" label="Category" />

<x-button color="beartropy" size="lg">
    Save
</x-button>

<x-alert success dismissible>
    Everything was saved!
</x-alert>
```

See the [documentation](https://beartropyui.com/docs) for more examples and advanced usage.

---

## 🎨 Presets & Customization

You can fully customize colors, icons, sizes, and behavior for every component by editing the presets in `resources/views/presets` (after publishing).

* **Add or edit color schemes, sizes, icons, etc.**
* Use the provided `add-preset` command for scaffolding new presets:

```
php artisan beartropyui:add-preset {component} {name}
```

---

## 🧑‍💻 Contributing

Found a bug, want a new feature, or have feedback? Open an issue or PR!

---

## 📜 License

Beartropy UI is open-source, MIT licensed.

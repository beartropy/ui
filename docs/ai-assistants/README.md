# AI Assistant Support for Beartropy UI

Beartropy UI includes comprehensive AI assistant integration to help you build TALL stack applications faster. This guide covers how to use Beartropy UI with different AI coding assistants.

## 🤖 Supported AI Assistants

### Claude Code (Full Support)
- ✅ Native skills with slash commands
- ✅ Context-aware component suggestions
- ✅ Complete code examples
- ✅ Interactive help

### Cursor (Full Support)
- ✅ Custom rules integration
- ✅ Component autocomplete context
- ✅ Beartropy-specific suggestions

### Other AI Tools (Content Support)
- ✅ Universal guide for any AI assistant
- ✅ Complete examples and patterns
- ✅ Copy-paste ready code

## 📂 Directory Structure

```
beartropy/ui/
├── .claude/
│   └── skills/                    # Claude Code skills (slash commands)
│       ├── beartropy-setup/
│       ├── beartropy-form/
│       ├── beartropy-component/
│       ├── beartropy-livewire/
│       ├── beartropy-patterns/
│       └── README.md
│
└── docs/
    └── ai-assistants/
        ├── README.md              # This file
        ├── BEARTROPY_GUIDE.md     # Universal AI guide
        ├── cursor/
        │   └── .cursorrules       # Cursor configuration
        └── examples/              # Code examples
            ├── forms.md
            ├── tables.md
            └── patterns.md
```

## 🚀 Quick Start by Tool

### Using with Claude Code

**Install Beartropy UI**, then use built-in skills:

```bash
/beartropy-setup          # Installation & configuration help
/beartropy-form           # Create forms with components
/beartropy-component      # Component documentation & examples
/beartropy-livewire       # Livewire integration patterns
/beartropy-patterns       # Complete UI patterns (login, tables, etc.)
```

**Example:**
```
/beartropy-form

Create a registration form with name, email, password confirmation,
and terms checkbox
```

Skills are automatically available in the `.claude/skills/` directory.

---

### Using with Cursor

**Option 1: Add Cursor Rules (Recommended)**

Copy `.cursorrules` from `docs/ai-assistants/cursor/` to your project root:

```bash
cp vendor/beartropy/ui/docs/ai-assistants/cursor/.cursorrules .cursorrules
```

Or append to existing `.cursorrules`:

```bash
cat vendor/beartropy/ui/docs/ai-assistants/cursor/.cursorrules >> .cursorrules
```

**Option 2: Reference the Guide**

Tell Cursor to read the universal guide:

```
@docs/ai-assistants/BEARTROPY_GUIDE.md Create a login form using Beartropy UI
```

**Example Usage:**
```
Create a user profile edit page with avatar upload using Beartropy UI components
```

Cursor will use the rules to suggest proper Beartropy components and patterns.

---

### Using with Windsurf/Cascade

Reference the universal guide in your prompts:

```
Using the Beartropy UI guide at vendor/beartropy/ui/docs/ai-assistants/BEARTROPY_GUIDE.md,
create a data table with search and filters
```

Or copy relevant sections to your project's `CASCADE.md` or context files.

---

### Using with Cody, Copilot, or Other AI Tools

**Option 1: Read the Universal Guide**

Point your AI assistant to:
```
vendor/beartropy/ui/docs/ai-assistants/BEARTROPY_GUIDE.md
```

**Option 2: Use Examples**

Browse ready-to-use examples in:
```
vendor/beartropy/ui/docs/ai-assistants/examples/
```

**Example Prompt:**
```
I'm using Beartropy UI (Laravel component library).
Check vendor/beartropy/ui/docs/ai-assistants/BEARTROPY_GUIDE.md
for component syntax. Create a contact form with name, email, and message fields.
```

---

## 📚 Available Resources

### Skills (Claude Code)
- **beartropy-setup** - Installation, configuration, troubleshooting
- **beartropy-form** - Form building with validation
- **beartropy-component** - Component reference and examples
- **beartropy-livewire** - Livewire integration patterns
- **beartropy-patterns** - Complete UI patterns

### Universal Guide
- **BEARTROPY_GUIDE.md** - Complete component reference
- Works with any AI assistant
- All components, props, and patterns
- Copy-paste ready examples

### Cursor Rules
- **.cursorrules** - Cursor-specific configuration
- Component syntax and best practices
- Automatic suggestions

### Code Examples
- **examples/forms.md** - Form patterns
- **examples/tables.md** - Data table patterns
- **examples/patterns.md** - Common UI patterns

## 💡 Tips for Best Results

### 1. Be Specific About Components
❌ "Create a form"
✅ "Create a form using Beartropy UI Input, Select, and Button components"

### 2. Mention Livewire Integration
❌ "Make the form reactive"
✅ "Create a Livewire form with wire:model on Beartropy UI components"

### 3. Reference Component Names
Use exact component names:
- `x-bt-input` (not just "input")
- `x-bt-select` (not "dropdown")
- `x-bt-button` (not "button")

### 4. Ask for Complete Examples
❌ "Show me the Input component"
✅ "Show me a complete Livewire form using Beartropy UI Input components with validation"

## 🔧 Component Syntax Quick Reference

All Beartropy UI components use the `x-bt-` prefix:

```blade
{{-- Forms --}}
<x-bt-input wire:model="email" label="Email" />
<x-bt-select wire:model="status" :options="$options" />
<x-bt-textarea wire:model="message" label="Message" />
<x-bt-checkbox wire:model="agreed" label="I agree" />
<x-bt-toggle wire:model="enabled" label="Enable" />

{{-- Buttons --}}
<x-bt-button primary>Submit</x-bt-button>
<x-bt-button-icon icon="trash" danger />

{{-- Display --}}
<x-bt-alert success>Success message</x-bt-alert>
<x-bt-badge>New</x-bt-badge>
<x-bt-card>Content here</x-bt-card>

{{-- Overlays --}}
<x-bt-modal wire:model="showModal">...</x-bt-modal>
<x-bt-dialog wire:model="showDialog">...</x-bt-dialog>
```

## 📖 Learning Path

### Beginners
1. Read **BEARTROPY_GUIDE.md** - Component overview
2. Try **examples/forms.md** - Simple form examples
3. Use **beartropy-setup** skill (Claude Code) or reference setup section

### Intermediate
1. Explore **beartropy-livewire** - Reactive patterns
2. Study **examples/tables.md** - Data tables with filters
3. Build custom forms with validation

### Advanced
1. Review **beartropy-patterns** - Complete applications
2. Combine components for complex UIs
3. Custom presets and theming

## 🆘 Getting Help

### With Claude Code
Use the help skills:
```
/beartropy-component
How do I use the Select component with remote data?
```

### With Other Tools
1. Reference `BEARTROPY_GUIDE.md` in your prompts
2. Ask specific questions about components
3. Request examples from the examples directory

### General Support
- **Documentation**: https://beartropy.com/ui
- **GitHub Issues**: https://github.com/beartropy/ui/issues
- **Discussions**: https://github.com/beartropy/ui/discussions

## 🎯 Common Use Cases

### Creating Forms
- **Claude Code**: `/beartropy-form`
- **Cursor**: "Create form" (with .cursorrules)
- **Other**: Reference `examples/forms.md`

### Building Data Tables
- **Claude Code**: `/beartropy-patterns` → Data table example
- **Cursor**: "Create user table with search"
- **Other**: Reference `examples/tables.md`

### Livewire Integration
- **Claude Code**: `/beartropy-livewire`
- **Cursor**: Mention "Livewire" in prompt
- **Other**: Reference Livewire section in `BEARTROPY_GUIDE.md`

## 🤝 Contributing

Found a useful pattern? Submit a PR to add it to the examples!

1. Add example to `docs/ai-assistants/examples/`
2. Update this README if needed
3. Test with multiple AI assistants
4. Submit PR

## 📄 License

These AI assistant resources are part of Beartropy UI and are provided under the MIT License.

---

**Choose your AI assistant above and start building beautiful TALL stack applications faster!** 🚀

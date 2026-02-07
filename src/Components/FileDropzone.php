<?php

namespace Beartropy\Ui\Components;

/**
 * FileDropzone component.
 *
 * A drag-and-drop file upload area with client-side validation,
 * image previews, existing-file support, and Livewire integration.
 *
 * @property string|null  $id            Component ID.
 * @property string|null  $name          Input name.
 * @property string|null  $label         Label text.
 * @property string|null  $color         Color preset.
 * @property bool         $multiple      Allow multiple files.
 * @property string|null  $accept        File accept attribute (e.g. "image/*,.pdf").
 * @property int|null     $maxFileSize   Max file size in bytes (null = no limit).
 * @property int|null     $maxFiles      Max number of files (null = unlimited).
 * @property string|null  $placeholder   Override empty-state text.
 * @property bool         $preview       Show image previews.
 * @property bool         $clearable     Allow clearing selection.
 * @property bool         $disabled      Disabled state.
 * @property mixed        $customError   Custom error message.
 * @property string|null  $help          Help text.
 * @property string|null  $hint          Hint text (alias for help).
 * @property array        $existingFiles Existing files [{name, url, size?, type?}].
 *
 * ## Color Presets
 * @property bool $primary   Primary color (alias for beartropy).
 * @property bool $beartropy Beartropy color.
 * @property bool $red       Red color.
 * @property bool $orange    Orange color.
 * @property bool $amber     Amber color.
 * @property bool $yellow    Yellow color.
 * @property bool $lime      Lime color.
 * @property bool $green     Green color.
 * @property bool $emerald   Emerald color.
 * @property bool $teal      Teal color.
 * @property bool $cyan      Cyan color.
 * @property bool $sky       Sky color.
 * @property bool $blue      Blue color.
 * @property bool $indigo    Indigo color.
 * @property bool $violet    Violet color.
 * @property bool $purple    Purple color.
 * @property bool $fuchsia   Fuchsia color.
 * @property bool $pink      Pink color.
 * @property bool $rose      Rose color.
 * @property bool $slate     Slate color.
 * @property bool $gray      Gray color.
 * @property bool $zinc      Zinc color.
 * @property bool $neutral   Neutral color.
 * @property bool $stone     Stone color.
 */
class FileDropzone extends BeartropyComponent
{
    /**
     * Create a new FileDropzone component instance.
     *
     * @param string|null  $id            Component ID.
     * @param string|null  $name          Input name.
     * @param string|null  $label         Label text.
     * @param string|null  $color         Color preset.
     * @param bool         $multiple      Allow multiple files.
     * @param string|null  $accept        File accept attribute.
     * @param int|null     $maxFileSize   Max file size in bytes.
     * @param int|null     $maxFiles      Max number of files.
     * @param string|null  $placeholder   Override empty-state text.
     * @param bool         $preview       Show image previews.
     * @param bool         $clearable     Allow clearing selection.
     * @param bool         $disabled      Disabled state.
     * @param mixed        $customError   Custom error message.
     * @param string|null  $help          Help text.
     * @param string|null  $hint          Hint text (alias for help).
     * @param array<int, array{name: string, url: string, size?: int, type?: string}> $existingFiles Existing files.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $color = null,
        public bool $multiple = true,
        public ?string $accept = null,
        public ?int $maxFileSize = null,
        public ?int $maxFiles = null,
        public ?string $placeholder = null,
        public bool $preview = true,
        public bool $clearable = true,
        public bool $disabled = false,
        public mixed $customError = null,
        public ?string $help = null,
        public ?string $hint = null,
        public array $existingFiles = [],
    ) {
        $this->id = $id ?? ('beartropy-filedropzone-' . uniqid());
        $this->name = $name ?? $this->id;
    }

    /**
     * Format bytes to a human-readable string.
     */
    public function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }
        if ($bytes < 1073741824) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        return round($bytes / 1073741824, 1) . ' GB';
    }

    /**
     * Build the accept/size hint string shown below the dropzone icon.
     */
    public function getAcceptHint(): string
    {
        $parts = [];

        if ($this->accept) {
            $parts[] = strtoupper(str_replace(['.', ','], ['', ', '], $this->accept));
        }

        if ($this->maxFileSize) {
            $parts[] = 'Max ' . $this->formatBytes($this->maxFileSize);
        }

        return implode(' — ', $parts);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::file-dropzone');
    }
}

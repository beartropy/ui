<?php

namespace Beartropy\Ui\Components;

/**
 * Dialog Component.
 *
 * Single-instance, event-driven alert/confirm dialog.
 * Place once per page; controlled entirely through the `bt-dialog` browser event
 * dispatched by the Livewire `HasDialogs` trait or the JS `dialog()` helper.
 *
 * All runtime state (type, title, description, icon, accept/reject buttons)
 * is managed by the Alpine `btDialog` module — not by Blade props.
 */
class Dialog extends BeartropyComponent
{
    /**
     * @param  string|null  $size  Default panel width (sm, md, lg, xl, 2xl). Per-dialog events can override.
     */
    public function __construct(
        public ?string $size = 'md'
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::dialog');
    }
}

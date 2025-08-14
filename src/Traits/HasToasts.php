<?php

namespace Beartropy\Ui\Traits;

use Illuminate\Support\Str;

trait HasToasts
{
    protected $_beartropy_toast_proxy;

    public function toast()
    {
        if (!isset($this->_beartropy_toast_proxy)) {
            $this->_beartropy_toast_proxy = new class($this) {
                protected $component;
                public function __construct($component) {
                    $this->component = $component;
                }
                public function success($title, $message = '', $duration = 4000, $position = null) {
                    return $this->send('success', $title, $message, $duration, $position);
                }
                public function error($title, $message = '', $duration = 4000, $position = null) {
                    return $this->send('error', $title, $message, $duration, $position);
                }
                public function info($title, $message = '', $duration = 4000, $position = null) {
                    return $this->send('info', $title, $message, $duration, $position);
                }
                public function warning($title, $message = '', $duration = 4000, $position = null) {
                    return $this->send('warning', $title, $message, $duration, $position);
                }
                protected function send($type, $title, $message, $duration, $position)
                {
                    $payload = [
                        'id'      => (string) \Illuminate\Support\Str::uuid(),
                        'type'    => $type,
                        'title'   => $title,
                        'message' => $message,
                        'single'  => (!$message) ? true : false,
                        'duration'=> $duration,
                        'position'=> $position ?? null,
                    ];
                    $this->component->dispatch('beartropy-add-toast', $payload);
                }
            };
        }
        return $this->_beartropy_toast_proxy;
    }
}

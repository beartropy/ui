<?php

namespace Beartropy\Ui\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasToasts.
 *
 * Provides a fluent interface for dispatching toast notifications from Livewire components.
 */
trait HasToasts
{
    /** @var object|null Proxy object for fluent interface */
    protected $_beartropy_toast_proxy;

    /**
     * Get the toast proxy instance.
     *
     * Allows usage like `$this->toast()->success('Message')`.
     *
     * @return object Proxy instance with success(), error(), info(), warning() methods.
     */
    public function toast()
    {
        if (!isset($this->_beartropy_toast_proxy)) {
            $this->_beartropy_toast_proxy = new class($this) {
                protected $component;
                public function __construct($component)
                {
                    $this->component = $component;
                }

                /**
                 * Send a success toast.
                 * @param string $title
                 * @param string $message
                 * @param int $duration
                 * @param string|null $position
                 */
                public function success($title, $message = '', $duration = 4000, $position = null)
                {
                    return $this->send('success', $title, $message, $duration, $position);
                }

                /**
                 * Send an error toast.
                 * @param string $title
                 * @param string $message
                 * @param int $duration
                 * @param string|null $position
                 */
                public function error($title, $message = '', $duration = 4000, $position = null)
                {
                    return $this->send('error', $title, $message, $duration, $position);
                }

                /**
                 * Send an info toast.
                 * @param string $title
                 * @param string $message
                 * @param int $duration
                 * @param string|null $position
                 */
                public function info($title, $message = '', $duration = 4000, $position = null)
                {
                    return $this->send('info', $title, $message, $duration, $position);
                }

                /**
                 * Send a warning toast.
                 * @param string $title
                 * @param string $message
                 * @param int $duration
                 * @param string|null $position
                 */
                public function warning($title, $message = '', $duration = 4000, $position = null)
                {
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
                        'duration' => $duration,
                        'position' => $position ?? null,
                    ];
                    $this->component->dispatch('beartropy-add-toast', $payload);
                }
            };
        }
        return $this->_beartropy_toast_proxy;
    }
}

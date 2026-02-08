<?php

namespace App\Traits;

trait WithToast
{
    public function toast(string $message, array $options = [])
    {
        $this->dispatch('toast', [
            'message' => $message,
            'options' => $options,
        ]);
    }
    public function toastInfo(string $message, array $options = [])
    {
        $this->toast($message, [
            ...[
                'type' => 'info',
            ],
            ...$options,
        ]);
    }
    public function toastSuccess(string $message, array $options = [])
    {
        $this->toast($message, [
            ...[
                'type' => 'success',
            ],
            ...$options,
        ]);
    }
    public function toastWarning(string $message, array $options = [])
    {
        $this->toast($message, [
            ...[
                'type' => 'warning',
            ],
            ...$options,
        ]);
    }
    public function toastError(string $message, array $options = [])
    {
        $this->toast($message, [
            ...[
                'type' => 'error',
            ],
            ...$options,
        ]);
    }

    public function addSuccess($name, $message)
    {
        session()->flash($name, $message);
    }
    public function addSuccesses(array $array)
    {
        foreach ($array as $key => $message) {
            $this->addSuccess($key, $message);
        }
    }

    public function status($message)
    {
        session()->flash('status', $message);
    }
    public function addErrors(array $errors)
    {
        foreach ($errors as $key => $message) {
            $this->addError($key, $message);
        }
    }
}

<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Mail settings')] class extends SettingsPage {
    public $default;
    public $from;
    public $show = [];
    public $mailers = [];

    public function prefix()
    {
        return 'mail';
    }

    public function mount()
    {
        parent::mount();
        $this->loadShow();
    }

    public function updatedDefault()
    {
        $this->loadShow();
    }

    public function loadShow()
    {
        $show = [];
        foreach ($this->mailers as $key => $value) {
            $show[$key] = $this->default === $key ? true : false;
        }
        $this->show = $show;
    }

    #[Computed()]
    public function cardTitle(string $key)
    {
        $title = Str::title("{$key} Settings");
        return Str::transliterate($title);
    }
    public function rules()
    {
        return [
            'default' => ['required', 'string', Rule::in(mailer_values())],
            'from' => ['required', 'array'],
            'from.address' => ['required', 'string', 'email'],
            'from.name' => ['required', 'string', 'max:255'],
            'mailers' => ['required', 'array'],
        ];
    }
};

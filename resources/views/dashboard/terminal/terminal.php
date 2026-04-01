<?php

use App\Livewire\Components\DashboardPage;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;


new #[Title('Terminal')] class extends DashboardPage
{
    public $command = '';
    public $output = '';
    public $history = [];
    protected $converter;
    public $search = '';

    protected $rules = [
        'command' => 'required|string',
    ];

    #[Computed()]
    public function commands()
    {
        return collect(Artisan::all())->map(function ($command, $name) {
            return [
                'name' => $name,
                'description' => $command->getDescription(),
            ];
        })->filter(function ($command) {
            if (empty($this->search)) {
                return true;
            }
            return str_contains(strtolower($command['name']), strtolower($this->search)) ||
                str_contains(strtolower($command['description']), strtolower($this->search));
        })->sortBy('name');
    }
    public function addOutput($output)
    {
        if (!$this->converter) {
            $this->converter = new AnsiToHtmlConverter();
        }
        $content = $this->converter->convert($output);
        $this->stream(to: 'output', content: $content);
        /*foreach (preg_split('/\r\n|\r|\n/', trim($output)) as $line) {
            $content = $type === 'error' ? "<div class='text-red-500'>$line</div>" : "<div>$line</div>";
            $this->stream(to: 'output', content: $content);
            $this->js('scrollToBottom');
        }*/
    }
    public function addLine($line)
    {
        $this->output .= "\n";
        $this->output .= $line;
    }
    public function submitCommand()
    {
        $this->js('$wire.runCommand()');
    }
    public function runCommand()
    {
        $this->authorize('manage_settings');
        $this->validate();
        $command = $this->pull('command');
        $this->history[] = $command;
        $this->addOutput("> php artisan {$command}");
        $this->addOutput("\n");
        try {
            Artisan::call($command);
            $this->addOutput(Artisan::output());
        } catch (\Exception $e) {
            $this->command = $command;
            $this->addOutput($e->getMessage());
        }
    }
};

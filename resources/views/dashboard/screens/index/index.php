<?php

use App\Models\Screen;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Computed()]
    public function screens()
    {
        if (current_user_has_role('admin')) {
            return Screen::paginate();
        } else {
            return Screen::withUser(current_user_id())->paginate();
        }
    }
    public function edit(Screen $screen)
    {
        $this->redirect(route('dashboard.screens.edit', $screen));
        // dd($screen->toArray());
    }
    public function delete(Screen $screen)
    {
        $screen->delete();
    }
    /* public function render()
    {
        $screens = Screen::paginate();
        return view('dashboard.screens.index', [
            'screens' => $screens,
        ]);
    } */
};

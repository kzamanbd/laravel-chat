<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\View\View;

class RecentUserList extends Component
{

    public function getUsersProperty()
    {
        return User::query()->whereNot('id', auth()->id())->get();
    }


    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.recent-user-list');
    }
}

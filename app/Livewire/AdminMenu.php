<?php

namespace App\Livewire;

use Livewire\Component;

class AdminMenu extends Component
{
    public $keyword;
    public function render()
    {
        return view('livewire.admin-menu');
    }
}

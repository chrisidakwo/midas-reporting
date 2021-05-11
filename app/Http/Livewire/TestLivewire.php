<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TestLivewire extends Component
{
	public $name = 'Kelly';

    public function render()
    {
        return view('livewire.test-livewire');
    }
}

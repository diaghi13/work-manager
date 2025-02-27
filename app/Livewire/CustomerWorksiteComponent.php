<?php

namespace App\Livewire;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CustomerWorksiteComponent extends Component
{
    #[Reactive]
    public ?Customer $customer = null;

    public function render()
    {
        return view('livewire.customer-worksite-component');
    }
}

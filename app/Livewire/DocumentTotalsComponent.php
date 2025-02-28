<?php

namespace App\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class DocumentTotalsComponent extends Component
{
    #[Reactive]
    public string $netPrice = "0";
    #[Reactive]
    public string $vatPrice = "0";
    #[Reactive]
    public string $grossPrice = "0";
    #[Reactive]
    public string $itemsQuantity = "0";

    public function render()
    {
        return view('livewire.document-totals-component');
    }
}

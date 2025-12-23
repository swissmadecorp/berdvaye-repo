<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cart;

class Header extends Component
{
    public $countCart = 0;

    #[On('refresh-cart-count')]
    public function inCartQty() {
        $this->countCart = Cart::count();
    }

    public function render()
    {
        $this->inCartQty();
        return view('livewire.header',['activePage' => 'Sculptures']);
    }
}

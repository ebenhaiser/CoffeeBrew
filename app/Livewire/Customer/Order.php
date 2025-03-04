<?php

namespace App\Livewire\Customer;

use App\Models\Menu;
use Livewire\Component;

class Order extends Component
{
    public $total_price = 0;
    public $itemQuantity = []; // Deklarasi array untuk menyimpan jumlah item
    public $keyword = null; // Deklarasi keyword untuk pencarian

    public function calculateTotalPrice()
    {
        $this->total_price = 0;
        foreach ($this->itemQuantity as $menuId => $quantity) {
            $menu = Menu::find($menuId);
            if ($menu) {
                $this->total_price += $menu->price * $quantity;
            }
        }
    }

    public function plusItem($menuId)
    {
        $this->itemQuantity[$menuId] = isset($this->itemQuantity[$menuId]) ? $this->itemQuantity[$menuId] + 1 : 1;
        $this->calculateTotalPrice();
    }

    public function minusItem($menuId)
    {
        if (isset($this->itemQuantity[$menuId]) && $this->itemQuantity[$menuId] > 0) {
            $this->itemQuantity[$menuId]--;
            $this->calculateTotalPrice();
        }
    }

    public function render()
    {
        if ($this->keyword != null) {
            $items = Menu::where('name', 'like', '%' . $this->keyword . '%')
                ->orWhere('description', 'like', '%' . $this->keyword . '%')
                ->orWhereHas('category', function ($query) {
                    $query->where('name', 'like', '%' . $this->keyword . '%');
                })
                ->get();
        } else {
            $items = Menu::get();
        }

        return view('livewire.customer.order', compact('items'));
    }
}

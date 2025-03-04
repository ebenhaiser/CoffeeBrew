<?php

namespace App\Livewire\Customer;

use App\Models\Menu;
use App\Models\Table;
use Livewire\Component;

class Order extends Component
{
    public $total_price = 0;
    public $itemQuantity = []; // Deklarasi array untuk menyimpan jumlah item
    public $keyword = null; // Deklarasi keyword untuk pencarian
    public $slug, $table_number = '···';

    public function mount($slug)
    {
        $this->slug = $slug;
        $table = Table::where('qr_code', $slug)->first();
        if ($table) {
            $this->table_number = $table->table_number;
        } else {
            $this->table_number = 'N/A';
        }
    }
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

    public function getOrderedItems()
    {
        $orderedItems = [];

        foreach ($this->itemQuantity as $menuId => $quantity) {
            if ($quantity > 0) {
                $menu = Menu::find($menuId);
                if ($menu) {
                    $orderedItems[] = [
                        'name' => $menu->name,
                        'image' => $menu->image,
                        'price' => $menu->price,
                        'quantity' => $quantity,
                        'subtotal' => $menu->price * $quantity,
                    ];
                }
            }
        }

        return $orderedItems;
    }
}

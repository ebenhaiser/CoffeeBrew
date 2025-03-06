<?php

namespace App\Livewire\Customer;

use App\Models\Menu;
use App\Models\Order as ModelsOrder;
use App\Models\OrderItem;
use App\Models\Table;
use Livewire\Component;

class Order extends Component
{
    public $total_price = 0;
    public $table_id;
    public $itemQuantity = []; // Deklarasi array untuk menyimpan jumlah item
    public $orderedItems = [];
    public $keyword = null; // Deklarasi keyword untuk pencarian
    public $slug, $table_number = '···';
    public $order_code;

    public function mount($slug)
    {
        $this->slug = $slug;
        $table = Table::where('qr_code', $slug)->first();
        if ($table) {
            $this->table_id = $table->id;
            $this->table_number = $table->table_number;
        } else {
            $this->table_id = null;
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
        $this->orderedItems = [];
        foreach ($this->itemQuantity as $menuId => $quantity) {
            if ($quantity > 0) {
                $menu = Menu::find($menuId);
                if ($menu) {
                    $this->orderedItems[] = [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'image' => $menu->image,
                        'price' => $menu->price,
                        'quantity' => $quantity,
                        'subtotal' => $menu->price * $quantity,
                    ];
                }
            }
        }
    }

    public function createOrder()
    {
        $this->order_code = 'ORDCB-' . $this->table_number . '-' . time();
        $createOrder = ModelsOrder::create([
            'order_code' => $this->order_code,
            'table_id' => $this->table_id,
            'total_price' => $this->total_price,
            'status' => 0,
        ]);
        $order_id = $createOrder->id;
        foreach ($this->orderedItems as $item) {
            OrderItem::create([
                'order_id' => $order_id,
                'menu_id' => $item['id'],
                'quantity' => $item['quantity'],
                'subtotal_price' => $item['subtotal'],
            ]);
        }
    }

    public function clear()
    {
        $this->itemQuantity = [];
        $this->total_price = 0;
        $this->keyword = null;
        $this->orderedItems = [];
        $this->order_code = null;
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Menu;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Order as ModelsOrder;
use App\Models\OrderItem;
use App\Models\Table;

class Order extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // theme pagination
    public $dataId, $order_code, $table_id, $total_price, $status, $amount_paid, $amount_change; //order data
    public $orderItems = []; //order items data
    public $keyword; //search keyword
    public $sortColumn = 'created_at', $sortDirection = 'desc'; //for sorting
    public $editData = false; //update data for edit data
    public $deletingName; //for getting name of deleted data
    public $bulkDelete = false, $data_selected_id = []; //for bulk delete
    public $rows = [];
    public $menus, $menuCategories;

    public function clear()
    {
        $this->dataId = null;
        $this->order_code = null;
        $this->table_id = null;
        $this->total_price = null;
        $this->status = null;
        $this->amount_paid = null;
        $this->amount_change = null;
        $this->orderItems = [];
        $this->keyword = null;
        $this->sortColumn = 'created_at';
        $this->editData = false;
        $this->deletingName = null;
        $this->bulkDelete = false;
        $this->data_selected_id = [];
        $this->rows = [];

        // Hapus session flash message
        session()->forget('message');

        // Reset validasi error
        $this->resetValidation();
    }

    public function activateBulkDelete()
    {
        $this->bulkDelete = true;
    }

    public function sort($columnName)
    {
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function mount()
    {
        $this->menuCategories = Category::all();
        $this->menus = Menu::all();
    }

    public function render()
    {
        if ($this->keyword != null) {
            $items = ModelsOrder::where('order_code', 'like', '%' . $this->keyword . '%')
                ->orWhereHas('table', function ($query) {
                    $query->where('table_number', 'like', '%' . $this->keyword . '%');
                })
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        } else {
            $items = ModelsOrder::orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        }

        $tables = Table::get();

        $this->amount_change = intval($this->amount_paid) - $this->total_price;
        if ($this->amount_change < 0) {
            $this->amount_change = 0;
        }

        return view('livewire.admin.order', compact('items', 'tables'));
    }

    public function validateAdd()
    {
        if ($this->editData == true) {
            if ($this->status == 0) {
                $this->validate([
                    'table_id' => 'required',
                    'amount_paid' => 'required|numeric|gte:total_price', // amount_paid harus >= total_price
                ], [
                    'amount_paid.gte' => 'Insufficient pay',
                ]);
            }
        } else {
            if ($this->status == 1) {
                $this->validate([
                    'table_id' => 'required',
                    'amount_paid' => 'required|numeric|gte:total_price', // amount_paid harus >= total_price
                ], [
                    'amount_paid.gte' => 'Insufficient pay',
                ]);
            } else {
                $this->validate([
                    'table_id' => 'required',
                ]);
            }
        }
    }

    // add data
    public function addRow()
    {
        $this->rows[] = [
            'category_id' => '',
            'menu_id' => '',
            'price' => 0,
            'quantity' => 1,
            'subtotal_price' => 0
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Reset index agar tidak ada index yang lompat
    }


    public function getFilteredMenus($categoryId)
    {
        return $categoryId ? Menu::where('category_id', $categoryId)->get() : collect();
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'rows') && $this->editData == false) {
            $parts = explode('.', $propertyName);
            if (count($parts) == 3) {
                $rowIndex = $parts[1];
                $field = $parts[2];

                // Jika menu_id berubah, update harga
                if ($field === 'menu_id' && !empty($this->rows[$rowIndex]['menu_id'])) {
                    $menu = Menu::find($this->rows[$rowIndex]['menu_id']);
                    if ($menu) {
                        $this->rows[$rowIndex]['price'] = $menu->price;
                        $this->rows[$rowIndex]['subtotal_price'] = $menu->price * $this->rows[$rowIndex]['quantity'];
                    }
                }

                // Jika quantity berubah, update subtotal
                if ($field === 'quantity') {
                    $this->rows[$rowIndex]['subtotal_price'] = intval($this->rows[$rowIndex]['price']) * intval($this->rows[$rowIndex]['quantity']);
                }
            }
        }
    }

    public function getTotalPrice()
    {
        $this->total_price = 0;
        foreach ($this->rows as $row) {
            $this->total_price += $row['subtotal_price'];
        }

        return $this->total_price;
    }


    public function store()
    {
        if ($this->amount_paid = null || $this->amount_paid <= 0) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }
        $this->validateAdd();
        $table = Table::find($this->table_id);
        $this->order_code = 'ORDCB-' . $table->table_number . '-' . time();
        $createOrder = ModelsOrder::create([
            'order_code' => $this->order_code,
            'table_id' => $this->table_id,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'amount_paid' => $this->amount_paid,
            'amount_change' => $this->amount_change,
        ]);

        $order_id = $createOrder->id;

        foreach ($this->rows as $row) {
            OrderItem::create([
                'order_id' => $order_id,
                'menu_id' => $row['menu_id'],
                'quantity' => $row['quantity'],
                'subtotal_price' => $row['subtotal_price'],
            ]);
        }

        session()->flash('successToast', "Order '" . $this->order_code . "' was <span class='badge bg-label-success'>created</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }
    // END add data


    // for edit/update data
    public function edit($id)
    {
        $data = ModelsOrder::with('items.menu.category')->find($id);
        $this->dataId = $id;
        $this->order_code = $data->order_code;
        $this->table_id = $data->table_id;
        $this->total_price = $data->total_price;
        $this->status = $data->status;
        $this->amount_paid = $data->amount_paid;
        $this->amount_change = $data->amount_change;
        $this->editData = true;

        // Ambil order items
        // Ambil order items langsung dari relasi
        $this->orderItems = $data->items;
    }

    public function update()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        $data = ModelsOrder::findOrFail($this->dataId);
        // if ($this->status == 0) {
        //     $status = 1;
        // } else {
        //     $status = $this->status;
        // }

        if ($this->status == -1) {
            $data->update([
                'table_id' => $this->table_id,
                'status' => -1,
            ]);
        } elseif ($this->status == 0) {
            $data->update([
                'table_id' => $this->table_id,
                'status' => 1,
                'amount_paid' => $this->amount_paid,
                'amount_change' => $this->amount_change,
            ]);
        }
        session()->flash('successToast', "Order '" . $this->order_code . "' was <span class='badge bg-label-success'>edited</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }
    // END for edit/update data

    public function delete()
    {
        if (count($this->data_selected_id) == 1 || $this->dataId != '') {
            $id = $this->dataId;
            $deletedData = ModelsOrder::find($id);
            $deletedName = $deletedData->order_code;
            ModelsOrder::find($id)->delete();
            session()->flash('successToast', "Order '" . $deletedName . "' was <span class='badge bg-label-danger'>Deleted</span>");
        } elseif (count($this->data_selected_id) >= 2) {
            foreach ($this->data_selected_id as $id) {
                ModelsOrder::find($id)->delete();
            }
            session()->flash('successToast', "'" . count($this->data_selected_id) . "' Orders were <span class='badge bg-label-danger'>Deleted</span>");
        }
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != '') {
            $this->dataId = $id;
            $this->deletingName = ModelsOrder::find($id)->order_code;
        } elseif (count($this->data_selected_id) == 1) {
            $this->dataId = $this->data_selected_id[0];
            $this->deletingName = ModelsOrder::find($this->dataId)->order_code;
        }
    }
}

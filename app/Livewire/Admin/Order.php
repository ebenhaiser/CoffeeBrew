<?php

namespace App\Livewire\Admin;

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

        if ($this->editData == true) {
            $this->amount_change = intval($this->amount_paid) - $this->total_price;
            if ($this->amount_change < 0) {
                $this->amount_change = 0;
            }
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
        }
    }

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
        if ($this->status == 0) {
            $status = 1;
        } else {
            $status = $this->status;
        }
        $data->update([
            'table_id' => $this->table_id,
            'status' => $status,
            'amount_paid' => $this->amount_paid,
            'amount_change' => $this->amount_change,
        ]);
        session()->flash('successToast', "Order '" . $this->order_code . "' was <span class='badge bg-label-success'>edited</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }

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
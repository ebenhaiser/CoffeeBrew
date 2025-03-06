<?php

namespace App\Livewire\Admin;

use App\Models\Order as ModelsOrder;
use Livewire\Component;
use Livewire\WithPagination;

class Order extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // theme pagination
    public $orderId, $order_code, $qr_code; //order data
    public $keyword; //search keyword
    public $sortColumn = 'created_at', $sortDirection = 'desc'; //for sorting
    public $updateData = false; //update data for edit data
    public $deletingName; //for getting name of deleted data
    public $bulkDelete = false, $data_selected_id = []; //for bulk delete
    // public function clear()
    // {
    //     $this->orderId = null;
    //     $this->order_code = null;
    //     $this->qr_code = null;
    //     $this->keyword = null;
    //     $this->sortColumn = 'table_number';
    //     $this->updateData = false;
    //     $this->deletingName = null;
    //     $this->bulkDelete = false;
    //     $this->data_selected_id = [];

    //     // Hapus session flash message
    //     session()->forget('message');

    //     // Reset validasi error
    //     $this->resetValidation();
    // }

    // public function activateBulkDelete()
    // {
    //     $this->bulkDelete = true;
    // }

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

        return view('livewire.admin.order', compact('items'));
    }
}

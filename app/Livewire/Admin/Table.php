<?php

namespace App\Livewire\Admin;

use App\Models\Table as ModelsTable;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Table extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // theme pagination
    public $tableId, $table_number, $qr_code; //table data
    public $keyword; //search keyword
    public $sortColumn = 'table_number', $sortDirection = 'asc'; //for sorting
    public $updateData = false; //update data for edit data
    public $deletingName; //for getting name of deleted data
    public $bulkDelete = false, $data_selected_id = []; //for bulk delete

    public function clear()
    {
        $this->tableId = null;
        $this->table_number = null;
        $this->qr_code = null;
        $this->keyword = null;
        $this->sortColumn = 'table_number';
        $this->updateData = false;
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
            $items = ModelsTable::where('table_number', 'like', '%' . $this->keyword . '%')
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        } else {
            $items = ModelsTable::orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        }

        return view('livewire.admin.table', compact('items'));
    }

    public function validateAdd()
    {
        if ($this->updateData == false) {
            $this->validate([
                'table_number' => 'required|unique:tables,table_number',
                'qr_code' => 'required|unique:tables,qr_code',
            ]);
        } else {
            $this->validate([
                'table_number' => ['required', Rule::unique('tables', 'table_number')->ignore($this->tableId)],
            ]);
        }
    }

    public function store()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        // Simpan gambar jika ada
        ModelsTable::create([
            'table_number' => $this->table_number,
            'qr_code' => $this->qr_code,
        ]);
        // Simpan data ke database
        session()->flash('successToast', "New Table '" . $this->table_number . "' was <span class='badge bg-label-success'>created</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }

    public function edit($id)
    {
        $table = ModelsTable::find($id);
        $this->tableId = $id;
        $this->table_number = $table->table_number;
        $this->qr_code = $table->qr_code;
        $this->updateData = true;
    }

    public function update()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        $table = ModelsTable::findOrFail($this->tableId);
        $table->update([
            'table_number' => $this->table_number,
        ]);
        session()->flash('successToast', "Table number '" . $this->table_number . "' was <span class='badge bg-label-success'>edited</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }

    public function delete()
    {
        if (count($this->data_selected_id) == 1 || $this->tableId != '') {
            $id = $this->tableId;
            $deletedData = ModelsTable::find($id);
            $deletedName = $deletedData->table_number;
            ModelsTable::find($id)->delete();
            session()->flash('successToast', "Table number '" . $deletedName . "' was <span class='badge bg-label-danger'>Deleted</span>");
        } elseif (count($this->data_selected_id) >= 2) {
            foreach ($this->data_selected_id as $id) {
                ModelsTable::find($id)->delete();
            }
            session()->flash('successToast', "'" . count($this->data_selected_id) . "' Tables were <span class='badge bg-label-danger'>Deleted</span>");
        }
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != '') {
            $this->tableId = $id;
            $this->deletingName = ModelsTable::find($id)->table_number;
        } elseif (count($this->data_selected_id) == 1) {
            $this->tableId = $this->data_selected_id[0];
            $this->deletingName = ModelsTable::find($this->tableId)->table_number;
        }
    }
}

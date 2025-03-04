<?php

namespace App\Livewire\Admin;

use App\Models\Category as ModelsCategory;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Category extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // theme pagination
    public $dataId, $name, $slug; //table data
    public $oldName;
    public $keyword; //search keyword
    public $sortColumn = 'name', $sortDirection = 'asc'; //for sorting
    public $updateData = false; //update data for edit data
    public $deletingName; //for getting name of deleted data
    public $bulkDelete = false, $data_selected_id = []; //for bulk delete

    public function clear()
    {
        $this->dataId = null;
        $this->name = null;
        $this->slug = null;
        $this->oldName = null;
        $this->keyword = null;
        $this->sortColumn = 'name';
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
            $items = ModelsCategory::where('name', 'like', '%' . $this->keyword . '%')
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        } else {
            $items = ModelsCategory::orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        }
        $this->slug = Str::slug($this->name);
        return view('livewire.admin.category', compact('items'));
    }

    public function validateAdd()
    {
        if ($this->updateData == false) {
            $this->validate([
                'name' => 'required|unique:categories,name',
            ]);
        } else {
            $this->validate([
                'name' => ['required', Rule::unique('categories', 'name')->ignore($this->dataId)],
            ]);
        }
    }

    public function store()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        // Simpan gambar jika ada
        ModelsCategory::create([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);
        // Simpan data ke database
        session()->flash('successToast', "New menu category '" . $this->name . "' was <span class='badge bg-label-success'>created</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }
    public function edit($id)
    {
        $data = ModelsCategory::find($id);
        $this->dataId = $id;
        $this->name = $data->name;
        $this->oldName = $data->name;
        $this->slug = $data->qr_code;
        $this->updateData = true;
    }

    public function update()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        $data = ModelsCategory::findOrFail($this->dataId);
        $data->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);
        session()->flash('successToast', "Menu category '" . $this->name . "' was <span class='badge bg-label-success'>edited</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }

    public function delete()
    {
        if (count($this->data_selected_id) == 1 || $this->dataId != '') {
            $id = $this->dataId;
            $deletedData = ModelsCategory::find($id);
            $deletedName = $deletedData->name;
            ModelsCategory::find($id)->delete();
            session()->flash('successToast', "Menu category '" . $deletedName . "' was <span class='badge bg-label-danger'>Deleted</span>");
        } elseif (count($this->data_selected_id) >= 2) {
            foreach ($this->data_selected_id as $id) {
                ModelsCategory::find($id)->delete();
            }
            session()->flash('successToast', "'" . count($this->data_selected_id) . "' Menu categories were <span class='badge bg-label-danger'>Deleted</span>");
        }
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != '') {
            $this->dataId = $id;
            $this->deletingName = ModelsCategory::find($id)->name;
        } elseif (count($this->data_selected_id) == 1) {
            $this->dataId = $this->data_selected_id[0];
            $this->deletingName = ModelsCategory::find($this->dataId)->name;
        }
    }
}

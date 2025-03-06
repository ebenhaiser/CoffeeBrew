<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use App\Models\Menu as ModelsMenu;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class Menu extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap'; // theme pagination
    public $menuId, $name, $category_id, $description, $price, $stock, $image; //table data
    public $imagePath = null;
    public $keyword; //search keyword
    public $sortColumn = 'name', $sortDirection = 'asc'; //for sorting
    public $updateData = false; //update data for edit data
    public $deletingName; //for getting name of deleted data
    public $bulkDelete = false, $data_selected_id = []; //for bulk delete

    public function sort($columnName)
    {
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }
    public function render()
    {
        if ($this->keyword != null) {
            $menus = ModelsMenu::where('name', 'like', '%' . $this->keyword . '%')
                ->orWhere('description', 'like', '%' . $this->keyword . '%')
                ->orWhereHas('category', function ($query) {
                    $query->where('name', 'like', '%' . $this->keyword . '%');
                })
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        } else {
            $menus = ModelsMenu::orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        }

        $categories = Category::get();

        return view('livewire.admin.menu', compact('menus', 'categories'));
    }

    public function validateAdd()
    {
        if ($this->updateData == false) {
            $this->validate([
                'name' => 'required|unique:menus,name',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
            ]);
        } else {
            $this->validate([
                'name' => ['required', Rule::unique('menus', 'name')->ignore($this->menuId)],
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
            ]);
        }
    }

    public function store()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        // Simpan gambar jika ada

        if ($this->image) {
            $fileName = Str::slug($this->name) . '.' . $this->image->getClientOriginalExtension();
            $imagePath = $this->image->storeAs('img/menu', $fileName, 'public');
            ModelsMenu::create([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $imagePath, // Simpan path gambar ke database
            ]);
        } else {
            ModelsMenu::create([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock, // Simpan path gambar ke database
            ]);
        }

        // Simpan data ke database

        session()->flash('successToast', "New Menu '" . $this->name . "' was <span class='badge bg-label-success'>created</span>");
        $this->clear();
        $this->dispatch('closeAllModals'); // Livewire 3 pakai dispatch, bukan emit

    }


    public function edit($id)
    {
        $menu = ModelsMenu::find($id);
        $this->menuId = $id;
        $this->name = $menu->name;
        $this->category_id = $menu->category_id;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->stock = $menu->stock;
        $this->imagePath = $menu->image;

        $this->updateData = true;
    }

    public function update()
    {
        $this->validateAdd(); // Validasi input terlebih dahulu

        // Ambil data menu lama berdasarkan ID
        $menu = ModelsMenu::findOrFail($this->menuId);

        // Set default image path dari data lama
        $imagePath = $menu->image;

        // Cek apakah ada file baru yang diupload
        if ($this->image) {
            // Jika ada gambar lama, hapus dari penyimpanan
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            // Simpan gambar baru dengan nama yang unik
            $fileName = Str::slug($this->name) . '.' . $this->image->getClientOriginalExtension();
            $imagePath = $this->image->storeAs('img/menu', $fileName, 'public');
            // Update data di database
            $menu->update([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $imagePath, // Simpan path gambar ke database (tetap gambar lama kalau tidak upload baru)
            ]);
        } else {
            $menu->update([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
            ]);
        }



        session()->flash('successToast', "Menu '" . $this->name . "' was <span class='badge bg-label-info'>updated</span>");
        $this->clear();
        $this->dispatch('closeAllModals'); // Livewire 3 pakai dispatch, bukan emit
    }


    public function delete()
    {
        if (count($this->data_selected_id) == 1 || $this->menuId != '') {
            $id = $this->menuId;
            $deletedMenu = ModelsMenu::find($id);
            $deletedName = $deletedMenu->name;
            if ($deletedMenu->image && Storage::disk('public')->exists($deletedMenu->image)) {
                Storage::disk('public')->delete($deletedMenu->image);
            }
            ModelsMenu::find($id)->delete();
            session()->flash('successToast', "Menu '" . $deletedName . "' was <span class='badge bg-label-danger'>Deleted</span>");
        } elseif (count($this->data_selected_id) >= 2) {
            foreach ($this->data_selected_id as $id) {
                $deletedMenu = ModelsMenu::find($id);
                if ($deletedMenu->image && Storage::disk('public')->exists($deletedMenu->image)) {
                    Storage::disk('public')->delete($deletedMenu->image);
                }
                ModelsMenu::find($id)->delete();
            }
            session()->flash('successToast', "'" . count($this->data_selected_id) . "' Menus were <span class='badge bg-label-danger'>Deleted</span>");
        }
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != '') {
            $this->menuId = $id;
            $this->deletingName = ModelsMenu::find($id)->name;
        } elseif (count($this->data_selected_id) == 1) {
            $this->menuId = $this->data_selected_id[0];
            $this->deletingName = ModelsMenu::find($this->menuId)->name;
        }
    }

    public function activateBulkDelete()
    {
        $this->bulkDelete = true;
    }

    public function clear()
    {
        $this->menuId = null;
        $this->name = null;
        $this->category_id = null;;
        $this->description = null;;
        $this->price = null;
        $this->stock = null;
        $this->image = null;

        $this->sortColumn = 'name';
        $this->updateData = false;
        $this->deletingName = null;
        $this->data_selected_id = [];
        $this->bulkDelete = false;
        $this->imagePath = null;

        // Hapus session flash message
        session()->forget('message');

        // Reset validasi error
        $this->resetValidation();
    }
}

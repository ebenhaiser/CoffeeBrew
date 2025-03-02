<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class AdminMenu extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // theme pagination
    public $menuId, $name, $category_id, $description, $price, $stock, $image; //table data
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
            $menus = Menu::where('name', 'like', '%' . $this->keyword . '%')
                ->orWhere('description', 'like', '%' . $this->keyword . '%')
                ->orWhereHas('category', function ($query) {
                    $query->where('name', 'like', '%' . $this->keyword . '%');
                })
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        } else {
            $menus = Menu::orderBy($this->sortColumn, $this->sortDirection)
                ->paginate(10);
        }

        $categories = Category::get();

        return view('livewire.admin-menu', compact('menus', 'categories'));
    }

    public function validateAdd()
    {
        if ($this->updateData == false) {
            $this->validate([
                'name' => 'required|unique:menus,name',
                'price' => 'required|numeric',
                'category_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
            ]);
        } else {
            $this->validate([
                'name' => ['required', Rule::unique('menus', 'name')->ignore($this->menuId)],
                'price' => 'required|numeric',
                'category_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
            ]);
        }
    }

    public function store()
    {
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        Menu::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
        ]);

        session()->flash('successToast', "New Menu '" . $this->name . "' was <span class='badge bg-label-success'>created</span>");
        $this->reset();
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        $this->menuId = $id;
        $this->name = $menu->name;
        $this->category_id = $menu->category_id;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->stock = $menu->stock;

        $this->updateData = true;
    }

    public function update()
    {
        $id = $this->menuId;
        $this->validateAdd(); // Memastikan validasi terjadi sebelum menyimpan

        Menu::find($id)->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
        ]);

        session()->flash('successToast', "Menu '" . $this->name . "' was <span class='badge bg-label-success'>edited</span>");
        $this->clear();
    }

    public function delete()
    {
        if (count($this->data_selected_id) == 1 || $this->menuId != '') {
            $id = $this->menuId;
            $deletedMenu = Menu::find($id);
            $deletedName = $deletedMenu->name;
            Menu::find($id)->delete();
            session()->flash('successToast', "Menu '" . $deletedName . "' was <span class='badge bg-label-danger'>Deleted</span>");
        } elseif (count($this->data_selected_id) >= 2) {
            foreach ($this->data_selected_id as $id) {
                Menu::find($id)->delete();
            }
            session()->flash('successToast', "'" . count($this->data_selected_id) . "' Menu datas were <span class='badge bg-label-danger'>Deleted</span>");
        }
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != '') {
            $this->menuId = $id;
            $this->deletingName = Menu::find($id)->name;
        } elseif (count($this->data_selected_id) == 1) {
            $this->menuId = $this->data_selected_id[0];
            $this->deletingName = Menu::find($this->menuId)->name;
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

        $this->updateData = false;
        $this->deletingName = null;
        $this->data_selected_id = [];
        $this->bulkDelete = false;
    }
}

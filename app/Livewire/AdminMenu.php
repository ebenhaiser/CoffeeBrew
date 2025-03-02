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
    protected $paginationTheme = 'bootstrap';
    public $menuId;
    public $name;
    public $category_id;
    public $description;
    public $price;
    public $stock;
    public $image;
    public $keyword;
    public $sortColumn = 'name';
    public $sortDirection = 'asc';
    public $updateData = false;
    public $deletingName;

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
                'image' => 'nullable|image|max:2048',
            ]);
        } else {
            $this->validate([
                'name' => ['required', Rule::unique('menus', 'name')->ignore($this->menuId)],
                'price' => 'required|numeric',
                'category_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|image|max:2048',
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
        $this->dispatch('closeAllModals');
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
        $this->dispatch('closeAllModals');
    }

    public function delete()
    {
        $id = $this->menuId;
        $deletedMenu = Menu::find($id);
        $deletedName = $deletedMenu->name;
        Menu::find($id)->delete();
        session()->flash('successToast', "Menu '" . $deletedName . "' was <span class='badge bg-label-danger'>Deleted</span>");
        $this->clear();
        $this->dispatch('closeAllModals');
    }

    public function delete_confirmation($id)
    {
        $this->menuId = $id;
        $this->deletingName = Menu::find($id)->name;
    }

    public function clear()
    {
        $this->name = null;
        $this->menuId = null;
        $this->category_id = null;;
        $this->description = null;;
        $this->price = null;
        $this->stock = null;
        $this->image = null;
        $this->updateData = false;
    }
}
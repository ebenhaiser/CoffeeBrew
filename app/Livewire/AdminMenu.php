<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class AdminMenu extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $name;
    public $category_id;
    public $description;
    public $price;
    public $stock;
    public $image;
    public $keyword;
    public $sortColumn = 'name';
    public $sortDirection = 'asc';
    // public $addError = [];
    public $validateNewData = false;
    public $addNewData = false;

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
        $this->validate([
            'name' => 'required|unique:menus,name',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
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

        session()->flash('message', 'Menu berhasil ditambahkan.');
        $this->reset(); // Reset input setelah submit
        // $this->dispatchBrowserEvent('close-modal'); // Event untuk menutup modal
        $this->dispatch('closeAllModals');
    }

    // public function store()
    // {
    // $this->dispatch('closeAllModals');
    // $rules = [
    //     'name' => 'required',
    //     'price' => 'required|numeric',
    //     'category_id' => 'nullable|exists:categories,id',
    //     'description' => 'nullable|string',
    //     'stock' => 'nullable|integer|min:0',
    //     'image' => 'nullable|image|max:2048',
    // ];

    // $messages = [
    //     'name.required' => 'Name is required',
    //     'price.required' => 'Price is required',
    //     'price.numeric' => 'Price must be a number',
    // ];

    // $validated = $this->validate($rules, $messages);

    // Menu::create([
    //     'name' => $this->name,
    //     'category_id' => $this->category_id,
    //     'description' => $this->description,
    //     'price' => $this->price,
    //     'stock' => $this->stock ?? 0,
    //     'image' => $this->image ?? null,
    // ]);

    // Menu::create($validated);

    // session()->flash('successToast', "New menu '" . $this->name . "' successfully created");
    // $this->clear();
    // $this->addNewData = true;
    // }


    public function clear()
    {
        $this->name = null;
        $this->category_id = null;;
        $this->description = null;;
        $this->price = null;
        $this->stock = null;
        $this->image = null;
    }
}

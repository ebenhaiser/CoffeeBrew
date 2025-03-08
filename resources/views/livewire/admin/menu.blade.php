<div>
    <x-admin.toast />
    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" class="form-control" id="" placeholder="Search..." wire:model.live="keyword">
        </div>
        <div class="col-md-6 mb-3" align="right">
            <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addData">
                <i class='bx bx-plus'></i>
            </button>
            @if ($bulkDelete == false)
                <button type="submit" class="btn btn-danger" wire:click="activateBulkDelete()">
                    <i class='bx bx-trash'></i>
                </button>
            @else
                <a @if (count($this->data_selected_id) <= 0) wire:click="clear()" @else wire:click="delete_confirmation('')" data-bs-toggle="modal" data-bs-target="#deleteData" @endif
                    class="btn btn-danger" style="color: white">
                    {{ '(' . count($data_selected_id) . ') ' }}<i class='bx bx-trash'></i>
                </a>
                <button class="btn btn-secondary" wire:click="clear()">
                    <i class='bx bx-x'></i>
                </button>
            @endif
        </div>
    </div>
    <div class="table-responsive">
        {{ $menus->links() }}
        <table class="table table-striped table-bordered table-sortable">
            <thead>
                <tr>
                    @if ($bulkDelete == true)
                        <th>
                            <i class='bx bx-trash'></i>
                        </th>
                    @else
                        <th>#</th>
                    @endif
                    <th class="sort {{ $sortColumn == 'name' ? $sortDirection : '' }}" wire:click="sort('name')">Name
                    </th>
                    <th class="sort {{ $sortColumn == 'category_id' ? $sortDirection : '' }} text-center"
                        wire:click="sort('category_id')">
                        Category</th>
                    <th class="sort {{ $sortColumn == 'description' ? $sortDirection : '' }}"
                        wire:click="sort('description')">
                        Description
                    </th>
                    <th class="text-center">Image</th>
                    <th class="sort {{ $sortColumn == 'price' ? $sortDirection : '' }}" wire:click="sort('price')">Price
                    </th>
                    <th class="sort {{ $sortColumn == 'stock' ? $sortDirection : '' }}" wire:click="sort('stock')">Stock
                    </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $key => $menu)
                    <tr>
                        @if ($bulkDelete == true)
                            <td>
                                <input class="form-check-input shadow" type="checkbox" id="flexCheckDefault"
                                    value="{{ $menu->id }}" wire:key="{{ $menu->id }}"
                                    wire:model.live="data_selected_id">
                            </td>
                        @else
                            <td>{{ $menus->firstItem() + $key }}</td>
                        @endif
                        <td>{{ $menu->name }}</td>
                        <td align="center">
                            @if ($menu->category_id)
                                {{ $menu->category->name }}
                            @else
                                <span class="badge rounded-pill bg-label-warning">Uncategorized</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($menu->description, 50) }}</td>
                        <td align="center">
                            @if ($menu->image && Storage::disk('public')->exists($menu->image))
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                    width="100" height="100" style="object-fit:cover" class="rounded">
                            @else
                                <i class="text-secondary">no image</i>
                            @endif

                        </td>
                        <td>
                            {{ 'Rp. ' . number_format($menu->price, 2, '.', ',') }}
                        </td>
                        <td>{{ $menu->stock }}</td>
                        <td align="center">
                            <a wire:click="edit({{ $menu->id }})" class="btn btn-sm btn-warning"
                                data-bs-toggle="modal" data-bs-target="#addData" style="color: white">
                                <i class='bx bx-edit'></i>
                            </a>
                            @if ($bulkDelete == false)
                                <a wire:click="delete_confirmation({{ $menu->id }})" class="btn btn-sm btn-danger"
                                    style="color: white" data-bs-toggle="modal" data-bs-target="#deleteData">
                                    <i class='bx bx-trash'></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $menus->links() }}
        </div>
    </div>

    <!-- Modal add data-->
    <div class="modal fade" id="addData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    @if ($updateData == false)
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Menu</h1>
                    @else
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit '{{ $name }}'</h1>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="clear()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Insert menu name" wire:model="name" required>
                            @if ($errors->has('name'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif

                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">category</label>
                            <select name="category_id" class="form-control" id="" wire:model="category_id">
                                <option value="">-- choose category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('category_id'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('category_id') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="name" name="description"
                                placeholder="Insert menu description" wire:model="description">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Price</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                <input type="text" class="form-control" placeholder="Insert menu price"
                                    aria-label="" aria-describedby="basic-addon11" wire:model="price">
                            </div>
                            @if ($errors->has('price'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('price') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="text" class="form-control" id="name" name="stock"
                                placeholder="Insert menu stock" wire:model="stock">
                            @if ($errors->has('stock'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('stock') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" wire:model="image">
                            @if ($errors->has('image'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('image') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid object-fit-cover rounded"
                                    style="width: 100%; max-width: 300px; aspect-ratio: 1 / 1; object-fit:cover"
                                    alt="Uploaded Image">
                            @elseif ($updateData == true && $imagePath && Storage::disk('public')->exists($imagePath))
                                <img src="{{ asset('storage/' . $imagePath) }}"
                                    class="img-fluid object-fit-cover rounded"
                                    style="width: 100%; max-width: 300px; aspect-ratio: 1 / 1; object-fit:cover"
                                    alt="Uploaded Image">
                            @else
                                <img src="https://placehold.co/100" class="rounded w-100" alt="">
                            @endif
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="clear()">Cancel</button>
                    @if ($updateData == false)
                        <button type="button" class="btn btn-primary" wire:click="store()">Add</button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click="update()">Edit</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- modal delete data --}}
    <div class="modal fade" id="deleteData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: red">Deleting File</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="clear()"></button>
                </div>
                <div class="modal-body">
                    @if ($deletingName)
                        Are you sure want to delete '{{ $deletingName }}'?
                    @elseif (count($data_selected_id) > 1)
                        Are you sure want to delete these '{{ count($data_selected_id) }}' datas?
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="clear()">Cancel</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                        wire:click="delete()">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

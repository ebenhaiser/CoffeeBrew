<div>
    <x-admin.toast />
    <div class="d-flex gap-3 justify-content-between mb-3">
        <input type="text" class="form-control" id="" style="max-width: 350px" placeholder="Search for menus?"
            wire:model.live="keyword">
        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addData">
            <i class='bx bx-plus'></i>
        </button>
    </div>
    <div class="table-responsive">
        {{ $menus->links() }}
        <table class="table table-striped table-sortable">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="sort {{ $sortColumn == 'name' ? $sortDirection : '' }}" wire:click="sort('name')">Name
                    </th>
                    <th class="sort {{ $sortColumn == 'category_id' ? $sortDirection : '' }}"
                        wire:click="sort('category_id')">
                        Category</th>
                    <th class="sort {{ $sortColumn == 'description' ? $sortDirection : '' }}"
                        wire:click="sort('description')">
                        Description</th>
                    <th class="sort {{ $sortColumn == 'price' ? $sortDirection : '' }}" wire:click="sort('price')">Price
                    </th>
                    <th class="sort {{ $sortColumn == 'stock' ? $sortDirection : '' }}" wire:click="sort('stock')">Stock
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $key => $menu)
                    <tr>
                        <td>{{ $menus->firstItem() + $key }}</td>
                        <td>{{ $menu->name }}</td>
                        <td>{{ $menu->category_id ? $menu->category->name : 'uncategorized' }}</td>
                        <td>{{ $menu->description }}</td>
                        <td>{{ 'Rp.' . $menu->price }}</td>
                        <td>{{ $menu->stock }}</td>
                        <td align="right">
                            <a wire:click="edit({{ $menu->id }})" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#addData" style="color: white">
                                <i class='bx bx-edit'></i>
                            </a>
                            <a wire:click="delete_confirmation({{ $menu->id }})" class="btn btn-danger"
                                style="color: white" data-bs-toggle="modal" data-bs-target="#deleteData">
                                <i class='bx bx-trash'></i>
                            </a>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <input type="file" class="form-control" name="image"
                                placeholder="Insert menu stock">
                        </div>
                        <div class="col-md-6 mb-3">
                            <img src="https://placehold.co/400" class="w-100" alt="">
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
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: red">Deleting File</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure want to delete '{{ $deletingName }}'?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="clear()">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete()">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

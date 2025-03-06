<div>
    <x-admin.toast />
    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" class="form-control" id="" placeholder="Search for order?"
                wire:model.live="keyword">
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
        {{ $items->links() }}
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
                    <th class="sort {{ $sortColumn == 'order_code' ? $sortDirection : '' }}"
                        wire:click="sort('order_code')">
                        Code
                    </th>
                    <th class="sort {{ $sortColumn == 'table_id' ? $sortDirection : '' }}"
                        wire:click="sort('table_id')">
                        Table
                    </th>
                    <th class="sort {{ $sortColumn == 'status' ? $sortDirection : '' }}" wire:click="sort('status')">
                        Status
                    </th>
                    <th colspan="2" class="sort {{ $sortColumn == 'created_at' ? $sortDirection : '' }}"
                        wire:click="sort('created_at')">
                        Date
                    </th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $key => $item)
                    <tr>
                        @if ($bulkDelete == true)
                            <td>
                                <input class="form-check-input shadow" type="checkbox" id="flexCheckDefault"
                                    value="{{ $item->id }}" wire:key="{{ $item->id }}"
                                    wire:model.live="data_selected_id">
                            </td>
                        @else
                            <td>{{ $items->firstItem() + $key }}</td>
                        @endif
                        <td>{{ $item->order_code }}</td>
                        <td align="center">
                            @if ($item->table)
                                {{ $item->table->table_number }}
                            @else
                                <span class="badge rounded-pill bg-label-warning">unknown</span>
                            @endif
                        </td>
                        <td align="center">
                            {!! match ($item->status) {
                                0 => '<span class="badge rounded-pill bg-label-warning">pending</span>',
                                1 => '<span class="badge rounded-pill bg-label-success">completed</span>',
                                -1 => '<span class="badge rounded-pill bg-label-danger">cancelled</span>',
                                default => 'pending',
                            } !!}
                        </td>
                        <td align="center">{{ $item->created_at->format('d M Y') }}</td>
                        <td align="center">{{ $item->created_at->format('H:i') }}</td>
                        <td align="center">
                            @if ($item->status == 0)
                                <a wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal" data-bs-target="#addData" style="color: white">
                                    <i class='bx bx-edit'></i>
                                </a>
                            @else
                                <a wire:click="edit({{ $item->id }})" class="btn btn-sm btn-success"
                                    data-bs-toggle="modal" data-bs-target="#viewData" style="color: white">
                                    <i class='bx bx-show'></i>
                                </a>
                            @endif
                            @if ($bulkDelete == false)
                                <a wire:click="delete_confirmation({{ $item->id }})" class="btn btn-sm btn-danger"
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
            {{ $items->links() }}
        </div>
    </div>

    <!-- Modal add data-->
    {{-- <div class="modal fade" id="addData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    @if ($updateData == false)
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Table</h1>
                    @else
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Table Number '{{ $table_number }}'
                        </h1>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="clear()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Table Number</label>
                            <input type="text" class="form-control" placeholder="Insert table number"
                                wire:model="table_number" required>
                            @if ($errors->has('table_number'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('table_number') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">QR Code</label>
                            <input type="text" class="form-control" placeholder="Insert QR Code" wire:model="qr_code"
                                required {{ $updateData == true ? 'readonly' : '' }}>
                            @if ($updateData == false)
                                @if ($errors->has('qr_code'))
                                    <div id="defaultFormControlHelp" class="form-text text-danger">
                                        {{ $errors->first('qr_code') }}
                                    </div>
                                @else
                                    <div id="defaultFormControlHelp" class="form-text text-warning">
                                        Cannot be changed
                                    </div>
                                @endif
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
    </div> --}}

    {{-- modal delete data --}}
    {{-- <div class="modal fade" id="deleteData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
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
    </div> --}}
</div>

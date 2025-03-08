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
                                    data-bs-toggle="modal" data-bs-target="#addData" style="color: white">
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
    <div class="modal fade" id="addData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    @if ($editData == false)
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Order</h1>
                    @else
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Order '{{ $order_code }}'
                        </h1>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="clear()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- order detail --}}
                    <div class="row mb-3">
                        {{-- order code --}}
                        <div class="col-md-4">
                            <label class="form-label">Order_Code</label>
                            <input type="text" class="form-control" placeholder="" wire:model="order_code" readonly>
                            @if ($errors->has('order_code'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('order_code') }}
                                </div>
                            @endif
                        </div>
                        {{-- table number --}}
                        <div class="col-md-4">
                            <label class="form-label">Table Number</label>
                            <select class="form-control" id="" wire:model="table_id"
                                {{ $status == 1 || $status == -1 ? 'disabled' : '' }}>
                                <option value="">-- Select Table --</option>
                                @foreach ($tables as $table)
                                    <option value="{{ $table->id }}">{{ $table->table_number }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('table_id'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('table_id') }}
                                </div>
                            @endif
                        </div>
                        {{-- status --}}
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            @if ($editData == true)
                                @if ($status == 1 || $status == -1)
                                    <select class="form-control" id="" wire:model="status" disabled>
                                        <option value="1">Completed</option>
                                        <option value="-1">Cancelled</option>
                                    </select>
                                @else
                                    <select class="form-control" id="" wire:model="status">
                                        <option value="0">Pending</option>
                                        <option value="-1">Cancelled</option>
                                    </select>
                                @endif
                            @else
                                <select class="form-control" id="" wire:model="status" disabled>
                                    <option value="0">Pending</option>
                                </select>
                            @endif
                            @if ($errors->has('status'))
                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr />
                    {{-- order items --}}
                    @if ($editData == false)
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-outline-primary">
                                <i class='bx bx-plus'></i>
                            </button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            @if ($editData == true)
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td>{!! $item->menu->category->name ?? '<i>Uncategorized</i>' !!}</td>
                                            <td>{!! $item->menu->name ?? '<i>Deleted</i>' !!}</td>
                                            <td> {{ 'Rp. ' . number_format($item->menu->price, 2, ',', ',') }}</td>
                                            <td align="center">{{ $item->quantity }}</td>
                                            <td> {{ 'Rp. ' . number_format($item->subtotal_price, 2, ',', ',') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                {{-- @foreach ($collection as $item)
                                @endforeach --}}
                            @endif
                            <tfoot>
                                <tr>
                                    <th colspan="4">Subtotal</th>
                                    <td>
                                        {{ 'Rp. ' . number_format($total_price, 2, ',', ',') }}

                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4">Amount Paid</th>
                                    <td>
                                        @if ($status == 1)
                                            {{ 'Rp. ' . number_format($amount_paid, 2, ',', ',') }}
                                        @else
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                <input type="text" class="form-control"
                                                    placeholder="Insert amount pay" aria-label=""
                                                    aria-describedby="basic-addon11" wire:model.live="amount_paid"
                                                    {{ $status == -1 ? 'disabled' : '' }}>
                                            </div>
                                            @if ($errors->has('amount_paid'))
                                                <div id="defaultFormControlHelp" class="form-text text-danger">
                                                    {{ $errors->first('amount_paid') }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4">Amount Change</th>
                                    <td>
                                        {{ 'Rp. ' . number_format($amount_change, 2, ',', ',') }}

                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="clear()">Cancel</button>
                    @if ($editData == true)
                        @if ($status == 0)
                            <button type="button" class="btn btn-primary" wire:click="update()">Edit</button>
                        @elseif ($status == 1)
                            <button type="button" class="btn btn-warning">Print</button>
                        @endif
                    @else
                        <button type="button" class="btn btn-primary" wire:click="store()">Add</button>
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

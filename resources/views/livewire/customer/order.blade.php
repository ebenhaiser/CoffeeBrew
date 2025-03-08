<div class="order-container">
    <x-slot:title>Order Table: {{ $table_number }}</x-slot:title>

    <div>
        <div class="header">
            <div class="d-flex justify-content-between">
                <h2>Table: {{ $table_number }}</h2>
                <h5>CoffeeBrew</h5>
            </div>
        </div>
        <div class="card search-card shadow">
            <div class="card-body">
                <input type="text" class="form-control" placeholder="Looking for beverage?" wire:model.live="keyword">
            </div>
        </div>
        <div class="menu-wrapper">
            <div class="row">
                @php
                    $groupedItems = $items->groupBy('category.name');
                @endphp
                <div style="margin-bottom: 100px">
                    @foreach ($groupedItems as $category => $menus)
                        <h5 class="">{{ $category }}</h5> <!-- Menampilkan nama kategori -->
                        <div class="mb-4">
                            @foreach ($menus as $item)
                                <div class="col-sm-6 mb-3">
                                    <div class="card menu-item shadow">
                                        <div class="card-body">
                                            <div class="d-flex gap-3">
                                                <div class="col-sm-5">
                                                    @if ($item->image)
                                                        <img src="{{ asset('storage/' . $item->image) }}" alt=""
                                                            style="width: 100%; max-width: 300px; aspect-ratio: 1 / 1; object-fit:cover">
                                                    @else
                                                        <img src="https://placehold.co/100" alt="">
                                                    @endif
                                                </div>
                                                <div class="col-sm-7">
                                                    <div class="menu-content">
                                                        <h6>{{ $item->name }}</h6>
                                                        <p>{{ 'Rp. ' . number_format($item->price, 2, ',', '.') }}</p>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center ms-auto me-3">
                                                            <button class="btn btn-sm btn-primary"
                                                                wire:click="minusItem({{ $item->id }})"
                                                                {{ isset($itemQuantity[$item->id]) && $itemQuantity[$item->id] > 0 ? '' : 'disabled' }}
                                                                style="width: 40px;">
                                                                <i class='bx bx-minus'></i>
                                                            </button>

                                                            <span class="my-auto text-center"
                                                                style="min-width: 30px; display: inline-block;">
                                                                {{ $itemQuantity[$item->id] ?? 0 }}
                                                            </span>

                                                            <button class="btn btn-sm btn-primary"
                                                                wire:click="plusItem({{ $item->id }})"
                                                                style="width: 40px;">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="price-wrapper">
            <div class="price-content d-flex justify-content-between">
                <div class="price">
                    <h7>Total Price</h7>
                    <h6>{{ 'Rp. ' . number_format($total_price, 2, ',', ',') }}</h6>
                </div>
                <div class="my-auto">
                    <button wire:click="getOrderedItems()" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#orderDetail"
                        {{ !empty($itemQuantity) && array_sum($itemQuantity) > 0 ? '' : 'disabled' }}>
                        <i class='bx bx-shopping-bag'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Order Detail-->
    <div class="modal fade" id="orderDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header shadow justify-content-between">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Order Detail</h1>
                    <div class="d-flex gap-3">
                        <img src="{{ asset('img/svg/table.svg') }}" alt="" width="18">
                        <span>{{ $table_number }}</span>
                    </div>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div style="margin-bottom: 100px">
                        @foreach ($orderedItems as $order)
                            <div class="card mb-2 shadow-sm">
                                <div class="card-header">
                                    <h6>
                                        {{ $order['name'] }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <img src="{{ asset('storage/' . $order['image']) }}" alt=""
                                            style="max-width: 50px; aspect-ratio: 1 / 1; object-fit:cover">
                                        <div>
                                            <div>Price: <span
                                                    class="text-secondary">{{ 'Rp. ' . number_format($order['price'], 2, ',', ',') }}</span>
                                            </div>
                                            <div>Quantity: <span class="text-secondary">{{ $order['quantity'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" align="right">
                                    <span style="font-weight: 500">Subtotal:
                                    </span><span>{{ 'Rp. ' . number_format($order['subtotal'], 2, ',', ',') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between price-wrapper">
                    <div class="price-content d-flex justify-content-between">
                        <div class="price">
                            <h7>Total Price</h7>
                            <h6>{{ 'Rp. ' . number_format($total_price, 2, ',', ',') }}</h6>
                        </div>
                        <div class="my-auto">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class='bx bx-arrow-back'></i>
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="createOrder()"
                                data-bs-target="#createOrderModal" data-bs-toggle="modal">
                                <i class='bx bx-shopping-bag'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create Order --}}
    <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">Order Created</h1>
                </div>
                <div class="modal-body" align="center">
                    <div class="mb-3">
                        <i class='bx bxs-shopping-bag' style="font-size: 100px; color: #8b4513;"></i>
                        <!-- Warna coklat kopi -->
                    </div>
                    <h5>Order Code:</h5>
                    <p>{{ $order_code }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:click="clear">Done</button>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="order-container">
    <div class="header">
        <div class="d-flex justify-content-between">
            <h2>Table: XXX</h2>
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
            @foreach ($groupedItems as $category => $menus)
                <h5 class="">{{ $category }}</h5> <!-- Menampilkan nama kategori -->
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
                                            <div class="d-flex justify-content-between align-items-center ms-auto me-3">
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
                                                    wire:click="plusItem({{ $item->id }})" style="width: 40px;">
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
                <div class="mb-3"></div>
            @endforeach
        </div>
    </div>
    <div class="price-wrapper">
        <div class="price-content d-flex justify-content-between">
            <div class="price">
                <h7>Total Price</h7>
                <h6>{{ 'Rp. ' . number_format($total_price, 2, ',', ',') }}</h6>
            </div>
            <div class="my-auto">
                <button class="btn btn-primary">Pay</button>
            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CoffeeBrew</title>
    <x-head />
</head>

<body>
    <div class="order-container">
        <div class="header">
            <div class="d-flex justify-content-between">
                <h2>Table: XXX</h2>
                <h5>CoffeeBrew</h5>
            </div>
        </div>
        <div class="card search-card shadow">
            <div class="card-body">
                <input type="text" class="form-control" placeholder="Looking for beverage?">
            </div>
        </div>
        <div class="menu-wrapper">
            <div class="row gap-3">
                <div class="col-sm-6">
                    <div class="card menu-item shadow">
                        <div class="card-body">
                            <div class="d-flex gap-2">
                                <img src="https://placehold.co/400" alt="">
                                <div class="menu-content">
                                    <h5>Menu Item 1</h5>
                                    <p>Rp.20.000</p>
                                    <div class="d-flex gap-3 justify-content-between">
                                        <button class="btn btn-primary">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                        <span class="my-auto">0</span>
                                        <button class="btn btn-primary">
                                            <i class='bx bx-minus'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="price-wrapper">
            <div class="price-content d-flex justify-content-between">
                <div class="price">
                    <h7>Total Price</h7>
                    <h6>Rp.20.000</h6>
                </div>
                <div class="my-auto">
                    <button class="btn btn-primary">Pay</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

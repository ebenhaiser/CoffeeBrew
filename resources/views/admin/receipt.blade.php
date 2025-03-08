<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 300px;
            margin: auto;
            padding: 10px;
            border: 1px solid #000;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 5px;
            border-bottom: 1px dashed #000;
        }

        .total {
            font-size: 1.2em;
            font-weight: bold;
        }

        .no-print {
            margin-top: 10px;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="center bold">CoffeeBrew</div>
    <div class="center">CoffeeBrew st. 123rd, Gondangdia</div>
    <div class="center">------------------------------------</div>
    <div><strong>Order:</strong> <span id="order_code">{{ $order->order_code }}</span></div>
    <div><strong>Table:</strong> <span id="table_number">{{ $order->table->table_number }}</span></div>
    <div><strong>Date:</strong> <span id="date">{{ $order->created_at->format('d-m-Y H:i') }}
        </span></div>
    <div class="center">------------------------------------</div>
    <table>
        <tbody id="order_items">
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->menu->name }}</td>
                    <td>{{ $item->quantity . 'X' }}</td>
                    <td>{{ 'Rp.' . number_format($item->subtotal_price, 2, '.', ',') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="center">------------------------------------</div>
    <div class="bold">Total: <span
            id="total_price">{{ 'Rp.' . number_format($order->total_price, 2, '.', ',') }}</span></div>
    <div>Amount Paid: <span id="amount_paid">{{ 'Rp.' . number_format($order->amount_paid, 2, '.', ',') }}</span>
    </div>
    <div>Change: <span id="amount_change">{{ 'Rp.' . number_format($order->amount_change, 2, '.', ',') }}</span></div>
    <div class="center">------------------------------------</div>
    <div class="center">Thank you for your visit!</div>
    <div class="no-print">
        <button onclick="window.print()">Print</button>
    </div>
</body>

</html>

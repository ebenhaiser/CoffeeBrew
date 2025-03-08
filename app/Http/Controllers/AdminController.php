<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function menus()
    {
        return view('admin.menus');
    }
    public function tables()
    {
        return view('admin.tables');
    }

    public function categories()
    {
        return view('admin.categories');
    }

    public function orders()
    {
        return view('admin.orders');
    }

    public function printReceipt($slug)
    {
        $order = Order::with('items.menu', 'table')->where('order_code', $slug)->first();
        return view('admin.receipt', compact('order'));
    }
}

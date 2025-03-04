<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function orderWithoutSlug() //for development
    {
        $slug = '';
        return view('customer.order', compact('slug'));
    }

    public function order($slug)
    {
        return view('customer.order', compact('slug'));
    }
}

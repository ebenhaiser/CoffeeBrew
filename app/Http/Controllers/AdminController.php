<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Basic inventory index page (to be expanded with items, stock, and purchase/sales).
     */
    public function index()
    {
        return view('inventory.index');
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FeeRepository;

class FeeController extends Controller
{
    public function __construct(
        protected FeeRepository $fees
    ) {
    }

    /**
     * Display a listing of fees.
     */
    public function index()
    {
        $fees = $this->fees->all();

        return view('fees.index', compact('fees'));
    }
}


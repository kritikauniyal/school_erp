<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Basic reports index page (to be expanded with charts and exports).
     */
    public function index()
    {
        return view('reports.index');
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionFeeStructureController extends Controller
{
    public function index()
    {
        return view('admin.admission-fee-structure');
    }
}

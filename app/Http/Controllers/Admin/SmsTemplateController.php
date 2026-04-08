<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the SMS templates.
     */
    public function index()
    {
        return view('sms-templates.index');
    }
}

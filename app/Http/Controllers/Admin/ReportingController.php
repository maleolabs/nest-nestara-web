<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
 
    public function index()
    {
        return back();
        return response()->view('admin.reporting.index');
    }

}
<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
 
    public function index()
    {
        return response()->view('dashboard.reporting.index');
    }

}
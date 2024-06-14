<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemantauanController extends Controller
{
    
    public function index()
    {
        return response()->view('dashboard.pemantauan.index');
    }

}
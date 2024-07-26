<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JenisPerizinanController extends Controller
{
    
    public function index()
    {
        return response()->view('admin.perizinan.index');
    }
    
}